<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder khusus untuk mengisi data pesanan (orders + order_items) dengan
 * sebaran yang "enak dilihat" di chart dashboard admin:
 *
 *  - Tren Pendapatan (7 hari terakhir)  -> butuh order 'completed' tersebar
 *    di beberapa hari terakhir dengan total yang naik-turun, bukan flat.
 *  - Status Pesanan (doughnut)          -> butuh variasi status.
 *  - 5 Produk Terlaris (bar)            -> butuh order_items dengan quantity
 *    yang berbeda-beda per produk.
 *
 * PERHATIAN: seeder ini akan MENGOSONGKAN tabel `order_items` dan `orders`
 * terlebih dahulu sebelum mengisi ulang, supaya bisa dijalankan berkali-kali
 * (idempotent) tanpa data ganda menumpuk. Jangan jalankan di database yang
 * sudah berisi pesanan asli dari pelanggan.
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // --- Bersihkan data lama supaya seeder aman dijalankan berulang ---
        DB::table('order_items')->delete();
        DB::table('orders')->delete();

        // --- Ambil user yang akan jadi "pemilik" order ---
        $customerIds = DB::table('users')->where('role', 'user')->pluck('id')->all();
        $kasirId     = DB::table('users')->where('role', 'kasir')->value('id');
        $adminId     = DB::table('users')->where('role', 'admin')->value('id');

        // Fallback kalau seeder user belum jalan sama sekali
        if (empty($customerIds)) {
            $customerIds = [$kasirId ?? $adminId];
        }
        $posUserId = $kasirId ?? $adminId ?? $customerIds[0];

        // --- Ambil produk yang tersedia (approved) untuk diisi ke order_items ---
        $products = DB::table('product')
            ->where('status', 'approved')
            ->select('id_product', 'harga', 'type')
            ->get();

        if ($products->isEmpty()) {
            $this->command?->warn('Tabel product kosong — jalankan seeder produk dulu sebelum OrderSeeder.');
            return;
        }

        $customerNames = [
            'Andi Saputra', 'Siti Rahma', 'Dedi Kurniawan', 'Rina Wulandari',
            'Bayu Pratama', 'Nur Aini', 'Fajar Ramadhan', 'Lina Marlina',
            'Agus Setiawan', 'Putri Ayu', 'Rizky Ramadan', 'Wahyu Hidayat',
        ];

        // Bobot status: mayoritas selesai, sisanya tersebar (total = 100)
        $statusWeights = [
            'completed'       => 55,
            'processing'      => 12,
            'ready'           => 10,
            'pending'         => 8,
            'pending_payment' => 8,
            'cancelled'       => 7,
        ];

        // --- Seed order untuk 21 hari terakhir (termasuk hari ini) ---
        // Range lebih panjang dari 7 hari supaya data tetap konsisten
        // walau seeder dijalankan beberapa hari setelah tanggal awal.
        $totalDays = 21;

        for ($daysAgo = $totalDays - 1; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::today()->subDays($daysAgo);

            // Tren naik menjelang hari ini + efek weekend lebih ramai,
            // supaya garis "Tren Pendapatan" tidak flat/datar.
            $progress   = 1 - ($daysAgo / $totalDays);           // 0 -> 1
            $baseOrders = 3 + (int) round($progress * 6);         // 3 -> ~9
            $weekendBoost = $date->isWeekend() ? 3 : 0;
            $ordersToday  = max(1, $baseOrders + $weekendBoost + random_int(-1, 2));

            for ($i = 0; $i < $ordersToday; $i++) {
                $this->createOrder($date, $customerIds, $posUserId, $customerNames, $statusWeights, $products);
            }
        }

        $this->command?->info("OrderSeeder selesai: {$totalDays} hari data pesanan berhasil dibuat.");
    }

    private function createOrder(
        Carbon $date,
        array $customerIds,
        int $posUserId,
        array $customerNames,
        array $statusWeights,
        \Illuminate\Support\Collection $products
    ): void {
        // Jam acak dalam jam operasional kafe (07:00 - 21:00)
        $createdAt = $date->copy()->setTime(random_int(7, 20), random_int(0, 59), random_int(0, 59));

        $source = random_int(1, 100) <= 60 ? 'offline' : 'online'; // POS lebih sering dari online
        $status = $this->weightedRandom($statusWeights);

        // Order lama (>2 hari) tidak masuk akal masih 'pending'/'pending_payment'
        if ($createdAt->lt(Carbon::now()->subDays(2)) && in_array($status, ['pending', 'pending_payment'])) {
            $status = 'completed';
        }

        $userId = $source === 'offline' ? $posUserId : $customerIds[array_rand($customerIds)];

        // Pilih 1-4 produk acak untuk order ini
        $itemCount   = random_int(1, 4);
        $chosen      = $products->random(min($itemCount, $products->count()));
        $chosen      = $chosen instanceof \Illuminate\Support\Collection ? $chosen : collect([$chosen]);

        $items = [];
        $total = 0;
        $types = [];

        foreach ($chosen as $product) {
            $qty      = random_int(1, 3);
            $price    = (int) $product->harga;
            $subtotal = $qty * $price;

            $items[] = [
                'product_id' => $product->id_product,
                'quantity'   => $qty,
                'price'      => $price,
                'subtotal'   => $subtotal,
            ];

            $total    += $subtotal;
            $types[]   = $product->type;
        }

        $uniqueTypes = array_unique($types);
        $orderType   = count($uniqueTypes) > 1 ? 'mixed' : ($uniqueTypes[0] ?? 'cafe');

        $orderId = DB::table('orders')->insertGetId([
            'midtrans_order_id' => $source === 'online' ? 'ORD-' . $createdAt->format('YmdHis') . '-' . random_int(100, 999) : null,
            'user_id'           => $userId,
            'total'             => $total,
            'type'              => $orderType,
            'source'            => $source,
            'customer_name'     => $customerNames[array_rand($customerNames)],
            'status'            => $status,
            'payment_status'    => in_array($status, ['completed', 'processing', 'ready']) ? 'paid' : 'unpaid',
            'paid_at'           => in_array($status, ['completed', 'processing', 'ready']) ? $createdAt : null,
            'created_at'        => $createdAt,
        ]);

        foreach ($items as $item) {
            DB::table('order_items')->insert([
                'order_id'   => $orderId,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);
        }
    }

    /**
     * Pilih satu key secara acak berdasarkan bobot (persentase).
     */
    private function weightedRandom(array $weights): string
    {
        $rand = random_int(1, array_sum($weights));
        $cumulative = 0;

        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $key;
            }
        }

        return array_key_first($weights);
    }
}
