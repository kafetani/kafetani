<?php
/**
 * Admin Sidebar
 * 
 * Cara pakai: set variabel $current_page sebelum include file ini.
 * Nilai yang valid: 'dashboard', 'products', 'farmers', 'orders'
 *
 * Contoh:
 *   $current_page = 'dashboard';
 *   include '../includes/admin_sidebar.php';
 */

$current_page = $current_page ?? '';

$nav_items = [
    'dashboard' => ['href' => 'dashboard.php', 'label' => 'Dashboard'],
    'products'  => ['href' => 'products.php',  'label' => 'Produk'],
    'farmers'   => ['href' => 'farmers.php',   'label' => 'Petani'],
    'orders'    => ['href' => 'orders.php',    'label' => 'Pesanan'],
    'kasir'     => ['href' => 'kasir.php',     'label' => '🖥 Kasir POS'],
];
?>
<aside style="background:var(--brown);color:#fff;padding:2rem;">
    <h2 style="font-family:var(--ff-display);font-size:1.5rem;margin-bottom:1rem;">Kafetani Admin</h2>
    <nav style="display:flex;flex-direction:column;gap:.8rem;">
        <?php foreach ($nav_items as $key => $item): ?>
            <?php $is_active = ($current_page === $key); ?>
            <a href="<?= $item['href'] ?>"
               style="color:<?= $is_active ? 'var(--amber)' : '#fff' ?>;
                      text-decoration:none;
                      font-size:.9rem;
                      <?= $is_active ? '' : 'opacity:.7;' ?>">
                <?= $item['label'] ?>
            </a>
        <?php endforeach; ?>
        <hr style="opacity:.2;margin:1rem 0;">
        <a href="../index.php" style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">&#8592; Lihat Situs</a>
    </nav>
</aside>
