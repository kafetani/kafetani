<?php
session_start();
require_once '../includes/auth_check.php';
checkAdmin();

$admin_name       = $_SESSION['nama'];
$total_pendapatan = 28000;
$total_pesanan    = 7;
$total_produk     = 9;
$total_petani     = 3;
?>
<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/style_dashboard.css">
<script src="../assets/js/dashboard.js" defer></script>

<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">

    <!-- Sidebar -->
    <aside style="background:var(--brown);color:#fff;padding:2rem;">
        <h2 style="font-family:var(--ff-display);font-size:1.5rem;margin-bottom:1rem;">Kafetani Admin</h2>
        <nav style="display:flex;flex-direction:column;gap:.8rem;">
            <a href="dashboard.php" style="color:var(--amber);text-decoration:none;font-size:.9rem;">Dashboard</a>
            <a href="products.php"  style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Produk</a>
            <a href="farmers.php"   style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Petani</a>
            <a href="orders.php"    style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Pesanan</a>
            <hr style="opacity:.2;margin:1rem 0;">
            <a href="../index.php"  style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">&#8592; Lihat Situs</a>
        </nav>
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
<?php include '../includes/footer.php'; ?>
