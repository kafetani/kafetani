<?php
// Data Menu (Simulasi Database)
$menu_items = [
    [
        "nama"      => "Kopi Susu Gula Aren",
        "kategori"  => "Kopi",
        "deskripsi" => "Espresso + susu segar + gula aren petani lokal",
        "harga"     => 28000,
        "gambar"    => "assets/img/products/kopi_susu_gula_aren.webp"
    ],
    [
        "nama"      => "Americano Arabica",
        "kategori"  => "Kopi",
        "deskripsi" => "Single origin biji kopi Arabica Gayo",
        "harga"     => 22000,
        "gambar"    => "assets/img/products/americano_arabica.webp"
    ],
    [
        "nama"      => "Cappuccino",
        "kategori"  => "Kopi",
        "deskripsi" => "Double shot espresso dengan microfoam susu",
        "harga"     => 26000,
        "gambar"    => "assets/img/products/cappuccino.webp"
    ],
    [
        "nama"      => "Croissant Butter",
        "kategori"  => "Bakeri",
        "deskripsi" => "Berlapis-lapis, renyah di luar lembut di dalam",
        "harga"     => 22000,
        "gambar"    => "assets/img/products/croissant_butter.webp"
    ],
    [
        "nama"      => "Roti Gandum",
        "kategori"  => "Bakeri",
        "deskripsi" => "Roti gandum utuh homemade tanpa pengawet",
        "harga"     => 16000,
        "gambar"    => "assets/img/products/roti_gandum.webp"
    ],
    [
        "nama"      => "Chocolate Cake",
        "kategori"  => "Camilan",
        "deskripsi" => "Kue cokelat lembut dengan taburan cokelat",
        "harga"     => 25000,
        "gambar"    => "assets/img/products/chocolate_cake.webp"
    ]
];

// Ambil kategori unik untuk filter tab
$categories = ["Semua", "Kopi", "Non-Kopi", "Bakeri", "Camilan"];
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
            <a href="auth/login.php">LOGIN</a>
        </nav>
        <button class="cart">🛒 Keranjang (0)</button>
    </header>

    <!-- Menu Section -->
    <section class="menu-section">
        <header class="section-header">
            <h1>Menu Kafe</h1>
            <p>Minuman, bakeri, dan camilan buatan sendiri dari bahan lokal</p>
        </header>

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
            <?php foreach ($menu_items as $item): ?>
                <div class="item-card <?= $item['kategori'] ?>">
                    <img src="<?= $item['gambar'] ?>" alt="<?= $item['nama'] ?>">
                    <h3><?= $item['nama'] ?></h3>
                    <p><?= $item['deskripsi'] ?></p>
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
