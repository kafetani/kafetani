<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Kafetani  Farm to Table Cafe &amp; Market')</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --cream:#F7F3EC;--cream2:#EFE8D9;--brown:#3B2A1A;--brown2:#6B4C30;
  --green:#2D5016;--green2:#4A7C23;--green3:#7BAD45;--green-light:#EAF0DC;
  --amber:#C8883A;--amber-light:#F5ECD8;
  --text:#2A1F12;--text-mid:#7A6550;--text-light:#A9967E;--border:#D9CEBC;
  --ff-display:'Cormorant Garamond',serif;--ff-body:'DM Sans',sans-serif;
}
html{font-size:16px;scroll-behavior:smooth}
body{background:var(--cream);color:var(--text);font-family:var(--ff-body);font-weight:400;line-height:1.6;min-height:100vh}
.main-nav{position:fixed;top:0;left:0;right:0;z-index:100;background:var(--cream);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 2.5rem;height:60px}
.nav-logo{display:flex;align-items:center;text-decoration:none}
.nav-links{display:flex;gap:2rem;align-items:center}
.nav-link{font-size:.85rem;font-weight:300;color:var(--text-mid);letter-spacing:.04em;text-transform:uppercase;transition:color .2s;text-decoration:none;font-family:var(--ff-body)}
.nav-link:hover,.nav-link.active{color:var(--green)}
.nav-cart{display:flex;align-items:center;gap:.4rem;background:var(--green);color:#fff;border:none;padding:.45rem 1rem;font-family:var(--ff-body);font-size:.8rem;font-weight:500;cursor:pointer;letter-spacing:.04em;transition:background .2s}
.nav-cart:hover{background:var(--green2)}
.cart-badge{background:var(--amber);color:#fff;border-radius:50%;width:18px;height:18px;font-size:.65rem;display:inline-flex;align-items:center;justify-content:center;font-weight:500}
.page{padding-top:60px;min-height:100vh;animation:fadeUp .4s ease}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
/* Page Header */
.page-header{background:var(--green);color:#fff;padding:3.5rem 3.5rem 2.5rem;position:relative;overflow:hidden}
.page-header::after{content:'';position:absolute;right:-60px;top:-60px;width:240px;height:240px;border-radius:50%;border:1px solid rgba(255,255,255,.08)}
.page-header-label{font-size:.72rem;letter-spacing:.2em;text-transform:uppercase;opacity:.6;margin-bottom:.8rem}
.page-header-title{font-family:var(--ff-display);font-size:3rem;font-weight:300;line-height:1.1;margin-bottom:.6rem}
.page-header-sub{font-size:.9rem;opacity:.7;font-weight:300}
/* Filter Bar */
.filter-bar{background:#fff;border-bottom:1px solid var(--border);padding:0 3.5rem;display:flex;gap:0;overflow-x:auto}
.filter-tab{padding:.9rem 1.4rem;font-size:.82rem;cursor:pointer;color:var(--text-mid);border-bottom:2px solid transparent;white-space:nowrap;transition:all .2s;background:none;border-top:none;border-left:none;border-right:none;font-family:var(--ff-body);letter-spacing:.02em}
.filter-tab.active{color:var(--green);border-bottom-color:var(--green);font-weight:500}
/* Product Grid */
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.5rem;padding:2.5rem 3.5rem}
.product-card{background:#fff;border:1px solid var(--border);cursor:pointer;transition:transform .2s,box-shadow .2s;position:relative}
.product-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(45,80,22,.1)}
.product-thumb{height:150px;overflow:hidden;background:var(--cream2)}
.product-thumb img{width:100%;height:100%;object-fit:cover}
.product-body{padding:1rem}
.product-cat{font-size:.68rem;letter-spacing:.12em;text-transform:uppercase;color:var(--text-light);margin-bottom:.3rem}
.product-name{font-family:var(--ff-display);font-size:1.1rem;font-weight:400;color:var(--brown);margin-bottom:.2rem}
.product-desc{font-size:.78rem;color:var(--text-light);line-height:1.5;margin-bottom:.8rem}
.product-footer{display:flex;align-items:center;justify-content:space-between}
.product-price{font-size:.95rem;color:var(--green);font-weight:500}
.add-btn{background:var(--green);color:#fff;border:none;width:28px;height:28px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.2rem;transition:background .2s;flex-shrink:0}
.add-btn:hover{background:var(--green2)}
/* Marketplace */
.market-layout{display:grid;grid-template-columns:260px 1fr}
.market-sidebar{background:#fff;border-right:1px solid var(--border);padding:2rem;min-height:calc(100vh - 60px - 140px)}
.sidebar-title{font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:var(--text-light);margin-bottom:1rem}
.farmer-card{display:flex;align-items:center;gap:.8rem;padding:.8rem;border:1px solid transparent;cursor:pointer;transition:all .2s;margin-bottom:.5rem}
.farmer-card:hover,.farmer-card.active{background:var(--green-light);border-color:var(--border)}
.farmer-avatar{width:40px;height:40px;border-radius:50%;overflow:hidden;flex-shrink:0}
.farmer-avatar img{width:100%;height:100%;object-fit:cover}
.farmer-info-name{font-size:.9rem;font-weight:500;color:var(--brown)}
.farmer-info-loc{font-size:.75rem;color:var(--text-light)}
.market-products{padding:2rem 2.5rem}
.market-banner{background:var(--green);color:#fff;padding:1.5rem 2rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between}
.market-banner-text h3{font-family:var(--ff-display);font-size:1.5rem;font-weight:300;margin-bottom:.2rem}
.market-banner-text p{font-size:.82rem;opacity:.75;font-weight:300}
.market-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.2rem}
/* Cart */
#cart-overlay{position:fixed;inset:0;background:rgba(42,31,18,.45);z-index:200;opacity:0;pointer-events:none;transition:opacity .3s}
#cart-overlay.open{opacity:1;pointer-events:all}
#cart-panel{position:fixed;right:0;top:0;bottom:0;width:380px;background:var(--cream);z-index:201;transform:translateX(100%);transition:transform .3s ease;display:flex;flex-direction:column;border-left:1px solid var(--border);font-family:var(--ff-body)}
#cart-panel.open{transform:translateX(0)}
#cart-panel .cart-top{padding:1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
#cart-panel .cart-top h2{font-family:var(--ff-display);font-size:1.6rem;font-weight:300;color:var(--brown);margin:0}
#cart-panel .cart-close{background:none;border:none;font-size:1.4rem;cursor:pointer;color:var(--text-mid);padding:.2rem}
#cart-panel .cart-items{flex:1;overflow-y:auto;padding:1.2rem}
#cart-panel .cart-empty{text-align:center;padding:3rem 1rem;color:var(--text-light)}
#cart-panel .cart-empty-icon{font-size:3rem;margin-bottom:1rem}
#cart-panel .cart-item{display:flex;gap:1rem;padding:.9rem 0;border-bottom:1px solid var(--border)}
#cart-panel .cart-item-icon{width:48px;height:48px;background:var(--cream2);flex-shrink:0;overflow:hidden}
#cart-panel .cart-item-icon img{width:100%;height:100%;object-fit:cover}
#cart-panel .cart-item-info{flex:1}
#cart-panel .cart-item-name{font-size:.9rem;font-weight:500;color:var(--brown);margin-bottom:.2rem}
#cart-panel .cart-item-price{font-size:.82rem;color:var(--green)}
#cart-panel .cart-item-qty{display:flex;align-items:center;gap:.5rem;margin-top:.5rem}
#cart-panel .qty-btn{width:24px;height:24px;border:1px solid var(--border);background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;color:var(--text-mid);transition:all .2s}
#cart-panel .qty-btn:hover{background:var(--green);color:#fff;border-color:var(--green)}
#cart-panel .qty-num{font-size:.85rem;font-weight:500;min-width:18px;text-align:center}
#cart-panel .cart-remove{background:none;border:none;color:var(--text-light);cursor:pointer;font-size:.75rem;margin-left:auto}
#cart-panel #cart-bottom{padding:1.5rem;border-top:1px solid var(--border)}
#cart-panel .cart-row{display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.6rem;color:var(--text-mid)}
#cart-panel .cart-row-total{display:flex;justify-content:space-between;font-size:1.05rem;font-weight:500;color:var(--brown);margin:1rem 0 1.2rem}
#cart-panel .checkout-btn{width:100%;background:var(--green);color:#fff;border:none;padding:.9rem;font-family:var(--ff-body);font-size:.9rem;font-weight:500;cursor:pointer;letter-spacing:.04em;transition:background .2s}
#cart-panel .checkout-btn:hover{background:var(--green2)}
#order-success{display:none;position:fixed;inset:0;background:rgba(42,31,18,.5);z-index:300;align-items:center;justify-content:center}
#order-success.show{display:flex}
#order-success .success-box{background:var(--cream);padding:3rem;max-width:420px;width:90%;text-align:center}
#order-success .success-icon{font-size:3rem;margin-bottom:1rem}
#order-success .success-title{font-family:var(--ff-display);font-size:2rem;font-weight:300;color:var(--green);margin-bottom:.6rem}
#order-success .success-text{font-size:.9rem;color:var(--text-mid);margin-bottom:2rem;line-height:1.7}
#order-success .success-close{background:var(--green);color:#fff;border:none;padding:.8rem 2rem;font-family:var(--ff-body);font-size:.9rem;cursor:pointer;width:100%}
#toast{position:fixed;bottom:2rem;left:50%;transform:translateX(-50%) translateY(100px);background:var(--brown);color:#fff;padding:.7rem 1.4rem;font-size:.82rem;z-index:400;transition:transform .3s;white-space:nowrap;font-family:var(--ff-body)}
#toast.show{transform:translateX(-50%) translateY(0)}
/* Footer */
footer{background:var(--brown);color:#fff;padding:4rem 3.5rem 2rem;margin-top:2rem}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:4rem;margin-bottom:4rem}
.footer-logo{height:70px;margin-bottom:1.5rem;filter:brightness(0) invert(1)}
.footer-desc{font-size:.85rem;color:rgba(255,255,255,.6);line-height:1.8;margin-bottom:1.5rem}
.footer-title{font-family:var(--ff-display);font-size:1.1rem;font-weight:400;color:var(--amber);margin-bottom:1.5rem;letter-spacing:.05em}
.footer-links{list-style:none}
.footer-link{display:block;color:rgba(255,255,255,.7);text-decoration:none;font-size:.85rem;margin-bottom:.8rem;transition:color .2s}
.footer-link:hover{color:#fff}
.footer-contact{font-size:.85rem;color:rgba(255,255,255,.6);line-height:1.8;margin-bottom:.8rem}
.footer-bottom{padding-top:2rem;border-top:1px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;align-items:center;font-size:.75rem;color:rgba(255,255,255,.4)}
@media(max-width:968px){.footer-grid{grid-template-columns:1fr 1fr;gap:2rem}}
</style>
@stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="main-nav">
  <a href="{{ route('home') }}" class="nav-logo">
    <img src="{{ asset('logo_v3.svg') }}" alt="Kafetani Logo" style="height:30px;">
  </a>
  <div class="nav-links">
    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
    <a href="{{ url('/menu') }}" class="nav-link {{ request()->is('menu') ? 'active' : '' }}">Menu Kafe</a>
    <a href="{{ url('/marketplace') }}" class="nav-link {{ request()->is('marketplace') ? 'active' : '' }}">Marketplace</a>
    @auth
      @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
      @endif
      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;">Logout</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="nav-link">Login</a>
    @endauth
  </div>
  <button class="nav-cart" onclick="openCart()">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         style="vertical-align:middle;margin-right:5px">
      <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
      <line x1="3" y1="6" x2="21" y2="6"/>
      <path d="M16 10a4 4 0 01-8 0"/>
    </svg>
    Keranjang <span class="cart-badge" id="cart-badge">0</span>
  </button>
</nav>

{{-- Konten halaman --}}
@yield('content')

{{-- Cart Panel --}}
<div id="cart-overlay" onclick="closeCart()"></div>
<div id="cart-panel">
  <div class="cart-top">
    <h2>Keranjang</h2>
    <button class="cart-close" onclick="closeCart()">✕</button>
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

{{-- Order success modal --}}
<div id="order-success">
  <div class="success-box">
    <div class="success-icon">✅</div>
    <h2 class="success-title">Pesanan Diterima!</h2>
    <p class="success-text">Pesananmu sedang diproses. Terima kasih sudah pilih Kafetani! ☕</p>
    <button class="success-close" onclick="closeSuccess()">Kembali Belanja</button>
  </div>
</div>

<div id="toast"></div>

{{-- Footer --}}
<footer>
  <div class="footer-grid">
    <div>
      <img src="{{ asset('logo_footer.svg') }}" alt="Kafetani Logo" class="footer-logo">
      <p class="footer-desc">Kafetani menghadirkan kesegaran ladang langsung ke meja kamu. Kami percaya pada keadilan bagi petani dan kualitas terbaik bagi penikmat kopi.</p>
    </div>
    <div>
      <h4 class="footer-title">Navigasi</h4>
      <ul class="footer-links">
        <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
        <li><a href="{{ url('/menu') }}" class="footer-link">Menu Kafe</a></li>
        <li><a href="{{ url('/marketplace') }}" class="footer-link">Marketplace</a></li>
        <li><a href="{{ route('login') }}" class="footer-link">Admin Panel</a></li>
      </ul>
    </div>
    <div>
      <h4 class="footer-title">Bantuan</h4>
      <ul class="footer-links">
        <li><a href="{{ url('/cara-pesan') }}" class="footer-link">Cara Pesan</a></li>
        <li><a href="{{ url('/tentang-kami') }}" class="footer-link">Tentang Kami</a></li>
        <li><a href="{{ url('/syarat-ketentuan') }}" class="footer-link">Syarat &amp; Ketentuan</a></li>
        <li><a href="{{ url('/kebijakan-privasi') }}" class="footer-link">Kebijakan Privasi</a></li>
      </ul>
    </div>
    <div>
      <h4 class="footer-title">Hubungi Kami</h4>
      <p class="footer-contact">📍 Jl. Ladang Hijau No. 12, Bandung</p>
      <p class="footer-contact">📞 +62 812 3456 7890</p>
      <p class="footer-contact">✉️ halo@kafetani.com</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; {{ date('Y') }} Kafetani. Semua Hak Dilindungi.</p>
    <p>Dibuat dengan ❤️ untuk Petani Indonesia</p>
  </div>
</footer>

<script src="{{ asset('app.js') }}?v=1.1"></script>
@stack('scripts')
</body>
</html>
