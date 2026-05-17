<?php
require_once '../config/koneksi.php';
$current_page = 'orders';
require_once '../includes/auth_check.php';
checkAdmin();

$status_filter = $_GET['status'] ?? 'all';
$success = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = mysqli_prepare($conn, "UPDATE orders SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $_POST['status'], $_POST['order_id']);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Status pesanan #" . (int)$_POST['order_id'] . " berhasil diperbarui!";
    }
    mysqli_stmt_close($stmt);
}

// Ambil daftar orders
$query = "SELECT o.*, u.nama AS user_nama
          FROM orders o
          JOIN users u ON o.user_id = u.id";

if ($status_filter !== 'all') {
    $sf    = mysqli_real_escape_string($conn, $status_filter);
    $query .= " WHERE o.status = '$sf'";
}
$query .= " ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

include '../includes/header.php';
?>
<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">
    <?php include '../includes/admin_sidebar.php'; ?>

    <main style="padding:3rem;background:var(--cream);">
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h1 style="font-family:var(--ff-display);font-size:2.2rem;color:var(--brown);">Daftar Pesanan</h1>
            <div style="display:flex;gap:.5rem;">
                <?php foreach (['all' => 'Semua', 'pending' => 'Masuk', 'processing' => 'Proses', 'ready' => 'Siap', 'completed' => 'Selesai'] as $val => $label): ?>
                    <a href="?status=<?= $val ?>" style="font-size:.8rem;padding:.4rem 1rem;background:#fff;border:1px solid var(--border);text-decoration:none;color:var(--text-mid);<?= $status_filter===$val ? 'border-color:var(--green);color:var(--green);' : '' ?>"><?= $label ?></a>
                <?php endforeach; ?>
            </div>
        </header>

        <?php if ($success): ?>
            <div style="background:#edf7ee;color:#2d5016;padding:1rem;margin-bottom:1.5rem;border:1px solid #d4e8d5;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <table style="width:100%;background:#fff;border-collapse:collapse;border:1px solid var(--border);">
            <thead style="background:var(--cream2);text-align:left;">
                <tr>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">ID</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Pelanggan</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Total</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Waktu</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Sumber</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Status</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Item</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="7" style="padding:3rem;text-align:center;color:var(--text-light);">Belum ada pesanan masuk.</td></tr>
                <?php endif; ?>

                <?php foreach ($orders as $o): ?>
                <?php
                    // Ambil item tiap order
                    $items_stmt = mysqli_prepare($conn,
                        "SELECT oi.quantity, p.nama_produk AS name
                         FROM order_items oi
                         JOIN product p ON oi.product_id = p.id_product
                         WHERE oi.order_id = ?"
                    );
                    mysqli_stmt_bind_param($items_stmt, 'i', $o['id']);
                    mysqli_stmt_execute($items_stmt);
                    $items = mysqli_fetch_all(mysqli_stmt_get_result($items_stmt), MYSQLI_ASSOC);
                    mysqli_stmt_close($items_stmt);
                ?>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:1rem;font-weight:600;">#<?= $o['id'] ?></td>
                    <td style="padding:1rem;"><?= htmlspecialchars($o['customer_name'] ?: $o['user_nama']) ?></td>
                    <td style="padding:1rem;font-weight:500;">Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
                    <td style="padding:1rem;font-size:.8rem;color:var(--text-mid);"><?= date('d M, H:i', strtotime($o['created_at'])) ?></td>
                    <td style="padding:1rem;">
                        <?php $src = $o['source'] ?? 'online'; ?>
                        <span style="font-size:.7rem;padding:.2rem .6rem;border-radius:20px;text-transform:uppercase;
                              background:<?= $src === 'offline' ? 'var(--amber-light)' : 'var(--cream2)' ?>;
                              color:<?= $src === 'offline' ? 'var(--amber)' : 'var(--text-mid)' ?>;">
                            <?= $src === 'offline' ? '&#128421; Kasir' : '&#127760; Online' ?>
                        </span>
                    </td>
                    <td style="padding:1rem;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="font-size:.8rem;padding:.3rem;border:1px solid var(--border);font-family:var(--ff-body);">
                                <?php foreach (['pending' => 'Masuk', 'processing' => 'Proses', 'ready' => 'Siap', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= $o['status']===$val ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td style="padding:1rem;">
                        <div style="font-size:.75rem;color:var(--text-mid);">
                            <?php if (empty($items)): ?>
                                <em>—</em>
                            <?php else: ?>
                                <?= implode(', ', array_map(fn($i) => $i['quantity'] . 'x ' . htmlspecialchars($i['name']), $items)) ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
<?php include '../includes/admin_footer.php'; ?>
