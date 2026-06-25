<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pendapatan' => Order::where('status', 'completed')->sum('total'),
            'total_pesanan'    => Order::count(),
            'total_produk'     => Product::count(),
            'total_petani'     => Farmer::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
