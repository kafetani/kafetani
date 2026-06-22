<?php
session_start();
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
$categories = ['Semua'];
while ($row = mysqli_fetch_assoc($cat_result)) {
    if ($row['name']) $categories[] = $row['name'];
}
$page_title = 'Menu Kafe';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="page" id="page-menu">
  <!-- Page Header -->
  <div class="page-header">
    <div class="page-header-label">Kafetani · Menu Kafe</div>
    <h1 class="page-header-title">Menu Kafe</h1>
    <p class="page-header-sub">Minuman, bakeri, dan camilan buatan sendiri dari bahan lokal</p>
  </div>
  <!-- Filter Tabs Kategori -->
  <div class="filter-bar">
    <?php foreach ($categories as $index => $cat): ?>
      <button class="filter-tab <?= $index === 0 ? 'active' : '' ?>"
              data-cat="<?= htmlspecialchars($cat) ?>">
        <?= htmlspecialchars($cat) ?>
      </button>
    <?php endforeach; ?>
  </div>
  <!-- Grid Produk -->
  <div class="products-grid" id="menu-grid">
    <?php if (empty($menu_items)): ?>
      <p style="grid-column:1/-1;text-align:center;color:var(--text-light);padding:4rem">
        Menu belum tersedia.
      </p>
    <?php endif; ?>
    <?php foreach ($menu_items as $item): ?>
      <div class="product-card" data-cat="<?= htmlspecialchars($item['kategori']) ?>">
        <div class="product-thumb">
          <img src="assets/img/products/<?= htmlspecialchars($item['gambar']) ?>"
               alt="<?= htmlspecialchars($item['nama_produk']) ?>">
        </div>
        <div class="product-body">
          <div class="product-cat"><?= htmlspecialchars($item['kategori']) ?></div>
          <div class="product-name"><?= htmlspecialchars($item['nama_produk']) ?></div>
          <p class="product-desc"><?= htmlspecialchars($item['deskripsi']) ?></p>
          <div class="product-footer">
            <span class="product-price">
              Rp <?= number_format($item['harga'], 0, ',', '.') ?>
            </span>
            <button class="add-btn"
              data-id="<?= htmlspecialchars($item['nama_produk']) ?>"
              data-name="<?= htmlspecialchars($item['nama_produk']) ?>"
              data-price="<?= (int)$item['harga'] ?>"
              data-image="<?= htmlspecialchars($item['gambar']) ?>">+</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
<script src="assets/js/app.js?v=1.1"></script>
<script src="assets/js/menu.js?v=1.1"></script>