<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * POST /midtrans/notification
     * Menerima webhook notifikasi pembayaran dari Midtrans.
     */
    public function notification(Request $request): JsonResponse
    {
        // Konfigurasi server key & env Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Invalid notification payload.'], 400);
        }

        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        // Cari order berdasarkan id
        $order = Order::with('items.product')->find($orderId);

        if (!$order) {
            Log::warning("Midtrans Notification: Order ID {$orderId} not found.");
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        // Catat metadata transaksi
        $order->transaction_id = $notification->transaction_id;
        $order->payment_type = $paymentType;

        if ($transactionStatus == 'capture') {
            if ($paymentType == 'credit_card') {
                if ($fraudStatus == 'challenge') {
                    $order->payment_status = 'unpaid';
                    $order->status = 'pending_payment';
                } else {
                    $order->payment_status = 'paid';
                    $order->status = 'pending'; // masuk antrian pesanan
                    $order->paid_at = now();
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $order->payment_status = 'paid';
            $order->status = 'pending'; // masuk antrian pesanan
            $order->paid_at = now();
        } elseif ($transactionStatus == 'pending') {
            $order->payment_status = 'unpaid';
            $order->status = 'pending_payment';
        } elseif ($transactionStatus == 'deny') {
            $order->payment_status = 'unpaid';
            $order->status = 'cancelled';
        } elseif ($transactionStatus == 'expire') {
            $order->payment_status = 'expired';
            $order->status = 'cancelled';

            // Mengembalikan stok produk yang dipesan
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stok', $item->quantity);
                }
            }
        } elseif ($transactionStatus == 'cancel') {
            $order->payment_status = 'unpaid';
            $order->status = 'cancelled';

            // Mengembalikan stok produk yang dipesan
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stok', $item->quantity);
                }
            }
        }

        $order->save();

        return response()->json(['success' => true]);
    }
}
