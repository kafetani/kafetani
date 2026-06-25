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

        // Categories
        DB::table('categories')->insertOrIgnore([
            ['name' => 'Kopi',       'slug' => 'kopi'],
            ['name' => 'Non-Kopi',   'slug' => 'non-kopi'],
            ['name' => 'Bakeri',     'slug' => 'bakeri'],
            ['name' => 'Camilan',    'slug' => 'camilan'],
            ['name' => 'Bahan Baku', 'slug' => 'bahan-baku'],
        ]);

        // Farmers
        DB::table('farmers')->insertOrIgnore([
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
                'bio'      => 'Petani muda yang fokus pada sayuran hidroponik dan bahan baku bakeri segar. Lulusan pertanian IPB yang memilih kembali ke desa untuk mengembangkan pertanian modern ramah lingkungan.',
                'avatar'   => 'pak_yusuf.webp',
            ],
        ]);

        // Products
        DB::table('product')->insertOrIgnore([
            // Menu Kafe
            ['nama_produk' => 'Americano Arabica',    'harga' => 28000, 'stok' => 50, 'deskripsi' => 'Espresso dengan air panas, rasa tegas',        'petani' => null, 'gambar' => 'americano_arabica.webp',  'category_id' => 1, 'type' => 'cafe'],
            ['nama_produk' => 'Kopi Susu Gula Aren',  'harga' => 32000, 'stok' => 50, 'deskripsi' => 'Kopi lokal dengan gula aren asli',              'petani' => null, 'gambar' => 'kopi_susu_gula_aren.webp', 'category_id' => 1, 'type' => 'cafe'],
            ['nama_produk' => 'Cappuccino',            'harga' => 30000, 'stok' => 50, 'deskripsi' => 'Espresso, susu steam, dan foam lembut',         'petani' => null, 'gambar' => 'cappuccino.webp',          'category_id' => 1, 'type' => 'cafe'],
            ['nama_produk' => 'Croissant Butter',      'harga' => 22000, 'stok' => 30, 'deskripsi' => 'Renyah di luar, lembut di dalam',               'petani' => null, 'gambar' => 'croissant_butter.webp',    'category_id' => 3, 'type' => 'cafe'],
            ['nama_produk' => 'Chocolate Cake',        'harga' => 25000, 'stok' => 20, 'deskripsi' => 'Kue coklat lembab buatan sendiri',              'petani' => null, 'gambar' => 'chocolate_cake.webp',      'category_id' => 4, 'type' => 'cafe'],
            // Marketplace
            ['nama_produk' => 'Biji Kopi Arabica Gayo', 'harga' => 85000, 'stok' => 100, 'deskripsi' => 'Single origin, medium roast',            'petani' => 'Pak Budi - Gayo, Aceh',          'gambar' => 'biji_kopi_arabica_gayo.webp', 'category_id' => 5, 'type' => 'market'],
            ['nama_produk' => 'Gula Aren Organik',       'harga' => 45000, 'stok' => 100, 'deskripsi' => 'Proses alami tanpa pemutih',             'petani' => 'Bu Sari - Temanggung, Jateng',   'gambar' => 'gula_aren.webp',              'category_id' => 5, 'type' => 'market'],
            ['nama_produk' => 'Susu Sapi Segar',         'harga' => 28000, 'stok' => 100, 'deskripsi' => 'Segar dipanen pagi hari',               'petani' => 'Pak Yusuf - Pangalengan, Jabar', 'gambar' => 'susu_sapi_segar.webp',        'category_id' => 5, 'type' => 'market'],
        ]);
    }
}
