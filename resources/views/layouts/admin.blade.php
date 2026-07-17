<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin')  Kafetani</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('style-admin.css') }}?v={{ @filemtime(public_path('style-admin.css')) ?: '1' }}">
@stack('styles')
</head>
<body>
<div class="admin-layout" id="adminLayout">

  {{-- Mobile topbar --}}
  <header class="admin-topbar">
    <button type="button" class="admin-burger" id="adminBurger" aria-label="Buka menu" aria-expanded="false" aria-controls="adminSidebar">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('admin.kasir') }}" class="admin-topbar-logo">
      <img src="{{ asset_v('logo_v3.svg') }}" alt="Kafetani Logo" style="height:26px;">
    </a>
  </header>

  {{-- Overlay (mobile) --}}
  <div class="admin-overlay" id="adminOverlay"></div>

  {{-- Sidebar --}}
  <aside class="admin-sidebar" id="adminSidebar">
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('admin.kasir') }}" class="admin-sidebar-logo">
      <img src="{{ asset_v('logo_v3.svg') }}" alt="Kafetani Logo" style="height:32px;">
    </a>
    <nav class="admin-nav">
      @if(auth()->user()->isAdmin())
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Dashboard
      </a>
      <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
        <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg> Produk
      </a>
      <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"/><line x1="7.5" y1="7.5" x2="7.51" y2="7.5"/></svg> Kategori
      </a>
      <a href="{{ route('admin.farmers.index') }}" class="{{ request()->routeIs('admin.farmers*') ? 'active' : '' }}">
        <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Petani
      </a>
      <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
        <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Pesanan
      </a>
      @endif
      <a href="{{ route('admin.kasir') }}" class="{{ request()->routeIs('admin.kasir*') ? 'active' : '' }}">
        <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg> Kasir POS
      </a>
      <hr>
      <a href="{{ route('home') }}">← Lihat Situs</a>
    </nav>
  </aside>

  {{-- Main Content --}}
  <main class="admin-main">

    @if(session('success'))
      <div class="alert alert-ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-err">{{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-err">
        @foreach($errors->all() as $err)
          {{ $err }}<br>
        @endforeach
      </div>
    @endif

    @yield('content')
  </main>

</div>
<script>
(function(){
  var burger  = document.getElementById('adminBurger');
  var sidebar = document.getElementById('adminSidebar');
  var overlay = document.getElementById('adminOverlay');

  function openSidebar(){
    sidebar.classList.add('is-open');
    overlay.classList.add('is-visible');
    burger.setAttribute('aria-expanded','true');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar(){
    sidebar.classList.remove('is-open');
    overlay.classList.remove('is-visible');
    burger.setAttribute('aria-expanded','false');
    document.body.style.overflow = '';
  }

  burger.addEventListener('click', function(){
    sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
  });
  overlay.addEventListener('click', closeSidebar);
  sidebar.querySelectorAll('a').forEach(function(link){
    link.addEventListener('click', closeSidebar);
  });
  window.addEventListener('resize', function(){
    if (window.innerWidth > 900) closeSidebar();
  });
})();
</script>
@stack('scripts')
</body>
</html>
