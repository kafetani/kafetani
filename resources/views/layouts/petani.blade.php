<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Petani') &middot; Kafetani</title>
<link rel="icon" type="image/svg+xml" href="{{ asset_v('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('style-admin.css') }}">
@stack('styles')
</head>
<body>
<div class="admin-layout">
  <aside class="admin-sidebar">
    <h2>Kafetani Petani</h2>
    <nav class="admin-nav">
      <a href="{{ route('petani.dashboard') }}" class="{{ request()->routeIs('petani.dashboard') ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Dashboard</a>
      <a href="{{ route('petani.produk.index') }}" class="{{ request()->routeIs('petani.produk*') ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg> Produk Saya</a>
      <a href="{{ route('petani.profil') }}" class="{{ request()->routeIs('petani.profil') ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Profil Saya</a>
      <hr>
      <a href="{{ route('home') }}">← Lihat Situs</a>
      <a href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('petani-logout-form').submit();"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Logout</a>
      <form id="petani-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </nav>
  </aside>

  <main class="admin-main">
    @if(session('success'))
      <div class="alert alert-ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-err">{{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-err">
        @foreach($errors->all() as $err){{ $err }}<br>@endforeach
      </div>
    @endif

    @yield('content')
  </main>
</div>
@stack('scripts')
</body>
</html>
