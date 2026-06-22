<?php
// File: kafetani/admin/dashboard.php

session_start();
require_once '../includes/auth_check.php';
checkAdmin();

<<<<<<< HEAD
// Panggil Controller dari folder app
require_once '../../app/Controller/dashboardController.php';

// Jalankan aplikasi via MVC
$controller = new DashboardController();
$controller->index();
?>
=======
$admin_name       = $_SESSION['nama'];
$total_pendapatan = 28000;
$total_pesanan    = 7;
$total_produk     = 9;
$total_petani     = 3;
$current_page     = 'dashboard';
?>
<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/style_dashboard.css">
<script src="../assets/js/dashboard.js" defer></script>

<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">

    <!-- Sidebar -->
    <?php include '../includes/admin_sidebar.php'; ?>

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
<?php include '../includes/admin_footer.php'; ?>
>>>>>>> 96e46403ac59177fac5aae4a2cc738b9225279a3
