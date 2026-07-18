<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Farmer;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PetaniController extends Controller
{
    /**
     * Ambil profil farmer milik user petani yang sedang login.
     * Setiap method di sini menggunakan ini supaya query produk selalu
     * dibatasi ke farmer_id miliknya sendiri.
     */
    private function currentFarmer(): Farmer
    {
        $farmer = auth()->user()->farmer;

        abort_if(! $farmer, 403, 'Akun Anda belum terhubung ke profil petani manapun. Hubungi Admin.');

        return $farmer;
    }

    /**
     * Dashboard: ringkasan produk milik petani.
     */
    public function dashboard()
    {
        $farmer = $this->currentFarmer();

        $products = Product::where('farmer_id', $farmer->id)->get();

        $stats = [
            'total_produk'    => $products->count(),
            'total_pending'   => $products->where('status', 'pending')->count(),
            'total_approved'  => $products->where('status', 'approved')->count(),
            'total_rejected'  => $products->where('status', 'rejected')->count(),
            'total_terjual'   => Product::where('farmer_id', $farmer->id)
                                    ->join('order_items', 'order_items.product_id', '=', 'product.id_product')
                                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                    ->where('orders.status', 'completed')
                                    ->sum('order_items.quantity'),
        ];

        $produkTerbaru = $products->sortByDesc('id_product')->take(5);

        // --- Chart: Tren kebutuhan stok (permintaan 14 hari terakhir) ---
        // Menampilkan jumlah unit produk milik petani ini yang terjual per
        // hari, supaya petani bisa memperkirakan seberapa cepat stok
        // habis dan kapan perlu menyiapkan stok baru (SRS 3.4.3).
        $productIds = $products->pluck('id_product');

        $stockDemandTrend = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $stockDemandTrend[] = [
                'label'    => $date->translatedFormat('d M'),
                'terjual'  => (int) OrderItem::whereIn('product_id', $productIds)
                    ->whereHas('order', fn ($q) => $q->where('status', 'completed')->whereDate('created_at', $date))
                    ->sum('quantity'),
            ];
        }

        // --- Sisa stok saat ini per produk, urut dari yang paling menipis ---
        $stokPerProduk = $products->sortBy('stok')->take(8)->map(fn ($p) => [
            'nama' => $p->nama_produk,
            'stok' => (int) $p->stok,
        ])->values();

        $charts = [
            'stock_demand_trend' => $stockDemandTrend,
            'stok_per_produk'    => $stokPerProduk,
        ];

        return view('petani.dashboard', compact('farmer', 'stats', 'produkTerbaru', 'charts'));
    }

    /**
     * Lihat profil farmer milik user login.
     */
    public function profil()
    {
        $farmer = $this->currentFarmer();

        return view('petani.profil', compact('farmer'));
    }

    /**
     * Update profil farmer milik user login.
     */
    public function updateProfil(Request $request)
    {
        $farmer = $this->currentFarmer();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'contact'  => ['nullable', 'string', 'max:50'],
            'bio'      => ['nullable', 'string'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($farmer->avatar) {
                @unlink(public_path('farmers/' . $farmer->avatar));
            }
            $ext            = $request->file('avatar')->getClientOriginalExtension();
            $data['avatar'] = uniqid('farmer_', true) . '.' . strtolower($ext);
            $request->file('avatar')->move(public_path('farmers'), $data['avatar']);
        }

        $farmer->update($data);

        // Nama akun (users.nama) ikut disinkronkan dengan nama farmer supaya
        // konsisten di seluruh tampilan (dashboard, navbar, dll).
        auth()->user()->update(['nama' => $data['name']]);

        return redirect()->route('petani.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Daftar produk milik petani yang sedang login.
     */
    public function produkIndex()
    {
        $farmer     = $this->currentFarmer();
        $products   = Product::with('category')->where('farmer_id', $farmer->id)->orderByDesc('id_product')->get();
        $categories = Category::orderBy('name')->get();

        return view('petani.produk.index', compact('products', 'categories'));
    }

    /**
     * Simpan produk baru atau update produk milik sendiri.
     * Status selalu di-reset/di-set 'pending' setiap kali produk
     * baru dibuat atau diedit oleh petani (FR-19, selaras FR-23).
     */
    public function produkSave(Request $request)
    {
        $farmer = $this->currentFarmer();
        $id     = $request->input('id');

        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:100'],
            'harga'       => ['required', 'integer', 'min:1'],
            'stok'        => ['required', 'integer', 'min:0'],
            'deskripsi'   => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'gambar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['farmer_id'] = $farmer->id;
        $data['type']      = 'market';
        $data['status']    = 'pending';

        $existing = null;
        if ($id) {
            $existing = Product::where('id_product', $id)->first();

            // Cegah petani mengedit/mengklaim produk milik petani lain
            // dengan menyisipkan id produk secara manual. Aturan
            // kepemilikannya sendiri ada di ProductPolicy (dipakai juga
            // oleh produkDelete()), bukan ditulis ulang di sini.
            if (! $existing || $request->user()->cannot('update', $existing)) {
                throw ValidationException::withMessages([
                    'id' => 'Produk tidak ditemukan atau bukan milik Anda.',
                ]);
            }
        }

        if ($request->hasFile('gambar')) {
            $ext            = $request->file('gambar')->getClientOriginalExtension();
            $data['gambar'] = uniqid('prod_', true) . '.' . strtolower($ext);
            $request->file('gambar')->move(public_path('products'), $data['gambar']);

            if ($existing?->gambar) {
                @unlink(public_path('products/' . $existing->gambar));
            }
        } else {
            $data['gambar'] = $existing->gambar ?? null;
        }

        if ($existing) {
            $existing->update($data);
            $msg = 'Produk berhasil diperbarui dan menunggu review Admin kembali.';
        } else {
            Product::create($data);
            $msg = 'Produk baru berhasil didaftarkan dan menunggu review Admin.';
        }

        return redirect()->route('petani.produk.index')->with('success', $msg);
    }

    /**
     * Hapus produk milik sendiri.
     */
    public function produkDelete(Request $request)
    {
        $this->currentFarmer();
        $id      = (int) $request->query('hapus');
        $product = Product::where('id_product', $id)->firstOrFail();

        $this->authorize('delete', $product);

        if ($product->gambar) {
            @unlink(public_path('products/' . $product->gambar));
        }

        $product->delete();

        return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
