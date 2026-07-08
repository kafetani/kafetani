<?php

namespace App\Http\Controllers\Api\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * GET /api/kasir/products
     * Daftar produk kafe yang tersedia untuk diinput di POS.
     */
    public function products(): JsonResponse
    {
        $products = Product::with('category')
            ->where('type', 'cafe')
            ->where('stok', '>', 0)
            ->orderBy('category_id')
            ->orderBy('nama_produk')
            ->get();

        return response()->json([
            'success'  => true,
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * POST /api/kasir/orders
     * Sama seperti Admin\KasirController::placeOrder() versi web, tapi
     * menerima body JSON (items sebagai array asli).
     *
     * Catatan: berbeda dari checkout online (Api\OrderController::store),
     * pesanan kasir dibayar tunai/QRIS langsung di tempat — jadi TIDAK lewat
     * Midtrans Snap dan payment_status langsung 'paid', bukan 'pending_payment'.
     *
     * Body contoh:
     *   { "items": [{"id": 1, "qty": 2}], "order_type": "dine-in", "customer_name": "Budi" }
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'items'                => ['required', 'array', 'min:1'],
            'items.*.id'           => ['required', 'integer'],
            'items.*.qty'          => ['required', 'integer', 'min:1'],
            'order_type'           => ['required', 'in:dine-in,pickup'],
            'customer_name'        => ['nullable', 'string', 'max:100'],
        ]);

        $customerName = $data['customer_name'] ?: 'Tamu';

        // Ambil harga asli dari DB (anti-manipulasi harga dari klien)
        $ids     = array_column($data['items'], 'id');
        $dbProds = Product::whereIn('id_product', $ids)
                          ->where('type', 'cafe')
                          ->get()
                          ->keyBy('id_product');

        $total     = 0;
        $lineItems = [];

        foreach ($data['items'] as $item) {
            $pid = (int) $item['id'];
            $qty = max(1, (int) $item['qty']);

            if (! isset($dbProds[$pid])) {
                continue;
            }

            $harga      = $dbProds[$pid]->harga;
            $subtotal   = $harga * $qty;
            $total     += $subtotal;
            $lineItems[] = [
                'product'  => $dbProds[$pid],
                'qty'      => $qty,
                'harga'    => $harga,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($lineItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk kafe valid dalam pesanan.',
            ], 422);
        }

        try {
            $order = DB::transaction(function () use ($lineItems, $total, $customerName) {
                // Kunci baris produk & validasi kecukupan stok sebelum transaksi disimpan
                // (mencegah race condition antara cek stok dan pengurangan stok).
                $insufficient = [];
                foreach ($lineItems as $li) {
                    $locked = Product::where('id_product', $li['product']->id_product)
                                      ->lockForUpdate()
                                      ->first();

                    if (! $locked || $locked->stok < $li['qty']) {
                        $insufficient[] = sprintf(
                            '%s (diminta %d, tersedia %d)',
                            $li['product']->nama_produk,
                            $li['qty'],
                            $locked->stok ?? 0
                        );
                    }
                }

                if (! empty($insufficient)) {
                    throw new \RuntimeException('Stok tidak mencukupi untuk: ' . implode(', ', $insufficient));
                }

                $order = Order::create([
                    'user_id'        => auth('api')->id(),
                    'total'          => $total,
                    'type'           => 'cafe',
                    'source'         => 'offline',
                    'customer_name'  => $customerName,
                    'status'         => 'processing',
                    'payment_status' => 'paid',
                    'payment_type'   => 'cash',
                    'paid_at'        => now(),
                ]);

                foreach ($lineItems as $li) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $li['product']->id_product,
                        'quantity'   => $li['qty'],
                        'price'      => $li['harga'],
                        'subtotal'   => $li['subtotal'],
                    ]);

                    $li['product']->decrement('stok', $li['qty']);
                }

                return $order;
            });
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Pesanan berhasil dibuat.',
            'order_id' => $order->id,
        ], 201);
    }
}
