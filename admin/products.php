<?php
session_start();
require_once '../includes/auth_check.php';
checkAdmin();

include "../config/koneksi.php";

// Data Produk (Simulasi Database)
$products = [
    [
        "gambar"   => "../assets/img/products/kopi_susu_gula_aren.webp",
        "nama"     => "Kopi Susu Gula Aren",
        "kategori" => "Kopi",
        "tipe"     => "CAFE",
        "harga"    => 28000,
        "stok"     => "50 cup"
    ],
    [
        "gambar"   => "../assets/img/products/americano_arabica.webp",
        "nama"     => "Americano Arabica",
        "kategori" => "Kopi",
        "tipe"     => "CAFE",
        "harga"    => 22000,
        "stok"     => "100 cup"
    ],
    [
        "gambar"   => "../assets/img/products/cappuccino.webp",
        "nama"     => "Cappuccino",
        "kategori" => "Kopi",
        "tipe"     => "CAFE",
        "harga"    => 26000,
        "stok"     => "33 cup"
    ],
    [
        "gambar"   => "../assets/img/products/croissant_butter.webp",
        "nama"     => "Croissant Butter",
        "kategori" => "Bakeri",
        "tipe"     => "CAFE",
        "harga"    => 22000,
        "stok"     => "20 pcs"
    ],
    [
        "gambar"   => "../assets/img/products/roti_gandum.webp",
        "nama"     => "Roti Gandum",
        "kategori" => "Bakeri",
        "tipe"     => "CAFE",
        "harga"    => 16000,
        "stok"     => "15 pcs"
    ],
    [
        "gambar"   => "../assets/img/products/chocolate_cake.webp",
        "nama"     => "Chocolate Cake",
        "kategori" => "Camilan",
        "tipe"     => "CAFE",
        "harga"    => 32000,
        "stok"     => "10 slice"
    ],
    [
        "gambar"   => "../assets/img/products/biji_kopi_arabica_gayo.webp",
        "nama"     => "Biji Kopi Arabica Gayo",
        "kategori" => "Bahan Baku",
        "tipe"     => "MARKET",
        "harga"    => 85000,
        "stok"     => "30 250g"
    ],
    [
        "gambar"   => "../assets/img/products/gula_aren.webp",
        "nama"     => "Gula Aren Organik",
        "kategori" => "Bahan Baku",
        "tipe"     => "MARKET",
        "harga"    => 45000,
        "stok"     => "48 500g"
    ]
];
?>
<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/style_produk.css">
<script src="../assets/js/produk.js" defer></script>

<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">

    <!-- Sidebar -->
    <aside style="background:var(--brown);color:#fff;padding:2rem;">
        <h2 style="font-family:var(--ff-display);font-size:1.5rem;margin-bottom:1rem;">Kafetani Admin</h2>
        <nav style="display:flex;flex-direction:column;gap:.8rem;">
            <a href="dashboard.php" style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Dashboard</a>
            <a href="products.php"  style="color:var(--amber);text-decoration:none;font-size:.9rem;">Produk</a>
            <a href="farmers.php"   style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Petani</a>
            <a href="orders.php"    style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">Pesanan</a>
            <hr style="opacity:.2;margin:1rem 0;">
            <a href="../index.php"  style="color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;">&#8592; Lihat Situs</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1>Manajemen Produk</h1>
            <button class="add-product">+ Produk Baru</button>
        </div>

        <!-- Table -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><img src="<?= $p['gambar'] ?>" alt="<?= htmlspecialchars($p['nama']) ?>"></td>
                        <td><?= htmlspecialchars($p['nama']) ?></td>
                        <td><?= htmlspecialchars($p['kategori']) ?></td>
                        <td>
                            <span style="color: <?= $p['tipe'] == 'MARKET' ? '#e67e22' : '#2980b9' ?>; font-weight: bold;">
                                <?= $p['tipe'] ?>
                            </span>
                        </td>
                        <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($p['stok']) ?></td>
                        <td>
                            <a href="?edit=<?= urlencode($p['nama']) ?>">Edit</a> |
                            <a href="?hapus=<?= urlencode($p['nama']) ?>" style="color: #c0392b;">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

</div>
<?php include '../includes/footer.php'; ?>
