<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin')  Kafetani</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --cream:#F7F3EC;--cream2:#EFE8D9;--brown:#3B2A1A;
  --green:#2D5016;--green2:#4A7C23;--amber:#C8883A;--amber-light:#F5ECD8;
  --text:#2A1F12;--text-mid:#7A6550;--text-light:#A9967E;--border:#D9CEBC;
  --ff-display:'Cormorant Garamond',serif;--ff-body:'DM Sans',sans-serif;
}
html,body{font-family:var(--ff-body);background:var(--cream);color:var(--text);min-height:100vh}
.icon-inline{width:1em;height:1em;vertical-align:-.15em;display:inline-block}
.admin-layout{display:grid;grid-template-columns:240px 1fr;min-height:100vh}
.admin-sidebar{background:var(--brown);color:#fff;padding:2rem;position:sticky;top:0;height:100vh;overflow-y:auto}
.admin-sidebar h2{font-family:var(--ff-display);font-size:1.5rem;margin-bottom:1.5rem;font-weight:300}
.admin-nav{display:flex;flex-direction:column;gap:.5rem}
.admin-nav a{color:rgba(255,255,255,.7);text-decoration:none;font-size:.9rem;padding:.55rem .7rem;transition:all .2s;border-left:2px solid transparent;display:block}
.admin-nav a:hover{color:#fff;background:rgba(255,255,255,.06)}
.admin-nav a.active{color:var(--amber);border-left-color:var(--amber);background:rgba(255,255,255,.05)}
.admin-nav hr{border:none;border-top:1px solid rgba(255,255,255,.15);margin:.75rem 0}
.admin-main{padding:3rem;background:var(--cream);overflow-x:hidden}
.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem}
.page-header h1{font-family:var(--ff-display);font-size:2.2rem;font-weight:300;color:var(--brown);margin:0}
.alert{padding:.85rem 1rem;margin-bottom:1.2rem;font-size:.85rem;border-left:3px solid}
.alert-ok{background:#EAF3DE;border-color:var(--green);color:#27500A}
.alert-err{background:#FCEBEB;border-color:#c0392b;color:#7b2d1e}
.btn-primary{background:var(--green);color:#fff;border:none;padding:.6rem 1.2rem;font-family:var(--ff-body);font-size:.85rem;cursor:pointer;transition:background .2s;text-decoration:none;display:inline-block}
.btn-primary:hover{background:var(--green2)}
.btn-edit{background:none;border:1px solid var(--green);color:var(--green);padding:.3rem .75rem;font-family:var(--ff-body);font-size:.78rem;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-edit:hover{background:var(--green);color:#fff}
.btn-danger{background:none;border:1px solid #c0392b;color:#c0392b;padding:.3rem .75rem;font-family:var(--ff-body);font-size:.78rem;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-danger:hover{background:#c0392b;color:#fff}
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
  <aside class="admin-sidebar">
    <h2>Kafetani Admin</h2>
    <nav class="admin-nav">
      <a href="{{ route('admin.dashboard') }}"     class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Dashboard</a>
      <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg> Produk</a>
      <a href="{{ route('admin.farmers.index') }}"  class="{{ request()->routeIs('admin.farmers*')  ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Petani</a>
      <a href="{{ route('admin.orders.index') }}"   class="{{ request()->routeIs('admin.orders*')   ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Pesanan</a>
      <a href="{{ route('admin.kasir') }}"          class="{{ request()->routeIs('admin.kasir*')    ? 'active' : '' }}"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg> Kasir POS</a>
      <hr>
      <a href="{{ route('home') }}">← Lihat Situs</a>
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
