<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Menambahkan Meta Tag CSRF Token untuk Keamanan Request Checkout -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Kafetani  Farm to Table Cafe &amp; Market')</title>
<link rel="icon" type="image/svg+xml" href="{{ asset_v('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset_v('style-app.css') }}">
<!-- Midtrans Snap.js -->
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="main-nav">
  <a href="{{ route('home') }}" class="nav-logo">
    <img src="{{ asset_v('logo_v3.svg') }}" alt="Kafetani Logo" style="height:30px;">
  </a>

  <button class="nav-toggle" id="nav-toggle" onclick="toggleNavMenu()" aria-label="Buka menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>

  <div class="nav-links" id="nav-links">
    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
    <a href="{{ url('/menu') }}" class="nav-link {{ request()->is('menu') ? 'active' : '' }}">Menu Kafe</a>
    <a href="{{ url('/marketplace') }}" class="nav-link {{ request()->is('marketplace') ? 'active' : '' }}">Marketplace</a>
    @auth
      @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
      @endif
      @if(auth()->user()->isKasirOrAdmin())
        <a href="{{ route('admin.kasir') }}" class="nav-link {{ request()->routeIs('admin.kasir*') ? 'active' : '' }}">Kasir POS</a>
      @endif
      <a href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('nav-logout-form').submit();"
         class="nav-link">Logout</a>
      <form id="nav-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    @else
      <a href="{{ route('login') }}" class="nav-link">Login</a>
    @endauth
  </div>

  <button class="nav-cart nav-cart-mobile" onclick="openCart()">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         style="vertical-align:middle;margin-right:5px">
      <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
      <line x1="3" y1="6" x2="21" y2="6"/>
      <path d="M16 10a4 4 0 01-8 0"/>
    </svg>
    Keranjang <span class="cart-badge" id="cart-badge-mobile">0</span>
  </button>

  <button class="nav-cart nav-cart-desktop" onclick="openCart()">
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

<script>
  function toggleNavMenu(){
    var links = document.getElementById('nav-links');
    var toggle = document.getElementById('nav-toggle');
    var open = links.classList.toggle('open');
    toggle.classList.toggle('open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  // Keep the two cart badges (desktop/mobile) in sync
  (function(){
    var target = document.getElementById('cart-badge');
    var mirror = document.getElementById('cart-badge-mobile');
    if(!target || !mirror) return;
    var mo = new MutationObserver(function(){ mirror.textContent = target.textContent; });
    mo.observe(target, {childList:true, characterData:true, subtree:true});
  })();
</script>

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
      <div class="cart-empty-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="56" height="56"
             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
      </div>
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
    <div class="success-icon">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="56" height="56"
           fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
    </div>
    <h2 class="success-title">Pesanan Diterima!</h2>
    <p class="success-text">Pesananmu sedang diproses. Terima kasih sudah pilih Kafetani! <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></p>
    <button class="success-close" onclick="closeSuccess()">Kembali Belanja</button>
  </div>
</div>

<div id="toast"></div>

{{-- Footer --}}
<footer>
  <div class="footer-grid">
    <div>
      <img src="{{ asset_v('logo_footer.svg') }}" alt="Kafetani Logo" class="footer-logo">
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
      <p class="footer-contact"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Jl. Ladang Hijau No. 12, Bandung</p>
      <p class="footer-contact"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> +62 812 3456 7890</p>
      <p class="footer-contact"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> halo@kafetani.com</p>
    </div>
  </div>
  <div class="footer-academic">
    <p>Nama Kelompok &amp; NIM:</p>
    <p>1. Hamadah Hilmi – 20240801265</p>
    <p>2. Muhammad Riyan Hardiono – 20240801112</p>
    <p>3. Rafi Ahmadinejad Al Farisi – 20240801285</p><br>
    <p>Dosen Pengampu MK: Dewi Setiowati, A.Md., S.Pd., M.Tr.Kom.</p>
    <p>Kelas: KH001</p>
    <p>Tahun Akademik: 2025/2026 Genap</p>
  </div>
  <div class="footer-bottom">
    <p>&copy; {{ date('Y') }} Kafetani. Semua Hak Dilindungi.</p>
    <p>Dibuat dengan <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> untuk Petani Indonesia</p>
  </div>
</footer>

<script src="{{ asset('app.js') }}"></script>
@stack('scripts')
</body>
</html>