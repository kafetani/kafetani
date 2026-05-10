<?php
$admin_current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <h2 style="font-family:var(--ff-display);font-size:1.5rem;">Admin Panel</h2>
        <div style="font-size:0.7rem;opacity:0.6;letter-spacing:0.1em;text-transform:uppercase;margin-top:0.2rem;">Kafetani Dashboard</div>
    </div>
    
    <nav class="admin-nav">
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="admin-nav-link <?= ($admin_current_page == 'dashboard.php') ? 'active' : '' ?>">Dashboard</a>
        <a href="<?= BASE_URL ?>admin/products.php" class="admin-nav-link <?= ($admin_current_page == 'products.php') ? 'active' : '' ?>">Produk</a>
        <a href="<?= BASE_URL ?>admin/farmers.php" class="admin-nav-link <?= ($admin_current_page == 'farmers.php') ? 'active' : '' ?>">Petani</a>
        <a href="<?= BASE_URL ?>admin/orders.php" class="admin-nav-link <?= ($admin_current_page == 'orders.php') ? 'active' : '' ?>">Pesanan</a>
        <a href="<?= BASE_URL ?>admin/kasir.php" class="admin-nav-link <?= ($admin_current_page == 'kasir.php') ? 'active' : '' ?>">Kasir</a>
    </nav>

    <div style="margin-top:auto;">
        <hr style="opacity:.1;margin-bottom:1.5rem;">
        <a href="<?= BASE_URL ?>index.php" class="admin-nav-link">← Lihat Situs</a>
    </div>
</aside>
