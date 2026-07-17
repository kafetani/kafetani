<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan alur verifikasi kemitraan petani (SRS Bab 3.4.2 — Use Case
 * "Memverifikasi Kemitraan Petani").
 *
 * Sebelum ini, akun petani yang mendaftar sendiri lewat RegisterPetaniController
 * langsung aktif penuh tanpa validasi admin sama sekali — tidak ada bedanya
 * dengan use case yang diklaim ada di SRS. Kolom ini meniru pola
 * product.status (lihat 2026_07_10_000003_add_status_to_product_table):
 *
 * - 'pending'  → akun petani baru mendaftar sendiri, menunggu verifikasi admin.
 * - 'approved' → sudah diverifikasi, resmi masuk jaringan (tampil di direktori
 *                petani publik/marketplace).
 * - 'rejected' → ditolak admin, tidak tampil di halaman publik.
 *
 * Petani yang diinput langsung oleh admin (Admin\FarmerController@store)
 * otomatis 'approved' — admin yang menambahkan berarti sudah memvalidasi
 * sendiri, sama seperti pola approval produk.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('user_id');
        });

        // Backfill: seluruh data petani yang sudah ada sebelum migration ini
        // dianggap sudah terverifikasi, supaya tidak tiba-tiba hilang dari
        // Marketplace/direktori petani yang sudah berjalan.
        DB::table('farmers')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
