<p align="center"><img src="public/logo_v3.svg" width="240" alt="Kafetani Logo"></p>

# Kafetani

Aplikasi kafe sekaligus marketplace hasil tani. Pelanggan bisa pesan menu kafe atau beli produk segar langsung dari petani lokal, sementara admin/kasir mengelola pesanan, produk, dan data petani dari satu dashboard.

## Quickstart

**Prasyarat:** PHP 8.3+, Composer, Bun. Database pakai SQLite secara default, jadi tidak wajib install database server terpisah.

```bash
# 1. Install dependency
composer install
bun install

# 2. Siapkan environment
cp .env.example .env        # Windows (PowerShell): copy .env.example .env
php artisan key:generate

# 3. Siapkan database (tabel sessions perlu digenerate manual dulu)
php artisan make:session-table
php artisan migrate --seed  # kalau ditanya bikin file SQLite, pilih "yes"

# 4. Build asset frontend
bun run build

# 5. Jalankan server
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser. `--seed` di atas otomatis mengisi kategori, petani, produk contoh, dan dua akun berikut:

| Role  | Email               | Password     |
|-------|---------------------|--------------|
| Admin | admin@gmail.com     | kafetani2025 |
| Kasir | kasir@kafetani.com  | kasir123     |

**Pakai MySQL?** Ganti baris berikut di `.env` sebelum langkah migrasi:
```env
DB_CONNECTION=mysql
DB_DATABASE=db_kafetani
DB_USERNAME=root
DB_PASSWORD=
```

**Development sehari-hari:** `bun run dev` (bukan `build`) supaya asset auto-reload. Atau jalankan server, queue worker, log viewer, dan Vite sekaligus dengan satu perintah:
```bash
composer run dev
```

## Konfigurasi Production (Domain, Google Sign-In, Midtrans, Email)

Selain langkah Quickstart di atas, deployment production butuh beberapa konfigurasi tambahan di `.env`:

### 1. Domain
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kafetani.store
```
`APP_URL` dipakai oleh helper `asset()`/`asset_v()` untuk generate URL gambar (avatar petani, foto produk, dll). Pastikan ini sudah domain final sebelum deploy, karena beberapa cache framework (`view`, `config`) menyimpan hasil generate URL dan perlu di-clear ulang kalau `APP_URL` berubah:
```bash
php artisan config:clear
php artisan view:clear
```

### 2. Google Sign-In
Buat OAuth Client ID di [Google Cloud Console](https://console.cloud.google.com/apis/credentials) (tipe **Web application**), lalu isi:
```env
GOOGLE_CLIENT_ID=xxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxxxxxxx
GOOGLE_REDIRECT_URI=https://kafetani.store/auth/google/callback
```
`GOOGLE_REDIRECT_URI` di atas **harus** didaftarkan persis sama di Google Cloud Console pada bagian *Authorized redirect URIs*, kalau tidak, login Google akan gagal dengan error `redirect_uri_mismatch`.

### 3. Midtrans
Ambil Server Key & Client Key dari [Midtrans Dashboard](https://dashboard.midtrans.com/) (Settings → Access Keys):
```env
MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxx
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_SNAP_URL=https://app.midtrans.com/snap/snap.js
```
- Saat masih testing, biarkan `MIDTRANS_IS_PRODUCTION=false` dan `MIDTRANS_SNAP_URL` mengarah ke `app.sandbox.midtrans.com`, lalu pakai Server/Client Key dari mode **Sandbox** di dashboard (bukan Production). Server key sandbox dan production tidak bisa saling dicampur.
- Di Midtrans Dashboard → **Settings → Configuration**, isi *Payment Notification URL* dengan:
  ```
  https://kafetani.store/midtrans/notification
  ```
  Endpoint ini menerima webhook status pembayaran (lihat `app/Http/Controllers/Api/MidtransController.php`); tanpa ini, status pesanan tidak akan otomatis berubah jadi `paid` setelah pelanggan membayar.

### 4. Email (Lupa Password)
Fitur lupa password mengirim link reset ke email pengguna lewat `App\Mail\ResetPasswordMail`. Secara default `MAIL_MAILER` di `.env.example` di-set ke `log`, artinya email cuma ditulis ke `storage/logs/laravel.log`, bukan benar-benar terkirim. Untuk production, ganti dengan SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.namaprovider.com
MAIL_PORT=587
MAIL_USERNAME=xxxxxxxx
MAIL_PASSWORD=xxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=halo@kafetani.store
MAIL_FROM_NAME="Kafetani"
```
Provider yang umum dipakai: SMTP dari domain sendiri (kalau `kafetani.store` sudah ada email hosting), atau layanan pihak ketiga seperti Mailgun/SES/Resend. Link reset yang dikirim otomatis mengikuti `APP_URL`, jadi pastikan `APP_URL` sudah domain final sebelum testing fitur ini.

