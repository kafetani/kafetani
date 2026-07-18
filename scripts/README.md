# Load test & race-condition test — `POST /api/kasir/orders`

Script ini membuktikan dua klaim laporan yang sebelumnya tidak punya
bukti otomatis:

1. **"Load test 500 request"** (Bab 5.3) — 500 request sungguh dikirim
   konkuren (bukan berurutan) ke server yang benar-benar jalan.
2. **"`lockForUpdate()` mencegah race condition stok"** (Bab 5.3) — semua
   request diarahkan ke satu produk berstok terbatas; kalau locking-nya
   benar, order yang berhasil tidak boleh melebihi stok awal.

## ⚠️ Penting: pilih server yang benar

**`php artisan serve` TIDAK cocok untuk test ini.** Server bawaan PHP itu
**single-threaded** — dia memproses satu request pada satu waktu, jadi
walaupun 500 request dikirim konkuren dari sisi client, di sisi server
tidak akan pernah ada dua request yang benar-benar jalan bersamaan. Hasil
"tidak oversell" dari server seperti itu tidak membuktikan apa-apa soal
`lockForUpdate()`, karena race condition-nya memang tidak pernah tercipta.

Supaya benar-benar menguji concurrency, jalankan lewat **php-fpm (banyak
worker) + nginx**, seperti setup production yang sebenarnya. Ini yang
dipakai untuk hasil di bawah.

## Setup (sekali saja)

```bash
# 1. Pool php-fpm dengan beberapa worker statis
cat > /tmp/fpm-conf/php-fpm.conf << 'EOF'
[global]
pid = /tmp/fpm-conf/php-fpm.pid
error_log = /tmp/fpm-conf/php-fpm.log
daemonize = no

[www]
listen = 127.0.0.1:9000
pm = static
pm.max_children = 16
user = nobody
group = nogroup
catch_workers_output = yes
EOF

# 2. nginx sebagai reverse proxy ke php-fpm
cat > /tmp/nginx-kafetani.conf << 'EOF'
worker_processes 4;
pid /tmp/fpm-conf/nginx.pid;
daemon off;
events { worker_connections 1024; }
http {
    server {
        listen 127.0.0.1:8080;
        root /path/ke/kafetani-main/public;
        index index.php;
        location / { try_files $uri $uri/ /index.php?$query_string; }
        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include /etc/nginx/fastcgi_params;
        }
    }
}
EOF

chmod -R 777 storage bootstrap/cache   # supaya worker 'nobody' bisa nulis log/cache

php-fpm -y /tmp/fpm-conf/php-fpm.conf &
nginx -c /tmp/nginx-kafetani.conf &
```

## Siapkan data uji

```bash
php artisan tinker --execute="
use App\Models\User; use App\Models\Product; use App\Models\Category; use App\Models\ApiToken;

\$user     = User::create(['nama'=>'Kasir Load Test','email'=>'kasir.loadtest@kafetani.test','password'=>bcrypt('password'),'role'=>'kasir']);
\$category = Category::create(['name'=>'Minuman','slug'=>'minuman']);
\$product  = Product::create(['nama_produk'=>'Es Kopi Susu (Load Test)','harga'=>18000,'stok'=>50,'category_id'=>\$category->id,'type'=>'cafe','status'=>'approved']);
\$token    = ApiToken::issueFor(\$user, 'load-test-script');

echo 'PRODUCT_ID='.\$product->id_product.PHP_EOL;
echo 'TOKEN='.\$token.PHP_EOL;
"
```

## Jalankan

```bash
php scripts/load-test-kasir-order.php \
    --base-url=http://127.0.0.1:8080 \
    --token=<TOKEN_DARI_LANGKAH_DI_ATAS> \
    --product-id=<PRODUCT_ID> \
    --initial-stock=50 \
    --requests=500 \
    --concurrency=50
```

## Hasil aktual (dijalankan 18 Juli 2026, 16 worker php-fpm + nginx, MySQL 8.0)

```
=== Hasil ===
Total request terkirim    : 500
Durasi total               : 80.98 detik
Throughput                 : 6.2 request/detik
Berhasil (201)              : 50
Ditolak - stok habis (422): 450
Error lain                  : 0
Latensi p50 / p95 / p99    : 7.759s / 8.669s / 10.895s

=== Verifikasi anti-oversell ===
✅ Order berhasil (50) tidak melebihi stok awal (50) meski 500 request
   dikirim konkuren (concurrency=50).

Stok akhir di database: 0 (tepat, tidak negatif)
Jumlah order di tabel `orders`: 50 (tepat, tidak lebih)
```

**Catatan pembacaan hasil:** throughput yang rendah (6.2 req/s) dan
latensi p50 yang tinggi (~7.8 detik) itu **bukan tanda kegagalan** —
justru sebaliknya. Karena semua 500 request memperebutkan baris produk
yang sama, `lockForUpdate()` sengaja membuat transaksi lain **menunggu**
sampai transaksi yang sedang memegang kunci baris itu selesai (commit).
Waktu tunggu inilah yang membuat latensi naik. Itu adalah trade-off yang
diharapkan: ketimbang membiarkan 500 transaksi jalan paralel dan
oversell, sistem sengaja menyerialisasi akses ke baris kritis itu.

## Berkas terkait

- `scripts/load-test-kasir-order.php` — script load test itu sendiri
  (dependency-free, cuma butuh ekstensi `curl` PHP)
- `tests/Feature/MidtransNotificationSecurityTest.php` — bukti otomatis
  terpisah untuk klaim "callback Midtrans palsu ditolak" (lihat Bab 5.2)
