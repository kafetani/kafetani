<?php
/**
 * api/orders.php
 * Endpoint POST untuk menyimpan pesanan dari keranjang belanja.
 *
 * Payload JSON yang diterima dari app.js:
 *   { cart: [{id, name, price, qty, image}], total: number, type: string }
 *
 * Response JSON:
 *   { success: true }  atau  { success: false, message: "..." }
 */

// ── Setup ─────────────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

require_once __DIR__ . '/../config/koneksi.php';

// ── Helper: kirim JSON lalu exit ───────────────────────────────────────────────
function respond(bool $success, string $message = '', int $order_id = 0): void {
    $payload = ['success' => $success];
    if ($message)  $payload['message']  = $message;
    if ($order_id) $payload['order_id'] = $order_id;
    echo json_encode($payload);
    exit;
}

// ── Hanya terima POST ─────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method tidak diizinkan.');
}

// ── Harus sudah login ─────────────────────────────────────────────────────────
if (empty($_SESSION['user_id'])) {
    respond(false, 'Kamu harus login terlebih dahulu untuk memesan.');
}
$user_id = (int) $_SESSION['user_id'];

// ── Baca payload JSON ─────────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || empty($data['cart']) || !is_array($data['cart'])) {
    respond(false, 'Data pesanan tidak valid atau keranjang kosong.');
}

$cart  = $data['cart'];
$total = isset($data['total']) ? (int) $data['total'] : 0;

// ── Tentukan type order (cafe / market / mixed) ────────────────────────────────
// Kita cek setiap produk di database untuk tahu type-nya
$order_type = 'mixed'; // default

// ── Mulai transaksi ────────────────────────────────────────────────────────────
mysqli_begin_transaction($conn);

try {
    // 1. Insert ke tabel orders
    $stmt_order = mysqli_prepare($conn,
        "INSERT INTO orders (user_id, total, type, status) VALUES (?, ?, ?, 'pending')"
    );
    mysqli_stmt_bind_param($stmt_order, 'iis', $user_id, $total, $order_type);
    mysqli_stmt_execute($stmt_order);
    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt_order);

    if (!$order_id) {
        throw new Exception('Gagal membuat pesanan.');
    }

    // 2. Insert setiap item ke tabel order_items
    $stmt_item = mysqli_prepare($conn,
        "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)"
    );

    foreach ($cart as $item) {
        $item_name  = isset($item['name'])  ? mysqli_real_escape_string($conn, $item['name']) : '';
        $item_price = isset($item['price']) ? (int) $item['price'] : 0;
        $item_qty   = isset($item['qty'])   ? (int) $item['qty']   : 1;
        $item_id    = isset($item['id'])    ? $item['id']          : null;

        if ($item_qty <= 0 || $item_price < 0) continue;

        // Cari id_product: coba by id numerik dulu, lalu by nama
        $product_id = null;

        if ($item_id && is_numeric($item_id)) {
            // Dari marketplace — id_product langsung tersedia
            $res = mysqli_query($conn,
                "SELECT id_product FROM product WHERE id_product = " . (int)$item_id . " LIMIT 1"
            );
            if ($row = mysqli_fetch_assoc($res)) {
                $product_id = (int) $row['id_product'];
            }
        }

        if (!$product_id && $item_name) {
            // Dari menu kafe — id berupa nama produk, cari by nama
            $res = mysqli_query($conn,
                "SELECT id_product FROM product WHERE nama_produk = '{$item_name}' LIMIT 1"
            );
            if ($row = mysqli_fetch_assoc($res)) {
                $product_id = (int) $row['id_product'];
            }
        }

        if (!$product_id) {
            // Produk tidak ditemukan di DB — lewati item ini (bukan error fatal)
            continue;
        }

        $subtotal = $item_price * $item_qty;

        mysqli_stmt_bind_param($stmt_item, 'iiiii',
            $order_id, $product_id, $item_qty, $item_price, $subtotal
        );
        mysqli_stmt_execute($stmt_item);

        // Kurangi stok
        mysqli_query($conn,
            "UPDATE product SET stok = GREATEST(0, stok - {$item_qty}) WHERE id_product = {$product_id}"
        );
    }

    mysqli_stmt_close($stmt_item);

    // 3. Hitung ulang total dari order_items (lebih akurat)
    $res_total = mysqli_query($conn,
        "SELECT COALESCE(SUM(subtotal), 0) AS real_total FROM order_items WHERE order_id = {$order_id}"
    );
    $real_total = (int) mysqli_fetch_assoc($res_total)['real_total'];
    $real_total += 2000; // biaya layanan

    mysqli_query($conn,
        "UPDATE orders SET total = {$real_total} WHERE id = {$order_id}"
    );

    mysqli_commit($conn);

    respond(true, 'Pesanan berhasil dibuat.', $order_id);

} catch (Exception $e) {
    mysqli_rollback($conn);
    respond(false, 'Terjadi kesalahan: ' . $e->getMessage());
}
