<?php
include 'config/koneksi.php';

// Ambil semua menu kafe dari database
$result = mysqli_query($conn, "
    SELECT p.nama_produk, p.deskripsi, p.harga, p.gambar, c.name AS kategori
    FROM product p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.type = 'cafe' AND p.stok > 0
    ORDER BY c.name, p.nama_produk
");
$menu_items = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Ambil kategori unik untuk filter tab
$cat_result = mysqli_query($conn, "
    SELECT DISTINCT c.name
    FROM product p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.type = 'cafe' AND p.stok > 0
    ORDER BY c.name
");
$categories = ["Semua"];
while ($row = mysqli_fetch_assoc($cat_result)) {
    if ($row['name']) $categories[] = $row['name'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kafetani - Menu Kafe</title>
    <link rel="stylesheet" href="assets/css/style_menu.css">
    <script src="assets/js/menu.js" defer></script>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <a href="index.php">BERANDA</a>
            <a href="menu.php">MENU KAFE</a>
            <a href="marketplace.php">MARKETPLACE</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="/kafetani/admin/dashboard.php" class="nav-link">ADMIN</a>
                <?php endif; ?>
                <a href="/kafetani/auth/logout.php" class="nav-link">LOGOUT</a>
            <?php else: ?>
                <a href="/kafetani/auth/login.php" class="nav-link">LOGIN</a>
            <?php endif; ?>
        </nav>
        <button class="cart">🛒 Keranjang (0)</button>
    </header>

    <!-- Menu Section -->
    <section class="menu-section">
        </tr>
        <thread>
            <h1>Menu Kafe</h1>
            <p>Minuman, bakeri, dan camilan buatan sendiri dari bahan lokal</p>
        </thread>
        <tr>
            

        <!-- Tabs Kategori -->
        <div class="tabs">
            <?php foreach ($categories as $index => $cat): ?>
                <button class="<?= $index === 0 ? 'active' : '' ?>">
                    <?= $cat ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Menu Items -->
        <div class="menu-items">
            <?php if (empty($menu_items)): ?>
                <p style="text-align:center; color:#888;">Menu belum tersedia.</p>
            <?php endif; ?>
            <?php foreach ($menu_items as $item): ?>
                <div class="item-card <?= htmlspecialchars($item['kategori']) ?>">
                    <img src="assets/img/products/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                    <h3><?= htmlspecialchars($item['nama_produk']) ?></h3>
                    <p><?= htmlspecialchars($item['deskripsi']) ?></p>
                    <div class="price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></div>
                    <button class="add-btn">+</button>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer>
        © <?= date('Y') ?> Kafetani - Semua hak dilindungi
    </footer>
</body>
</html>
