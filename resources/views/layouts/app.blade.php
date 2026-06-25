<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kafetani') - Farm to Table</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/marketplace.css') }}">
    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="main-nav">
        <a href="{{ url('/') }}" class="nav-logo">
            <img src="{{ asset('assets/img/logo_v3.svg') }}" alt="Kafetani Logo" style="height:30px;">
        </a>
        <div class="nav-links">
            <a href="{{ url('/') }}"
               class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('menu.index') }}"
               class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">Menu Kafe</a>
            <a href="{{ route('marketplace.index') }}"
               class="nav-link {{ request()->routeIs('marketplace.*') ? 'active' : '' }}">Marketplace</a>

            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
                @endif
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="nav-link">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
            @endauth
        </div>
        <button class="nav-cart" onclick="openCart()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 style="vertical-align:middle; margin-right:5px">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            Keranjang <span class="cart-badge" id="cart-badge">0</span>
        </button>
    </nav>

    {{-- Konten Halaman --}}
    @yield('content')

    {{-- Footer --}}
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <img src="{{ asset('assets/img/logo_footer.svg') }}" alt="Kafetani Logo" class="footer-logo">
                <p class="footer-desc">Kafetani menghadirkan kesegaran ladang langsung ke meja kamu.</p>
            </div>
            <div class="footer-col">
                <h4 class="footer-title">Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}" class="footer-link">Beranda</a></li>
                    <li><a href="{{ route('menu.index') }}" class="footer-link">Menu Kafe</a></li>
                    <li><a href="{{ route('marketplace.index') }}" class="footer-link">Marketplace</a></li>
                </ul>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
