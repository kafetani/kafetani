// File: menu.js

document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".tabs button");
  const items = document.querySelectorAll(".menu-items .item-card");
  const cartBtn = document.querySelector(".cart");
  let cartCount = 0;

  // Fungsi update keranjang
  function updateCart() {
    cartBtn.textContent = `🛒 Keranjang (${cartCount})`;
  }

  // Event klik tombol tambah (+)
  const addBtns = document.querySelectorAll(".add-btn");
  addBtns.forEach(btn => {
    btn.addEventListener("click", function () {
      cartCount++;
      updateCart();
    });
  });

  // Event klik tab kategori
  tabs.forEach(tab => {
    tab.addEventListener("click", function () {
      // Hapus class active dari semua tab
      tabs.forEach(t => t.classList.remove("active"));
      this.classList.add("active");

      const category = this.textContent.trim();

      items.forEach(item => {
        if (category === "Semua") {
          item.style.display = "block";
        } else {
          // Cocokkan dengan class item-card
          if (item.classList.contains(category)) {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
        }
      });
    });
  });
});
