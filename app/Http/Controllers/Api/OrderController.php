<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * POST /api/orders
     * Terima pesanan dari keranjang belanja online (marketplace / menu kafe).
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

            if (is_numeric($item['id']) && isset($dbProds[(int)$item['id']])) {
                // Dari marketplace — ID numerik langsung
                $prod  = $dbProds[(int)$item['id']];
                $pid   = $prod->id_product;
                $price = $prod->harga;
            } elseif (!empty($item['name'])) {
                // Dari menu kafe — cari by nama
                $prod = Product::where('nama_produk', $item['name'])->first();
                if ($prod) {
                    $pid   = $prod->id_product;
                    $price = $prod->harga;
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
            ];
        }

        if (empty($lineItems)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada produk valid dalam pesanan.'], 422);
        }

        $realTotal += 2000; // biaya layanan

        try {
            $order = DB::transaction(function () use ($userId, $realTotal, $lineItems) {
                $order = Order::create([
                    'user_id' => $userId,
                    'total'   => $realTotal,
                    'type'    => 'mixed',
                    'source'  => 'online',
                    'status'  => 'pending',
                ]);

                foreach ($lineItems as $li) {
                    OrderItem::create(array_merge(['order_id' => $order->id], $li));
                    // Kurangi stok
                    Product::where('id_product', $li['product_id'])
                           ->decrement('stok', $li['quantity']);
                }

                return $order;
            });

            return response()->json([
                'success'  => true,
                'message'  => 'Pesanan berhasil dibuat.',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
