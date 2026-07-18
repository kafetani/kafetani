<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Midtrans\MT_Tests;
use Tests\TestCase;

/**
 * Memverifikasi klaim Bab 5.2 poin 3: "callback Midtrans palsu ditolak".
 *
 * MidtransController TIDAK memvalidasi signature_key secara manual (lihat
 * app/Http/Controllers/Api/MidtransController.php). Ia memakai
 * Midtrans\Notification dari SDK resmi, yang caranya:
 *  1. Ambil transaction_id dari body webhook yang masuk (satu-satunya
 *     bagian payload yang dipakai sebagai kunci pencarian).
 *  2. Query ULANG status transaksi tersebut ke API Midtrans memakai
 *     server_key aplikasi (Transaction::status()).
 *  3. Field lain (transaction_status, fraud_status, payment_type, dst)
 *     yang benar-benar dipakai controller berasal dari RESPON API itu,
 *     BUKAN dari body webhook mentah.
 *
 * Jadi "penolakan callback palsu" dibuktikan bukan lewat hash yang salah,
 * tapi dengan menunjukkan: field yang diklaim penyerang di body webhook
 * (mis. "transaction_status": "settlement") diabaikan sepenuhnya kalau
 * tidak cocok dengan apa yang benar-benar dikembalikan API Midtrans.
 *
 * Test ini memakai stub HTTP resmi milik SDK (Midtrans\MT_Tests), bukan
 * mock terpisah, supaya jalur kode yang dites persis sama dengan yang
 * jalan di production (cuma transport cURL-nya yang diganti).
 */
class MidtransNotificationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // MT_Tests.php ships under midtrans/midtrans-php's `autoload-dev`,
        // yang Composer TIDAK memuat untuk paket dependency (hanya untuk
        // root package). Kita require manual di sini (bukan di top-level
        // file) karena base_path() baru tersedia setelah aplikasi Laravel
        // di-bootstrap oleh parent::setUp(). Ini file stub resmi bawaan
        // SDK, bukan tiruan/mock buatan sendiri — jalur kode yang dites
        // (termasuk ApiRequestor::remoteCall) tetap identik dengan
        // production, cuma transport cURL-nya yang di-stub.
        if (! class_exists(MT_Tests::class)) {
            require_once base_path('vendor/midtrans/midtrans-php/tests/MT_Tests.php');
        }

        MT_Tests::reset();
        MT_Tests::$stubHttp = true;
    }

    protected function tearDown(): void
    {
        MT_Tests::reset();
        parent::tearDown();
    }

    private function buatOrderPending(string $midtransOrderId): Order
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi']);

        $product = Product::create([
            'nama_produk' => 'Kopi Susu Gula Aren',
            'harga'       => 22000,
            'stok'        => 50,
            'category_id' => $category->id,
            'type'        => 'cafe',
            'status'      => 'approved',
        ]);

        $order = Order::create([
            'midtrans_order_id' => $midtransOrderId,
            'total'             => 22000,
            'type'              => 'cafe',
            'source'            => 'online',
            'customer_name'     => 'Tamu Uji',
            'status'            => 'pending_payment',
            'payment_status'    => 'unpaid',
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id_product,
            'quantity'   => 1,
            'price'      => 22000,
            'subtotal'   => 22000,
        ]);

        return $order;
    }

    /**
     * Simulasikan respons API Midtrans yang SEBENARNYA dikembalikan saat
     * sistem melakukan Transaction::status() — ini yang mewakili "kebenaran"
     * di sisi Midtrans, terlepas dari apa pun yang diklaim penyerang di body
     * webhook.
     */
    private function stubRespon(string $orderId, string $transactionStatus, ?string $fraudStatus = null): void
    {
        MT_Tests::$stubHttpResponse = json_encode([
            'order_id'           => $orderId,
            'transaction_id'     => 'txn-' . $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status'       => $fraudStatus,
            'payment_type'       => 'bank_transfer',
            'status_code'        => '200',
        ]);
    }

    public function test_callback_palsu_yang_klaim_lunas_ditolak_jika_status_asli_masih_pending(): void
    {
        $order = $this->buatOrderPending('ORDER-PALSU-1');

        // Kebenaran di sisi Midtrans: transaksi ini SEBENARNYA masih pending.
        $this->stubRespon('ORDER-PALSU-1', 'pending');

        // Penyerang mengirim body webhook palsu yang MENGKLAIM sudah settlement,
        // berharap sistem langsung menandai order sebagai lunas.
        $payloadPalsu = json_encode([
            'order_id'           => 'ORDER-PALSU-1',
            'transaction_id'     => 'txn-ORDER-PALSU-1',
            'transaction_status' => 'settlement', // <- klaim palsu, harus diabaikan
            'payment_type'       => 'bank_transfer',
            'fraud_status'       => 'accept',
        ]);

        $response = $this->callNotificationWithRawBody($payloadPalsu);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        // Order HARUS mengikuti status asli dari API (pending), bukan klaim
        // penyerang di body webhook (settlement).
        $order->refresh();
        $this->assertSame('unpaid', $order->payment_status);
        $this->assertSame('pending_payment', $order->status);
        $this->assertNotSame('paid', $order->payment_status);
    }

    public function test_status_settlement_asli_dari_midtrans_tetap_diproses_normal(): void
    {
        $order = $this->buatOrderPending('ORDER-ASLI-1');

        // Kali ini API Midtrans yang sebenarnya memang mengonfirmasi settlement.
        $this->stubRespon('ORDER-ASLI-1', 'settlement');

        $payload = json_encode([
            'order_id'           => 'ORDER-ASLI-1',
            'transaction_id'     => 'txn-ORDER-ASLI-1',
            'transaction_status' => 'settlement',
            'payment_type'       => 'bank_transfer',
        ]);

        $response = $this->callNotificationWithRawBody($payload);

        $response->assertOk();
        $order->refresh();
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('pending', $order->status);
        $this->assertNotNull($order->paid_at);
    }

    public function test_order_id_yang_tidak_dikenal_ditolak_dengan_404(): void
    {
        $this->stubRespon('ORDER-TIDAK-ADA', 'settlement');

        $payload = json_encode([
            'order_id'           => 'ORDER-TIDAK-ADA',
            'transaction_id'     => 'txn-ORDER-TIDAK-ADA',
            'transaction_status' => 'settlement',
            'payment_type'       => 'bank_transfer',
        ]);

        $response = $this->callNotificationWithRawBody($payload);

        $response->assertStatus(404);
    }

    /**
     * Kirim body JSON mentah persis seperti yang dikirim Midtrans (server
     * asli mereka mem-POST JSON, bukan form-encoded), lewat test client biasa.
     */
    private function callNotificationWithRawBody(string $rawJsonBody)
    {
        return $this->call(
            'POST',
            '/midtrans/notification',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $rawJsonBody
        );
    }
}
