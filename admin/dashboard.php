<?php
session_start();
require_once '../includes/auth_check.php';
checkAdmin();

// Data Simulasi (Nantinya data ini bisa diambil dari Database)
$admin_name       = $_SESSION['nama'];
$total_pendapatan = 28000;
$total_pesanan    = 7;
$total_produk     = 9;
$total_petani     = 3;

// Array untuk Menu Sidebar agar lebih dinamis
$sidebar_menu = [
    ['label' => 'Dashboard', 'link' => 'dashboard.php', 'active' => true],
    ['label' => 'Produk',    'link' => 'products.php',  'active' => false],
];
?>

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
        <!-- Sidebar -->
        <aside class="sidebar">
            <div>
                <h2>Kafetani</h2>
                <ul>
                    <?php foreach ($sidebar_menu as $menu): ?>
                        <li>
                            <a href="<?= $menu['link'] ?>" style="<?= $menu['active'] ? 'font-weight: bold; color: #27ae60;' : '' ?>">
                                <?= $menu['label'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="../index.php" class="view-site">← Lihat Situs</a>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <header>
                <h1>Ringkasan Bisnis</h1>
                <p>Selamat datang, <?= htmlspecialchars($admin_name) ?>. Berikut statistik hari ini</p>
            </header>

            <!-- Statistik -->
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

            <!-- Aksi Cepat -->
            <section class="actions">
                <button class="btn">+ Tambah Produk Baru</button>
                <button class="btn">+ Daftarkan Petani</button>
            </section>
        </main>
    </div>
</body>
</html>
