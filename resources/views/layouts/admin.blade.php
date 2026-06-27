<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — Kafetani</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --cream:#F7F3EC;--cream2:#EFE8D9;--brown:#3B2A1A;--brown2:#6B4C30;
  --green:#2D5016;--green2:#4A7C23;--amber:#C8883A;--amber-light:#F5ECD8;
  --text:#2A1F12;--text-mid:#7A6550;--text-light:#A9967E;--border:#D9CEBC;
  --ff-display:'Cormorant Garamond',serif;--ff-body:'DM Sans',sans-serif;
}
html,body{font-family:var(--ff-body);background:var(--cream);color:var(--text);min-height:100vh}
.admin-layout{display:grid;grid-template-columns:240px 1fr;min-height:100vh}
/* Sidebar */
.admin-sidebar{background:var(--brown);color:#fff;padding:2rem;position:sticky;top:0;height:100vh;overflow-y:auto}
.admin-sidebar h2{font-family:var(--ff-display);font-size:1.5rem;margin-bottom:1.5rem;font-weight:300}
.admin-nav{display:flex;flex-direction:column;gap:.5rem}
.admin-nav a{color:rgba(255,255,255,.7);text-decoration:none;font-size:.9rem;padding:.55rem .7rem;transition:all .2s;border-left:2px solid transparent}
.admin-nav a:hover{color:#fff;background:rgba(255,255,255,.06)}
.admin-nav a.active{color:var(--amber);border-left-color:var(--amber);background:rgba(255,255,255,.05)}
.admin-nav hr{border:none;border-top:1px solid rgba(255,255,255,.15);margin:.75rem 0}
/* Main */
.admin-main{padding:3rem;background:var(--cream);overflow-x:hidden}
.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem}
.page-header h1{font-family:var(--ff-display);font-size:2.2rem;font-weight:300;color:var(--brown);margin:0}
/* Alerts */
.alert{padding:.85rem 1rem;margin-bottom:1.2rem;font-size:.85rem;border-left:3px solid}
.alert-ok{background:#EAF3DE;border-color:var(--green);color:#27500A}
.alert-err{background:#FCEBEB;border-color:#c0392b;color:#7b2d1e}
/* Buttons */
.btn-primary{background:var(--green);color:#fff;border:none;padding:.6rem 1.2rem;font-family:var(--ff-body);font-size:.85rem;cursor:pointer;transition:background .2s;text-decoration:none;display:inline-block}
.btn-primary:hover{background:var(--green2)}
.btn-edit{background:none;border:1px solid var(--green);color:var(--green);padding:.3rem .75rem;font-family:var(--ff-body);font-size:.78rem;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-edit:hover{background:var(--green);color:#fff}
.btn-danger{background:none;border:1px solid #c0392b;color:#c0392b;padding:.3rem .75rem;font-family:var(--ff-body);font-size:.78rem;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-danger:hover{background:#c0392b;color:#fff}
/* Table */
.data-table{width:100%;border-collapse:collapse;background:#fff}
.data-table th{padding:.85rem 1rem;text-align:left;font-size:.8rem;font-weight:500;color:var(--text-mid);border-bottom:1px solid var(--border);white-space:nowrap}
.data-table td{padding:.8rem 1rem;border-bottom:.5px solid var(--border);font-size:.85rem;color:var(--text);vertical-align:middle}
.data-table tr:hover td{background:#fafaf7}
.empty-row td{text-align:center;padding:3rem;color:var(--text-light)}
</style>
@stack('styles')
</head>
<body>
<div class="admin-layout">

  {{-- Sidebar --}}
  <aside class="admin-sidebar">
    <h2>Kafetani Admin</h2>
    <nav class="admin-nav">
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        📊 Dashboard
      </a>
      <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
        🛍 Produk
      </a>
      <a href="{{ route('admin.farmers.index') }}" class="{{ request()->routeIs('admin.farmers*') ? 'active' : '' }}">
        👨‍🌾 Petani
      </a>
      <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
        📋 Pesanan
      </a>
      <a href="{{ route('admin.kasir') }}" class="{{ request()->routeIs('admin.kasir*') ? 'active' : '' }}">
        🖥 Kasir POS
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
@stack('scripts')
</body>
</html>
