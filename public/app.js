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
    el.innerHTML = `<div class="cart-empty"><div class="cart-empty-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="56" height="56" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></div><p>Keranjangmu masih kosong.<br>Yuk pilih menu atau produk!</p></div>`;
    if (bottom) bottom.style.display = 'none';
    return;
  }

  if (bottom) bottom.style.display = 'block';
  el.innerHTML = cart.map((item, i) => `
    <div class="cart-item">
      <div class="cart-item-icon">
      ${item.image 
          ? `<img src="/products/${item.image}" style="width:100%;height:100%;object-fit:cover;border-radius:2px;">` 
          : (item.icon || '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>')}
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

  // Mendapatkan token CSRF dari meta tag di layout Blade
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  try {
    const response = await fetch('/api/orders', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {})
      },
      body: JSON.stringify({ cart, total, type: 'pickup' })
    });
    
    // Menangani respon tidak terautentikasi (Unauthorized) dari Laravel Middleware
    if (response.status === 401) {
      showToast("Silakan login terlebih dahulu untuk membuat pesanan.");
      setTimeout(() => window.location.href = '/login', 1500);
      return;
    }

    const result = await response.json();
    if (result.success) {
      cart = [];
      saveCart();
      updateCartBadge();
      closeCart();
      document.getElementById('order-success').classList.add('show');
    } else {
      showToast(result.message || "Gagal membuat pesanan.");
      if (result.message && result.message.toLowerCase().includes("login")) {
          setTimeout(() => window.location.href = '/login', 1500);
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
                icon:  this.dataset.icon  || '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>'
            }, this);
        });
    });
});