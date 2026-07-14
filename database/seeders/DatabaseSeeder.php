<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users (admin & kasir)
        DB::table('users')->insertOrIgnore([
            [
                'nama'     => 'Administrator',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('kafetani2025'),
                'role'     => 'admin',
            ],
            [
                'nama'     => 'Kasir Utama',
                'email'    => 'kasir@kafetani.com',
                'password' => Hash::make('kasir123'),
                'role'     => 'kasir',
            ],
        ]);

        // Pelanggan (akun contoh untuk demo alur registrasi, pemesanan
        // online, dan riwayat order di sisi pelanggan).
        DB::table('users')->insertOrIgnore([
            [
                'nama'     => 'Andi Saputra',
                'email'    => 'andi@pelanggan.com',
                'password' => Hash::make('pelanggan123'),
                'role'     => 'user',
            ],
            [
                'nama'     => 'Siti Rahma',
                'email'    => 'siti@pelanggan.com',
                'password' => Hash::make('pelanggan123'),
                'role'     => 'user',
            ],
            [
                'nama'     => 'Dedi Kurniawan',
                'email'    => 'dedi@pelanggan.com',
                'password' => Hash::make('pelanggan123'),
                'role'     => 'user',
            ],
        ]);

        // Categories
        DB::table('categories')->insertOrIgnore([
            ['name' => 'Kopi',       'slug' => 'kopi'],
            ['name' => 'Non-Kopi',   'slug' => 'non-kopi'],
            ['name' => 'Pastry',     'slug' => 'pastry'],
            ['name' => 'Camilan',    'slug' => 'camilan'],
            ['name' => 'Bahan Baku', 'slug' => 'bahan-baku'],
        ]);

        // Farmers
        $farmerIds = [];
        foreach ([
            [
                'name'     => 'Pak Budi',
                'location' => 'Gayo, Aceh',
                'contact'  => '0812-3456-7890',
                'bio'      => 'Petani kopi Arabica generasi ketiga di dataran tinggi Gayo. Sudah 20 tahun mengelola kebun seluas 3 hektar dengan metode organik tanpa pestisida kimia.',
                'avatar'   => 'pak_budi.webp',
            ],
            [
                'name'     => 'Bu Sari',
                'location' => 'Temanggung, Jateng',
                'contact'  => '0856-9876-5432',
                'bio'      => 'Pelopor gula aren tradisional di Temanggung. Bu Sari mengolah nira aren secara manual menggunakan tungku kayu bakar warisan leluhur untuk menjaga cita rasa autentik.',
                'avatar'   => 'bu_sari.webp',
            ],
            [
                'name'     => 'Pak Yusuf',
                'location' => 'Pangalengan, Jabar',
                'contact'  => '0821-5544-3322',
                'bio'      => 'Petani muda yang fokus pada sayuran hidroponik dan bahan baku pastry segar. Lulusan pertanian IPB yang memilih kembali ke desa untuk mengembangkan pertanian modern ramah lingkungan.',
                'avatar'   => 'pak_yusuf.webp',
            ],
        ] as $farmer) {
            $existing = DB::table('farmers')->where('name', $farmer['name'])->first();
            $farmerIds[$farmer['name']] = $existing?->id
                ?? DB::table('farmers')->insertGetId($farmer);
        }

        // Petani (akun contoh untuk demo alur registrasi & approval, FR-19/FR-23).
        // Dihubungkan ke farmer 'Pak Budi' yang sudah ada di atas.
        $petaniUser = DB::table('users')->where('email', 'petani@kafetani.com')->first();

        $petaniUserId = $petaniUser->id ?? DB::table('users')->insertGetId([
            'nama'     => 'Pak Budi',
            'email'    => 'petani@kafetani.com',
            'password' => Hash::make('petani123'),
            'role'     => 'petani',
        ]);

        DB::table('farmers')
            ->where('id', $farmerIds['Pak Budi'])
            ->whereNull('user_id')
            ->update(['user_id' => $petaniUserId]);

        // Products
        DB::table('product')->insertOrIgnore([
            // Menu Kafe
            ['nama_produk' => 'Americano Arabica',    'harga' => 28000, 'stok' => 50, 'deskripsi' => 'Espresso dengan air panas, rasa tegas',        'farmer_id' => null, 'gambar' => 'americano_arabica.webp',  'category_id' => 1, 'type' => 'cafe'],
            ['nama_produk' => 'Kopi Susu Gula Aren',  'harga' => 32000, 'stok' => 50, 'deskripsi' => 'Kopi lokal dengan gula aren asli',              'farmer_id' => null, 'gambar' => 'kopi_susu_gula_aren.webp', 'category_id' => 1, 'type' => 'cafe'],
            ['nama_produk' => 'Cappuccino',            'harga' => 30000, 'stok' => 50, 'deskripsi' => 'Espresso, susu steam, dan foam lembut',         'farmer_id' => null, 'gambar' => 'cappuccino.webp',          'category_id' => 1, 'type' => 'cafe'],
            ['nama_produk' => 'Croissant Butter',      'harga' => 22000, 'stok' => 30, 'deskripsi' => 'Renyah di luar, lembut di dalam',               'farmer_id' => null, 'gambar' => 'croissant_butter.webp',    'category_id' => 3, 'type' => 'cafe'],
            ['nama_produk' => 'Chocolate Cake',        'harga' => 25000, 'stok' => 20, 'deskripsi' => 'Kue coklat lembab buatan sendiri',              'farmer_id' => null, 'gambar' => 'chocolate_cake.webp',      'category_id' => 4, 'type' => 'cafe'],
            // Marketplace
            ['nama_produk' => 'Biji Kopi Arabica Gayo', 'harga' => 85000, 'stok' => 100, 'deskripsi' => 'Single origin, medium roast',            'farmer_id' => $farmerIds['Pak Budi'],  'gambar' => 'biji_kopi_arabica_gayo.webp', 'category_id' => 5, 'type' => 'market'],
            ['nama_produk' => 'Gula Aren Organik',       'harga' => 45000, 'stok' => 100, 'deskripsi' => 'Proses alami tanpa pemutih',             'farmer_id' => $farmerIds['Bu Sari'],   'gambar' => 'gula_aren.webp',              'category_id' => 5, 'type' => 'market'],
            ['nama_produk' => 'Susu Sapi Segar',         'harga' => 28000, 'stok' => 100, 'deskripsi' => 'Segar dipanen pagi hari',               'farmer_id' => $farmerIds['Pak Yusuf'], 'gambar' => 'susu_sapi_segar.webp',        'category_id' => 5, 'type' => 'market'],
        ]);

        // Produk contoh berstatus 'pending', didaftarkan oleh akun petani di
        // atas, untuk mendemokan alur approval (FR-19 -> FR-23) begitu
        // seeder selesai jalan.
        DB::table('product')->insertOrIgnore([
            ['nama_produk' => 'Kopi Honey Process Gayo', 'harga' => 95000,  'stok' => 40, 'deskripsi' => 'Proses honey, rasa manis alami dari lendir buah kopi',  'farmer_id' => $farmerIds['Pak Budi'], 'gambar' => null, 'category_id' => 5, 'type' => 'market', 'status' => 'pending'],
            ['nama_produk' => 'Kopi Wine Process Gayo',  'harga' => 110000, 'stok' => 25, 'deskripsi' => 'Fermentasi panjang ala natural wine, aroma buah kuat', 'farmer_id' => $farmerIds['Pak Budi'], 'gambar' => null, 'category_id' => 5, 'type' => 'market', 'status' => 'pending'],
        ]);
    }
}