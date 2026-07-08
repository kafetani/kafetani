<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * GET /api/orders
     * Riwayat pesanan milik user yang sedang login (dipakai app Android).
     * Dipisah dari store() di bawah karena itu juga dipakai checkout web
     * (session guard) — method baru ini eksplisit pakai guard 'api'.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', auth('api')->id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'orders'  => OrderResource::collection($orders),
        ]);
    }

    /**
     * GET /api/orders/{order}
     * Detail satu pesanan — hanya boleh dilihat oleh pemiliknya. Dipakai app
     * Android buat polling status setelah pembayaran Midtrans (karena update
     * payment_status baru masuk lewat webhook async, bukan langsung saat
     * checkout), maupun buat lihat riwayat.
     */
    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== auth('api')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        $order->load(['items.product']);

        return response()->json([
            'success' => true,
            'order'   => new OrderResource($order),
        ]);
    }

    /**
     * POST /api/orders
     * Terima pesanan dari keranjang belanja online (marketplace / menu kafe)
     * dan buat transaksi Midtrans Snap token.
     *
     * Payload JSON:
     *   { cart: [{id, name, price, qty, image}], total: number }
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cart'        => ['required', 'array', 'min:1'],
            'cart.*.id'   => ['required'],
            'cart.*.qty'  => ['required', 'integer', 'min:1'],
            'cart.*.name' => ['nullable', 'string'],
            'total'       => ['nullable', 'integer', 'min:0'],
        ]);

        $cart    = $data['cart'];
        $userId  = auth()->id();

        // Kumpulkan semua ID numerik untuk cek DB sekaligus
        $numericIds = collect($cart)
            ->filter(fn($i) => is_numeric($i['id']))
            ->pluck('id')
            ->map('intval')
            ->toArray();

        $dbProds = Product::whereIn('id_product', $numericIds)
                          ->get()
                          ->keyBy('id_product');

        $lineItems = [];
        $realTotal = 0;

        foreach ($cart as $item) {
            $qty   = max(1, (int) ($item['qty'] ?? 1));
            $pid   = null;
            $price = 0;
            $name  = '';
            $prod  = null;

            if (is_numeric($item['id'])) {
                $prod = $dbProds->get((int)$item['id']);
            }

            if ($prod) {
                // Dari marketplace — ID numerik langsung
                $pid   = $prod->id_product;
                $price = $prod->harga;
                $name  = $prod->nama_produk;
            } elseif (!empty($item['name'])) {
                // Dari menu kafe — cari by nama
                $prod = Product::where('nama_produk', $item['name'])->first();
                if ($prod) {
                    $pid   = $prod->id_product;
                    $price = $prod->harga;
                    $name  = $prod->nama_produk;
                }
            }

            if (!$pid || $qty <= 0) continue;

            $subtotal   = $price * $qty;
            $realTotal += $subtotal;

            $lineItems[] = [
                'product_id' => $pid,
                'quantity'   => $qty,
                'price'      => $price,
                'subtotal'   => $subtotal,
                'name'       => $name,
            ];
        }

        if (empty($lineItems)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada produk valid dalam pesanan.'], 422);
        }

        $realTotal += 2000; // biaya layanan

        // Konfigurasi Midtrans sudah diatur secara global di AppServiceProvider::boot()

        try {
            $order = DB::transaction(function () use ($userId, $realTotal, $lineItems) {
                // Kunci baris produk & validasi kecukupan stok sebelum transaksi disimpan
                // (mencegah race condition antara cek stok dan pengurangan stok).
                $insufficient = [];
                foreach ($lineItems as $li) {
                    $locked = Product::where('id_product', $li['product_id'])
                                      ->lockForUpdate()
                                      ->first();

                    if (! $locked || $locked->stok < $li['quantity']) {
                        $insufficient[] = sprintf(
                            '%s (diminta %d, tersedia %d)',
                            $li['name'] ?: ('#' . $li['product_id']),
                            $li['quantity'],
                            $locked->stok ?? 0
                        );
                    }
                }

                if (! empty($insufficient)) {
                    throw new \RuntimeException('Stok tidak mencukupi untuk: ' . implode(', ', $insufficient));
                }

                // Status awal adalah pending_payment karena diintegrasikan dengan Payment Gateway
                $order = Order::create([
                    'user_id' => $userId,
                    'total'   => $realTotal,
                    'type'    => 'mixed',
                    'source'  => 'online',
                    'status'  => 'pending_payment',
                ]);

                foreach ($lineItems as $li) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $li['product_id'],
                        'quantity'   => $li['quantity'],
                        'price'      => $li['price'],
                        'subtotal'   => $li['subtotal'],
                    ]);
                    // Kurangi stok
                    Product::where('id_product', $li['product_id'])
                           ->decrement('stok', $li['quantity']);
                }

                return $order;
            });

            // Persiapkan payload item details untuk Midtrans
            $midtransItems = [];
            foreach ($lineItems as $li) {
                $midtransItems[] = [
                    'id'       => (string) $li['product_id'],
                    'price'    => $li['price'],
                    'quantity' => $li['quantity'],
                    'name'     => substr($li['name'], 0, 50),
                ];
            }
            
            // Tambahkan biaya layanan ke item details Midtrans
            $midtransItems[] = [
                'id'       => 'service_fee',
                'price'    => 2000,
                'quantity' => 1,
                'name'     => 'Biaya Layanan',
            ];

            $transactionDetails = [
                'order_id'     => (string) $order->id,
                'gross_amount' => $realTotal,
            ];

            $customerDetails = [
                'first_name' => auth()->user()->nama,
                'email'      => auth()->user()->email,
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details'    => $customerDetails,
                'item_details'        => $midtransItems,
            ];

            // Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan snap token ke database
            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'    => true,
                'message'    => 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.',
                'order_id'   => $order->id,
                'snap_token' => $snapToken,
            ]);
        } catch (\RuntimeException $e) {
            // Stok tidak mencukupi
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
