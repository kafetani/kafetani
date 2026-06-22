<?php
session_start();
include 'config/koneksi.php';
// Ambil semua produk marketplace dari database
$query  = mysqli_query($conn, "SELECT * FROM product WHERE type = 'market'");
$products = mysqli_fetch_all($query, MYSQLI_ASSOC);
// Data petani mitra (statis)
$farmers = [
    ['name' => 'Semua Petani',  'loc' => 'Semua Wilayah',       'img' => 'semua_petani.webp', 'active' => true],
    ['name' => 'Pak Budi',      'loc' => 'Gayo, Aceh',          'img' => 'pak_budi.webp',     'active' => false],
    ['name' => 'Bu Sari',       'loc' => 'Temanggung, Jateng',  'img' => 'bu_sari.webp',      'active' => false],
    ['name' => 'Pak Yusuf',     'loc' => 'Pangalengan, Jabar',  'img' => 'pak_yusuf.webp',    'active' => false],
];
$page_title = 'Marketplace';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="page" id="page-market">
  <!-- Page Header -->
  <div class="page-header">
    <div class="page-header-label">Kafetani · Marketplace</div>
    <h1 class="page-header-title">Marketplace Petani</h1>
    <p class="page-header-sub">Beli langsung dari petani lokal — biji kopi, gula aren, dan produk segar pilihan</p>
  </div>
  <!-- Layout: Sidebar + Konten -->
  <div class="market-layout">
    <!-- Sidebar Petani Mitra -->
    <aside class="market-sidebar">
      <div class="sidebar-title">Petani Mitra</div>
      <?php foreach ($farmers as $farmer): ?>
        <div class="farmer-card <?= $farmer['active'] ? 'active' : '' ?>">
          <div class="farmer-avatar">
            <img src="assets/img/farmers/<?= htmlspecialchars($farmer['img']) ?>"
                 alt="<?= htmlspecialchars($farmer['name']) ?>">
          </div>
          <div>
            <div class="farmer-info-name"><?= htmlspecialchars($farmer['name']) ?></div>
            <div class="farmer-info-loc"><?= htmlspecialchars($farmer['loc']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </aside>
    <!-- Konten Produk -->
    <div class="market-products">
      <!-- Banner -->
      <div class="market-banner">
        <div class="market-banner-text">
          <h3>Langsung dari Kebun</h3>
          <p>Setiap produk dikirim segar, tanpa perantara</p>
        </div>
        <div class="market-banner-icon">🌿</div>
      </div>
      <!-- Grid Produk -->
      <div class="market-grid">
        <?php if (empty($products)): ?>
          <p style="grid-column:1/-1;text-align:center;color:var(--text-light);padding:4rem">
            Produk belum tersedia.
          </p>
        <?php endif; ?>
        <?php foreach ($products as $data): ?>
          <div class="product-card">
            <div class="product-thumb green">
              <img src="assets/img/products/<?= htmlspecialchars($data['gambar']) ?>"
                   alt="<?= htmlspecialchars($data['nama_produk']) ?>">
            </div>
            <div class="product-body">
              <div class="product-cat"><?= htmlspecialchars($data['petani']) ?></div>
              <div class="product-name"><?= htmlspecialchars($data['nama_produk']) ?></div>
              <p class="product-desc"><?= htmlspecialchars($data['deskripsi']) ?></p>
              <div class="product-footer">
                <span class="product-price">
                  Rp <?= number_format($data['harga'], 0, ',', '.') ?>
                </span>
                <button class="add-btn"
                  data-id="<?= (int)$data['id_product'] ?>"
                  data-name="<?= htmlspecialchars($data['nama_produk']) ?>"
                  data-price="<?= (int)$data['harga'] ?>"
                  data-image="<?= htmlspecialchars($data['gambar']) ?>">+</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
<script src="assets/js/app.js?v=1.1"></script>