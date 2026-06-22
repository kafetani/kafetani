<?php
/**
 * Keranjang Belanja — Kafetani
 *
 * File ini berisi HTML panel keranjang + CSS mandiri (tidak butuh CSS var dari header.php).
 * Include file ini di semua halaman yang perlu fitur keranjang.
 *
 * Cara pakai:
 *   <?php include 'includes/cart.php'; ?>      ← dari halaman di root
 *   <?php include __DIR__ . '/cart.php'; ?>    ← dari includes/ lain
 *
 * Pastikan assets/js/app.js sudah di-load SETELAH include ini.
 */
?>

<!-- ══ CART: CSS mandiri (aman di-include di halaman tanpa CSS var) ══ -->
<style id="kafetani-cart-styles">
  /* Hindari duplikasi jika sudah ada dari header.php */
  #cart-overlay{position:fixed;inset:0;background:rgba(42,31,18,.45);z-index:200;opacity:0;pointer-events:none;transition:opacity .3s}
  #cart-overlay.open{opacity:1;pointer-events:all}
  #cart-panel{position:fixed;right:0;top:0;bottom:0;width:380px;background:#F7F3EC;z-index:201;transform:translateX(100%);transition:transform .3s ease;display:flex;flex-direction:column;border-left:1px solid #D9CEBC;font-family:'DM Sans',sans-serif}
  #cart-panel.open{transform:translateX(0)}
  #cart-panel .cart-top{padding:1.5rem;border-bottom:1px solid #D9CEBC;display:flex;align-items:center;justify-content:space-between}
  #cart-panel .cart-top h2{font-family:'Cormorant Garamond',Georgia,serif;font-size:1.6rem;font-weight:300;color:#3B2A1A;margin:0}
  #cart-panel .cart-close{background:none;border:none;font-size:1.4rem;cursor:pointer;color:#7A6550;padding:.2rem;line-height:1}
  #cart-panel .cart-items{flex:1;overflow-y:auto;padding:1.2rem}
  #cart-panel .cart-empty{text-align:center;padding:3rem 1rem;color:#A9967E}
  #cart-panel .cart-empty-icon{font-size:3rem;margin-bottom:1rem}
  #cart-panel .cart-empty p{font-size:.9rem;line-height:1.6}
  #cart-panel .cart-item{display:flex;gap:1rem;padding:.9rem 0;border-bottom:1px solid #D9CEBC}
  #cart-panel .cart-item-icon{width:48px;height:48px;background:#EFE8D9;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;overflow:hidden;border-radius:2px}
  #cart-panel .cart-item-icon img{width:100%;height:100%;object-fit:cover}
  #cart-panel .cart-item-info{flex:1;min-width:0}
  #cart-panel .cart-item-name{font-size:.9rem;font-weight:500;color:#3B2A1A;margin-bottom:.2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  #cart-panel .cart-item-price{font-size:.82rem;color:#2D5016;font-weight:500}
  #cart-panel .cart-item-qty{display:flex;align-items:center;gap:.5rem;margin-top:.5rem}
  #cart-panel .qty-btn{width:24px;height:24px;border:1px solid #D9CEBC;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;color:#7A6550;transition:all .2s;flex-shrink:0}
  #cart-panel .qty-btn:hover{background:#2D5016;color:#fff;border-color:#2D5016}
  #cart-panel .qty-num{font-size:.85rem;font-weight:500;min-width:18px;text-align:center}
  #cart-panel .cart-remove{background:none;border:none;color:#A9967E;cursor:pointer;font-size:.75rem;margin-left:auto}
  #cart-panel .cart-remove:hover{color:#c0392b}
  #cart-panel #cart-bottom{padding:1.5rem;border-top:1px solid #D9CEBC}
  #cart-panel .cart-row{display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.6rem;color:#7A6550}
  #cart-panel .cart-row-total{display:flex;justify-content:space-between;font-size:1.05rem;font-weight:500;color:#3B2A1A;margin:1rem 0 1.2rem}
  #cart-panel .checkout-btn{width:100%;background:#2D5016;color:#fff;border:none;padding:.9rem;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;cursor:pointer;letter-spacing:.04em;transition:background .2s}
  #cart-panel .checkout-btn:hover{background:#4A7C23}

  /* Order success modal */
  #order-success{display:none;position:fixed;inset:0;background:rgba(42,31,18,.5);z-index:300;align-items:center;justify-content:center}
  #order-success.show{display:flex}
  #order-success .success-box{background:#F7F3EC;padding:3rem;max-width:420px;width:90%;text-align:center}
  #order-success .success-icon{font-size:3rem;margin-bottom:1rem}
  #order-success .success-title{font-family:'Cormorant Garamond',Georgia,serif;font-size:2rem;font-weight:300;color:#2D5016;margin-bottom:.6rem}
  #order-success .success-text{font-size:.9rem;color:#7A6550;margin-bottom:2rem;line-height:1.7}
  #order-success .success-close{background:#2D5016;color:#fff;border:none;padding:.8rem 2rem;font-family:'DM Sans',sans-serif;font-size:.9rem;cursor:pointer;width:100%;transition:background .2s}
  #order-success .success-close:hover{background:#4A7C23}

  /* Toast notifikasi */
  #toast{position:fixed;bottom:2rem;left:50%;transform:translateX(-50%) translateY(100px);background:#3B2A1A;color:#fff;padding:.7rem 1.4rem;font-size:.82rem;z-index:400;transition:transform .3s;white-space:nowrap;font-family:'DM Sans',sans-serif;border-radius:2px}
  #toast.show{transform:translateX(-50%) translateY(0)}

  /* Badge keranjang universal */
  .cart-badge{background:#C8883A;color:#fff;border-radius:50%;width:18px;height:18px;font-size:.65rem;display:inline-flex;align-items:center;justify-content:center;font-weight:500;vertical-align:middle}
</style>

<!-- ══ CART PANEL ══ -->
<div id="cart-overlay" onclick="closeCart()"></div>
<div id="cart-panel">
  <div class="cart-top">
    <h2>Keranjang</h2>
    <button class="cart-close" onclick="closeCart()" title="Tutup">✕</button>
  </div>
  <div class="cart-items" id="cart-items">
    <div class="cart-empty">
      <div class="cart-empty-icon">🛒</div>
      <p>Keranjangmu masih kosong.<br>Yuk pilih menu atau produk!</p>
    </div>
  </div>
  <div id="cart-bottom" style="display:none;">
    <div class="cart-row"><span>Subtotal</span><span id="cart-sub">Rp 0</span></div>
    <div class="cart-row"><span>Biaya layanan</span><span>Rp 2.000</span></div>
    <div class="cart-row-total"><span>Total</span><span id="cart-total">Rp 0</span></div>
    <button class="checkout-btn" onclick="checkout()">Konfirmasi Pesanan →</button>
  </div>
</div>

<!-- ══ ORDER SUCCESS MODAL ══ -->
<div id="order-success">
  <div class="success-box">
    <div class="success-icon">✅</div>
    <h2 class="success-title">Pesanan Diterima!</h2>
    <p class="success-text">Pesananmu sedang diproses. Kamu bisa pickup atau tunggu konfirmasi dari barista kami. Terima kasih sudah pilih Kafetani! ☕</p>
    <button class="success-close" onclick="closeSuccess()">Kembali Belanja</button>
  </div>
</div>

<!-- ══ TOAST ══ -->
<div id="toast"></div>
