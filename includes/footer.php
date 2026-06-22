<?php include __DIR__ . '/cart.php'; ?>

<footer>
  <div class="footer-grid">
    <div class="footer-col">
      <img src="<?= BASE_URL ?>assets/img/logo_footer.svg" alt="Kafetani Logo" class="footer-logo">
      <p class="footer-desc">Kafetani menghadirkan kesegaran ladang langsung ke meja kamu. Kami percaya pada keadilan bagi petani dan kualitas terbaik bagi penikmat kopi.</p>
    </div>
    
    <div class="footer-col">
      <h4 class="footer-title">Navigasi</h4>
      <ul class="footer-links">
        <li><a href="<?= BASE_URL ?>index.php" class="footer-link">Beranda</a></li>
        <li><a href="<?= BASE_URL ?>menu.php" class="footer-link">Menu Kafe</a></li>
        <li><a href="<?= BASE_URL ?>marketplace.php" class="footer-link">Marketplace</a></li>
        <li><a href="<?= BASE_URL ?>auth/login.php" class="footer-link">Admin Panel</a></li>
      </ul>
    </div>
    
    <div class="footer-col">
      <h4 class="footer-title">Bantuan</h4>
      <ul class="footer-links">
        <li><a href="<?= BASE_URL ?>cara-pesan.php" class="footer-link">Cara Pesan</a></li>
        <li><a href="<?= BASE_URL ?>tentang-kami.php" class="footer-link">Tentang Kami</a></li>
        <li><a href="<?= BASE_URL ?>syarat-ketentuan.php" class="footer-link">Syarat & Ketentuan</a></li>
        <li><a href="<?= BASE_URL ?>kebijakan-privasi.php" class="footer-link">Kebijakan Privasi</a></li>
      </ul>
    </div>
    
    <div class="footer-col">
      <h4 class="footer-title">Hubungi Kami</h4>
      <div class="footer-contact">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
          <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/>
          <circle cx="12" cy="10" r="3"/>
        </svg>
        Jl. Ladang Hijau No. 12, Bandung
      </div>
      <div class="footer-contact">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -3 24 27" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
          <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.8 9.82 19.79 19.79 0 01.73 1.18 2 2 0 012.72-.82h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 7a16 16 0 006.09 6.09l1.08-1.08a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
        </svg>
        +62 812 3456 7890
      </div>
      <div class="footer-contact">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
          <rect x="2" y="4" width="20" height="16" rx="2"/>
          <path d="M2 7l10 7 10-7"/>
        </svg>
        halo@kafetani.com
      </div>
    </div>
  </div>
  
  <div class="footer-bottom">
    <p>&copy; 2026 Kafetani. Semua Hak Dilindungi.</p>
    <p>Dibuat dengan 
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="#C8883A" stroke="#C8883A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin: 0 2px;">
        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
      </svg>
      untuk Petani Indonesia
    </p>
  </div>
</footer>

<script src="<?= BASE_URL ?>assets/js/app.js?v=1.1"></script>
</body>
</html>
