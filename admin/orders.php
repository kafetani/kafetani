<?php
require_once '../config/koneksi.php';
require_once '../includes/auth_check.php';
checkAdmin();

$status_filter = $_GET['status'] ?? 'all';
$success = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($stmt->execute([$_POST['status'], $_POST['order_id']])) {
        $success = "Status pesanan #" . $_POST['order_id'] . " berhasil diperbarui!";
    }
}

$query = "SELECT o.*, u.nama as customer_name FROM orders o JOIN users u ON o.user_id = u.id";
if ($status_filter !== 'all') {
    $query .= " WHERE o.status = " . $pdo->quote($status_filter);
}
$query .= " ORDER BY o.created_at DESC";

$orders = $pdo->query($query)->fetchAll();

include '../includes/header.php';
?>
<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">
    <aside style="background:var(--brown);color:#fff;padding:2rem;">
        <h2 style="font-family:var(--ff-display);font-size:1.5rem;margin-bottom:1rem;">Kafetani Admin</h2>
        <nav style="display:flex;flex-direction:column;gap:.8rem;">
            <a href="dashboard.php" style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Dashboard</a>
            <a href="products.php" style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Produk</a>
            <a href="farmers.php" style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Petani</a>
            <a href="orders.php" style="color:var(--amber);text-decoration:none;font-size:.9rem;">Pesanan</a>
            <hr style="opacity:.2;margin:1rem 0;">
            <a href="../index.php" style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">← Lihat Situs</a>
        </nav>
    </aside>

    <main style="padding:3rem;background:var(--cream);">
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h1 style="font-family:var(--ff-display);font-size:2.2rem;color:var(--brown);">Daftar Pesanan</h1>
            <div style="display:flex;gap:.5rem;">
                <a href="?status=all" style="font-size:.8rem;padding:.4rem 1rem;background:#fff;border:1px solid var(--border);text-decoration:none;color:var(--text-mid); <?= $status_filter=='all'?'border-color:var(--green);color:var(--green);':'' ?>">Semua</a>
                <a href="?status=pending" style="font-size:.8rem;padding:.4rem 1rem;background:#fff;border:1px solid var(--border);text-decoration:none;color:var(--text-mid); <?= $status_filter=='pending'?'border-color:var(--green);color:var(--green);':'' ?>">Masuk</a>
                <a href="?status=processing" style="font-size:.8rem;padding:.4rem 1rem;background:#fff;border:1px solid var(--border);text-decoration:none;color:var(--text-mid); <?= $status_filter=='processing'?'border-color:var(--green);color:var(--green);':'' ?>">Proses</a>
                <a href="?status=ready" style="font-size:.8rem;padding:.4rem 1rem;background:#fff;border:1px solid var(--border);text-decoration:none;color:var(--text-mid); <?= $status_filter=='ready'?'border-color:var(--green);color:var(--green);':'' ?>">Siap</a>
                <a href="?status=completed" style="font-size:.8rem;padding:.4rem 1rem;background:#fff;border:1px solid var(--border);text-decoration:none;color:var(--text-mid); <?= $status_filter=='completed'?'border-color:var(--green);color:var(--green);':'' ?>">Selesai</a>
            </div>
        </header>

        <?php if($success): ?><div style="background:#edf7ee;color:#2d5016;padding:1rem;margin-bottom:1.5rem;border:1px solid #d4e8d5;"><?= $success ?></div><?php endif; ?>

        <table style="width:100%;background:#fff;border-collapse:collapse;border:1px solid var(--border);">
            <thead style="background:var(--cream2);text-align:left;">
                <tr>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">ID</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Pelanggan</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Total</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Waktu</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Tipe</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Status</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:1rem;font-weight:600;">#<?= $o['id'] ?></td>
                    <td style="padding:1rem;"><?= $o['customer_name'] ?></td>
                    <td style="padding:1rem;font-weight:500;">Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
                    <td style="padding:1rem;font-size:.8rem;color:var(--text-mid);"><?= date('d M, H:i', strtotime($o['created_at'])) ?></td>
                    <td style="padding:1rem;"><span style="font-size:.7rem;padding:.2rem .6rem;background:var(--cream2);border-radius:20px;text-transform:uppercase;"><?= $o['type'] ?></span></td>
                    <td style="padding:1rem;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="font-size:.8rem;padding:.3rem;border:1px solid var(--border);font-family:var(--ff-body);">
                                <option value="pending" <?= $o['status']=='pending'?'selected':'' ?>>Masuk</option>
                                <option value="processing" <?= $o['status']=='processing'?'selected':'' ?>>Proses</option>
                                <option value="ready" <?= $o['status']=='ready'?'selected':'' ?>>Siap</option>
                                <option value="completed" <?= $o['status']=='completed'?'selected':'' ?>>Selesai</option>
                                <option value="cancelled" <?= $o['status']=='cancelled'?'selected':'' ?>>Dibatalkan</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding:1rem;">
                        <?php
                        $items_stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                        $items_stmt->execute([$o['id']]);
                        $items = $items_stmt->fetchAll();
                        ?>
                        <div style="font-size:.75rem;color:var(--text-mid);">
                            <?php foreach($items as $idx => $item): ?>
                                <?= $item['quantity'] ?>x <?= $item['name'] ?><?= $idx < count($items)-1 ? ', ' : '' ?>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($orders)): ?>
                    <tr><td colspan="7" style="padding:3rem;text-align:center;color:var(--text-light);">Belum ada pesanan masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
