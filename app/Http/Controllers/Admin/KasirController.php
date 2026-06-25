<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Tampilkan halaman POS kasir
     */
    public function index()
    {
        $products = Product::with('category')
            ->where('type', 'cafe')
            ->where('stok', '>', 0)
            ->orderBy('category_id')
            ->orderBy('nama_produk')
            ->get();

        $categories = $products->pluck('category.name')->filter()->unique()->values();

        return view('admin.kasir', compact('products', 'categories'));
    }

    /**
     * Proses pesanan dari kasir (POST)
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'items'         => ['required', 'json'],
            'order_type'    => ['required', 'in:dine-in,pickup'],
            'customer_name' => ['nullable', 'string', 'max:100'],
        ]);

        $items        = json_decode($request->input('items'), true);
        $customerName = $request->input('customer_name') ?: 'Tamu';

        if (empty($items)) {
            return back()->with('error', 'Pesanan kosong.');
        }

        // Ambil harga asli dari DB (anti-manipulasi harga dari klien)
        $ids     = array_map('intval', array_column($items, 'id'));
        $dbProds = Product::whereIn('id_product', $ids)
                          ->where('type', 'cafe')
                          ->get()
                          ->keyBy('id_product');

        $total     = 0;
        $lineItems = [];

        foreach ($items as $item) {
            $pid = (int) $item['id'];
            $qty = max(1, (int) ($item['qty'] ?? 1));

            if (!isset($dbProds[$pid])) continue;

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
            return back()->with('error', 'Tidak ada produk kafe valid dalam pesanan.');
        }

        DB::transaction(function () use ($lineItems, $total, $customerName, &$order) {
            $order = Order::create([
                'user_id'       => auth()->id(),
                'total'         => $total,
                'type'          => 'cafe',
                'source'        => 'offline',
                'customer_name' => $customerName,
                'status'        => 'processing',
            ]);

            foreach ($lineItems as $li) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $li['product']->id_product,
                    'quantity'   => $li['qty'],
                    'price'      => $li['harga'],
                    'subtotal'   => $li['subtotal'],
                ]);

                // Kurangi stok
                $li['product']->decrement('stok', $li['qty']);
            }
        });

        return redirect()->route('admin.kasir')->with('success_order', [
            'id'            => $order->id,
            'customer_name' => $customerName,
            'order_type'    => $request->input('order_type'),
            'items'         => $lineItems,
            'total'         => $total,
        ]);
    }
}
