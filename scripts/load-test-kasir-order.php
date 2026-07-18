#!/usr/bin/env php
<?php
/**
 * scripts/load-test-kasir-order.php
 *
 * Membuktikan dua klaim laporan (Bab 5.2 & 5.3) yang sebelumnya tidak
 * punya bukti otomatis yang bisa dijalankan ulang:
 *
 *   1. "Load test 500 request" — mengirim N request BENAR-BENAR konkuren
 *      (bukan berurutan) ke server yang sungguh jalan, lewat cURL multi
 *      handle, dan mencatat throughput + distribusi status code + latensi.
 *
 *   2. "lockForUpdate() mencegah race condition stok" — semua request itu
 *      diarahkan ke SATU produk dengan stok terbatas. Kalau locking-nya
 *      benar, jumlah order yang berhasil TIDAK BOLEH melebihi stok awal,
 *      dan stok akhir di database tidak boleh negatif. Kalau locking-nya
 *      salah/tidak ada, oversell akan muncul di sini secara nyata.
 *
 * PENTING: jangan jalankan target server dengan `php artisan serve` —
 * itu single-threaded (satu request diproses pada satu waktu), jadi race
 * condition yang mau dibuktikan di sini TIDAK PERNAH benar-benar tercipta
 * di sisi server, walau request dikirim konkuren dari client. Gunakan
 * php-fpm (banyak worker) + nginx supaya concurrency-nya nyata. Lihat
 * scripts/README.md untuk setup lengkapnya.
 *
 * CARA PAKAI:
 *   1. Jalankan server: php artisan serve --host=127.0.0.1 --port=8000
 *   2. Siapkan data uji (lihat scripts/README.md untuk contoh tinker):
 *      - satu user kasir/admin
 *      - satu produk type=cafe dengan stok terbatas (mis. 50)
 *      - satu API token (App\Models\ApiToken::issueFor($user))
 *   3. Jalankan:
 *      php scripts/load-test-kasir-order.php \
 *          --base-url=http://127.0.0.1:8000 \
 *          --token=<PLAIN_TOKEN> \
 *          --product-id=<ID_PRODUK> \
 *          --initial-stock=50 \
 *          --requests=500 \
 *          --concurrency=50
 *
 * Script ini TIDAK menyentuh database secara langsung untuk verifikasi
 * akhir — silakan cek stok akhir produk lewat tinker/DB setelah run,
 * sesuai instruksi yang dicetak script di akhir.
 */

function argOrDefault(array $argv, string $name, $default)
{
    foreach ($argv as $arg) {
        if (str_starts_with($arg, "--{$name}=")) {
            return substr($arg, strlen("--{$name}="));
        }
    }
    return $default;
}

$baseUrl       = rtrim(argOrDefault($argv, 'base-url', 'http://127.0.0.1:8000'), '/');
$token         = argOrDefault($argv, 'token', null);
$productId     = argOrDefault($argv, 'product-id', null);
$initialStock  = (int) argOrDefault($argv, 'initial-stock', 0);
$totalRequests = (int) argOrDefault($argv, 'requests', 500);
$concurrency   = (int) argOrDefault($argv, 'concurrency', 50);

if (! $token || ! $productId) {
    fwrite(STDERR, "Wajib isi --token=... dan --product-id=...\n");
    fwrite(STDERR, "Lihat komentar di atas file ini untuk contoh lengkap.\n");
    exit(1);
}

$url = "{$baseUrl}/api/kasir/orders";

echo "=== Load test: {$url} ===\n";
echo "Total request : {$totalRequests}\n";
echo "Konkurensi    : {$concurrency}\n";
echo "Produk ID     : {$productId} (stok awal diasumsikan: {$initialStock})\n";
echo "Setiap request memesan qty=1 dari produk ini.\n\n";

$payload = json_encode([
    'items'         => [['id' => (int) $productId, 'qty' => 1]],
    'order_type'    => 'dine-in',
    'customer_name' => 'Load Test',
]);

$results = [];       // ['code' => int, 'time' => float, 'success' => bool]
$queue    = array_fill(0, $totalRequests, true);
$mh       = curl_multi_init();
$handles  = [];

function makeHandle(string $url, string $token, string $payload)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
    ]);
    return $ch;
}

$startedAt = microtime(true);
$sent = 0;

// Isi awal batch sesuai concurrency
for ($i = 0; $i < min($concurrency, $totalRequests); $i++) {
    $ch = makeHandle($url, $token, $payload);
    curl_multi_add_handle($mh, $ch);
    $handles[(int) $ch] = ['ch' => $ch, 'start' => microtime(true)];
    $sent++;
}

$active = null;
do {
    curl_multi_exec($mh, $active);
    curl_multi_select($mh, 0.1);

    while ($info = curl_multi_info_read($mh)) {
        $ch = $info['handle'];
        $key = (int) $ch;
        $elapsed = microtime(true) - $handles[$key]['start'];
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $body = curl_multi_getcontent($ch);
        $decoded = json_decode($body, true);

        $results[] = [
            'code'    => $httpCode,
            'time'    => $elapsed,
            'success' => $httpCode === 201 && ! empty($decoded['success']),
        ];

        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
        unset($handles[$key]);

        // Tambah request baru ke antrian selama masih ada sisa
        if ($sent < $totalRequests) {
            $newCh = makeHandle($url, $token, $payload);
            curl_multi_add_handle($mh, $newCh);
            $handles[(int) $newCh] = ['ch' => $newCh, 'start' => microtime(true)];
            $sent++;
        }
    }
} while ($active > 0 || count($handles) > 0);

$totalTime = microtime(true) - $startedAt;

// === Laporan hasil ===
$successCount = count(array_filter($results, fn ($r) => $r['success']));
$rejectedStok = count(array_filter($results, fn ($r) => $r['code'] === 422));
$otherErrors  = count($results) - $successCount - $rejectedStok;
$times        = array_column($results, 'time');
sort($times);
$p50 = $times[(int) (count($times) * 0.50)] ?? 0;
$p95 = $times[(int) (count($times) * 0.95)] ?? 0;
$p99 = $times[(int) (count($times) * 0.99)] ?? 0;

echo "=== Hasil ===\n";
printf("Total request terkirim   : %d\n", count($results));
printf("Durasi total             : %.2f detik\n", $totalTime);
printf("Throughput                : %.1f request/detik\n", count($results) / max($totalTime, 0.001));
printf("Berhasil (201)            : %d\n", $successCount);
printf("Ditolak - stok habis (422): %d\n", $rejectedStok);
printf("Error lain                : %d\n", $otherErrors);
printf("Latensi p50 / p95 / p99   : %.3fs / %.3fs / %.3fs\n", $p50, $p95, $p99);

echo "\n=== Verifikasi anti-oversell ===\n";
if ($initialStock > 0) {
    if ($successCount > $initialStock) {
        printf(
            "❌ OVERSELL TERDETEKSI: %d order berhasil padahal stok awal cuma %d. lockForUpdate() GAGAL mencegah race condition.\n",
            $successCount,
            $initialStock
        );
        exit(1);
    }
    printf(
        "✅ Order berhasil (%d) tidak melebihi stok awal (%d) meski %d request dikirim konkuren (concurrency=%d).\n",
        $successCount,
        $initialStock,
        count($results),
        $concurrency
    );
} else {
    echo "(--initial-stock tidak diisi, lewati pengecekan oversell otomatis)\n";
}

echo "\nLangkah verifikasi manual tambahan (jalankan setelah script ini selesai):\n";
echo "  php artisan tinker --execute=\"echo App\\\\Models\\\\Product::find({$productId})->stok;\"\n";
echo "  -> Nilainya harus sama dengan (stok_awal - jumlah_order_berhasil), TIDAK BOLEH negatif.\n";
