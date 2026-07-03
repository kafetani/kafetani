// Kafetani - Main Application Logic

let cart = JSON.parse(localStorage.getItem('kafetani_cart')) || [];

// ── CART FUNCTIONS ───────────────────────────────────────────────────────────
function addToCart(item, fromBtn) {
  const existing = cart.find(c => c.id === item.id);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({...item, qty: 1});
  }
  saveCart();
  updateCartBadge();
  if (fromBtn) {
    fromBtn.textContent = '✓';
    fromBtn.classList.add('added');
    setTimeout(() => { fromBtn.textContent = '+'; fromBtn.classList.remove('added'); }, 800);
  }
  showToast(`${item.name} ditambahkan ke keranjang`);
}

function updateCartBadge() {
  const total = cart.reduce((s, c) => s + c.qty, 0);
  const badge = document.getElementById('cart-badge');
  if (badge) badge.textContent = total;
}

function saveCart() {
  localStorage.setItem('kafetani_cart', JSON.stringify(cart));
}

function openCart() {
  document.getElementById('cart-overlay').classList.add('open');
  document.getElementById('cart-panel').classList.add('open');
  renderCart();
}

function closeCart() {
  document.getElementById('cart-overlay').classList.remove('open');
  document.getElementById('cart-panel').classList.remove('open');
}

function renderCart() {
  const el = document.getElementById('cart-items');
  const bottom = document.getElementById('cart-bottom');
  if (!el) return;

  if (cart.length === 0) {
    el.innerHTML = `<div class="cart-empty"><div class="cart-empty-icon">🛒</div><p>Keranjangmu masih kosong.<br>Yuk pilih menu atau produk!</p></div>`;
    if (bottom) bottom.style.display = 'none';
    return;
  }

  if (bottom) bottom.style.display = 'block';
  el.innerHTML = cart.map((item, i) => `
    <div class="cart-item">
      <div class="cart-item-icon">
      ${item.image 
          ? `<img src="/products/${item.image}" style="width:100%;height:100%;object-fit:cover;border-radius:2px;">` 
          : (item.icon || '📦')}
      </div>
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">Rp ${(item.price * item.qty).toLocaleString('id')}</div>
        <div class="cart-item-qty">
          <button class="qty-btn" onclick="changeQty(${i},-1)">−</button>
          <span class="qty-num">${item.qty}</span>
          <button class="qty-btn" onclick="changeQty(${i},1)">+</button>
          <button class="cart-remove" onclick="removeItem(${i})">Hapus</button>
        </div>
      </div>
    </div>
  `).join('');

  const sub = cart.reduce((s,c) => s + c.price * c.qty, 0);
  const subEl = document.getElementById('cart-sub');
  const totalEl = document.getElementById('cart-total');
  if (subEl) subEl.textContent = 'Rp ' + sub.toLocaleString('id');
  if (totalEl) totalEl.textContent = 'Rp ' + (sub + 2000).toLocaleString('id');
}

function changeQty(i, d) {
  cart[i].qty += d;
  if (cart[i].qty <= 0) cart.splice(i, 1);
  saveCart();
  updateCartBadge();
  renderCart();
}

function removeItem(i) {
  cart.splice(i, 1);
  saveCart();
  updateCartBadge();
  renderCart();
}

async function checkout() {
  if (cart.length === 0) {
    showToast("Keranjang kosong!");
    return;
  }
  
  const sub = cart.reduce((s,c) => s + c.price * c.qty, 0);
  const total = sub + 2000;

  try {
    const response = await fetch('api/orders.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ cart, total, type: 'pickup' })
    });
    
    const result = await response.json();
    if (result.success) {
      cart = [];
      saveCart();
      updateCartBadge();
      closeCart();
      document.getElementById('order-success').classList.add('show');
    } else {
      showToast(result.message || "Gagal membuat pesanan.");
      if (result.message && result.message.includes("login")) {
          setTimeout(() => window.location.href = 'auth/login.php', 1500);
      }
    }
  } catch (error) {
    showToast("Kesalahan jaringan: " + error.message);
  }
}

function closeSuccess() {
  document.getElementById('order-success').classList.remove('show');
}

function showToast(msg) {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2200);
}

// ── INISIALISASI HALAMAN ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();

    // Auto-bind semua tombol + yang punya data-name & data-price
    // Berlaku untuk menu.php (.add-btn) maupun marketplace.php (.add-to-cart)
    document.querySelectorAll('[data-name][data-price]').forEach(btn => {
        btn.addEventListener('click', function () {
            addToCart({
                id:    this.dataset.id    || this.dataset.name,
                name:  this.dataset.name,
                price: parseInt(this.dataset.price, 10),
                image: this.dataset.image || null,
                icon:  this.dataset.icon  || '📦'
            }, this);
        });
    });
});
