<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Daftar semua produk (bisa difilter by type)
     */
    public function index(Request $request)
    {
        $type       = $request->query('type', 'all');
        $products   = Product::with('category')->type($type)->orderBy('type')->orderBy('nama_produk')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'type'));
    }

    /**
     * Simpan produk baru atau update produk yang ada
     * (satu method seperti di proyek lama — bedain lewat ada/tidaknya 'id')
     */
    public function save(Request $request)
    {
        $id = $request->input('id');

        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:100'],
            'harga'       => ['required', 'integer', 'min:1'],
            'stok'        => ['required', 'integer', 'min:0'],
            'deskripsi'   => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'type'        => ['required', 'in:cafe,market'],
            'petani'      => ['nullable', 'string', 'max:100'],
            'gambar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Proses upload gambar
        if ($request->hasFile('gambar')) {
            $ext      = $request->file('gambar')->getClientOriginalExtension();
            $filename = uniqid('prod_', true) . '.' . strtolower($ext);
            $request->file('gambar')->move(public_path('img/products'), $filename);

            // Hapus gambar lama jika edit
            if ($id) {
                $old = Product::find($id)?->gambar;
                if ($old) @unlink(public_path('img/products/' . $old));
            }

            $data['gambar'] = $filename;
        } else {
            // Pakai gambar lama jika tidak ada upload baru
            $data['gambar'] = $request->input('gambar_lama');
        }

        if ($id) {
            Product::findOrFail($id)->update($data);
            $msg = 'Produk berhasil diperbarui.';
        } else {
            Product::create($data);
            $msg = 'Produk baru berhasil ditambahkan.';
        }

        return redirect()->route('admin.products.index')->with('success', $msg);
    }

    /**
     * Hapus produk
     */
    public function delete(Request $request)
    {
        $id      = (int) $request->query('hapus');
        $product = Product::findOrFail($id);

        if ($product->gambar) {
            @unlink(public_path('img/products/' . $product->gambar));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
