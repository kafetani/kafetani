<nav class="main-nav">
    <a href="{{ url('/') }}" class="nav-logo">
        <img src="{{ asset('logo_v3.svg') }}" alt="Kafetani Logo" style="height:30px;">
    </a>
    <div class="nav-links">
        <a href="{{ url('/') }}"
           class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('menu') }}"
           class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}">Menu Kafe</a>
        <a href="{{ route('marketplace') }}"
           class="nav-link {{ request()->routeIs('marketplace') ? 'active' : '' }}">Marketplace</a>

        @auth
            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kasir')
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
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
    <button class="nav-cart" id="nav-cart-btn" onclick="typeof openCart === 'function' ? openCart() : null">
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
