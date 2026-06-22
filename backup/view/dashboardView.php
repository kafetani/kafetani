<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Kafetani - Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style_dashboard.css">
    <script src="../assets/js/dashboard.js" defer></script>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div>
                <h2>Kafetani</h2>
                <ul>
                    <?php if (isset($sidebar_menu) && is_array($sidebar_menu)): ?>
                        <?php foreach ($sidebar_menu as $menu): ?>
                            <li>
                                <a href="<?= $menu['link'] ?>" style="<?= $menu['active'] ? 'font-weight: bold; color: #27ae60;' : '' ?>">
                                    <?= $menu['label'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <a href="../index.php" class="view-site">← Lihat Situs</a>
        </aside>

        <main class="main">
            <header>
                <h1>Ringkasan Bisnis</h1>
                <p>Selamat datang, <?= htmlspecialchars($admin_name ?? 'Admin') ?>. Berikut statistik hari ini</p>
            </header>

            <section class="stats">
                <div class="card">
                    <h3>Total Pendapatan</h3>
                    <p>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
                </div>
                <div class="card">
                    <h3>Total Pesanan</h3>
                    <p><?= $total_pesanan ?></p>
                </div>
                <div class="card">
                    <h3>Produk Tersedia</h3>
                    <p><?= $total_produk ?></p>
                </div>
                <div class="card">
                    <h3>Petani Mitra</h3>
                    <p><?= $total_petani ?></p>
                </div>
            </section>

            <section class="actions">
                <button class="btn">+ Tambah Produk Baru</button>
                <button class="btn">+ Daftarkan Petani</button>
            </section>
        </main>
    </div>
</body>

</html>