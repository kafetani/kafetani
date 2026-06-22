// menu.js — filter tab kategori untuk halaman Menu Kafe
// Logika keranjang ditangani oleh app.js
document.addEventListener('DOMContentLoaded', function () {
  const tabs  = document.querySelectorAll('.filter-tab');
  const cards = document.querySelectorAll('#menu-grid .product-card');
  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      // Update active tab
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      const category = this.dataset.cat;
      // Show/hide cards berdasarkan kategori
      cards.forEach(card => {
        const match = category === 'Semua' || card.dataset.cat === category;
        card.style.display = match ? '' : 'none';
      });
    });
  });
});