<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Farmer;
use App\Models\Product;

class PublicController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function menu()
    {
        $products = Product::with('category')
            ->where('type', 'cafe')
            ->where('stok', '>', 0)
            ->orderBy('category_id')
            ->orderBy('nama_produk')
            ->get();

        $categories = $products
            ->pluck('category.name')
            ->filter()
            ->unique()
            ->values()
            ->prepend('Semua');

        return view('public.menu', compact('products', 'categories'));
    }

    public function marketplace()
    {
        $products = Product::where('type', 'market')->visibleToPublic()->with('farmer')->get();
        $farmers  = Farmer::verified()->orderBy('name')->get();

        return view('public.marketplace', compact('products', 'farmers'));
    }

    public function caraPersan()
    {
        return view('public.cara-pesan');
    }

    public function tentangKami()
    {
        return view('public.tentang-kami');
    }

    public function kebijakanPrivasi()
    {
        return view('public.kebijakan-privasi');
    }

    public function syaratKetentuan()
    {
        return view('public.syarat-ketentuan');
    }
}
