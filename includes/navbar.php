<?php
$current_page = basename($_SERVER['PHP_SELF']);
$parts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
$base = '';
if (isset($parts[0]) && strpos($parts[0], '.') === false) {
  $base = '/' . $parts[0];
}
?>
<nav class="main-nav">
  <a href="<?php echo $base; ?>/index.php" class="nav-logo">
    <img src="<?php echo $base; ?>/assets/img/logo_v3.svg" alt="Kafetani Logo" style="height:30px;">
  </a>
  <div class="nav-links">
    <a href="<?php echo $base; ?>/index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">Beranda</a>
    <a href="<?php echo $base; ?>/menu.php" class="nav-link <?= ($current_page == 'menu.php') ? 'active' : '' ?>">Menu Kafe</a>
    <a href="<?php echo $base; ?>/marketplace.php"
      class="nav-link <?= ($current_page == 'marketplace.php') ? 'active' : '' ?>">Marketplace</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="<?php echo $base; ?>/admin/dashboard.php" class="nav-link">Admin</a>
      <?php endif; ?>
      <a href="<?php echo $base; ?>/auth/logout.php" class="nav-link">Logout</a>
    <?php else: ?>
      <a href="<?php echo $base; ?>/auth/login.php" class="nav-link">Login</a>
    <?php endif; ?>
  </div>
  <button class="nav-cart" onclick="openCart()">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;">
      <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
      <line x1="3" y1="6" x2="21" y2="6"/>
      <path d="M16 10a4 4 0 01-8 0"/>
    </svg>
    Keranjang <span class="cart-badge" id="cart-badge">0</span>
  </button>
</nav>