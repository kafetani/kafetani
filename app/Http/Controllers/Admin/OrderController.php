<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Daftar pesanan (bisa difilter by status)
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');

        $orders = Order::with(['user', 'items.product'])
            ->when($statusFilter !== 'all', fn($q) => $q->where('status', $statusFilter))
            ->orderByDesc('created_at')
            ->get();

        return view('admin.orders.index', compact('orders', 'statusFilter'));
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'status'   => ['required', 'in:pending,processing,ready,completed,cancelled'],
        ]);

        $order = Order::findOrFail($request->input('order_id'));
        $order->update(['status' => $request->input('status')]);

        return redirect()->route('admin.orders.index', ['status' => $request->query('status', 'all')])
                         ->with('success', "Status pesanan #{$order->id} berhasil diperbarui!");
    }
}
