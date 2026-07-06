<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Konfigurasi Global Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds', true);

        // Jika di mode sandbox / local development, abaikan verifikasi SSL
        if (!config('midtrans.is_production')) {
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                // Wajib diisi walau kosong: ApiRequestor.php milik midtrans-php mengakses
                // key ini langsung (bukan lewat isset()), jadi kalau tidak ada, PHP akan
                // melempar "Undefined array key 10023" (10023 = nilai int CURLOPT_HTTPHEADER).
                CURLOPT_HTTPHEADER => [],
            ];
        }
    }
}
