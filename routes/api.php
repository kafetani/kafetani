<?php

// Catatan: endpoint pesanan (/api/orders) sekarang didaftarkan di routes/web.php,
// bukan di sini. Alasannya: aplikasi ini pakai auth berbasis sesi/cookie (guard
// 'web'), bukan Laravel Sanctum (yang tidak terpasang di project ini). Route di
// file ini ('routes/api.php') masuk grup middleware 'api' yang stateless —
// tidak ada sesi maupun verifikasi CSRF — sehingga guard 'auth:sanctum' tanpa
// Sanctum terpasang akan selalu melempar error "Auth guard [sanctum] is not
// defined." Jika suatu saat perlu endpoint API murni yang stateless (dipanggil
// dari luar domain, mobile app, dll), pasang Laravel Sanctum terlebih dahulu
// baru daftarkan route-nya di sini.
