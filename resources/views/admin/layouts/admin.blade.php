<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Kafetani</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style_dashboard.css') }}">
    @stack('styles')
</head>
<body>

<div class="admin-layout" style="display:grid; grid-template-columns:240px 1fr; min-height:100vh;">

    {{-- Sidebar --}}
    <aside style="background:var(--brown); color:#fff; padding:2rem;">
        <h2 style="font-family:var(--ff-display); font-size:1.5rem; margin-bottom:1rem;">Kafetani Admin</h2>
        <nav style="display:flex; flex-direction:column; gap:.8rem;">

            @php
                $navItems = [
                    'dashboard' => ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
                    'products'  => ['route' => 'admin.products.index', 'label' => 'Produk'],
                    'farmers'   => ['route' => 'admin.farmers.index', 'label' => 'Petani'],
                    'orders'    => ['route' => 'admin.orders.index', 'label' => 'Pesanan'],
                ];
            @endphp

            @foreach ($navItems as $key => $item)
                @php $isActive = (request()->routeIs('admin.' . $key . '*')); @endphp
                <a href="{{ route($item['route']) }}"
                   style="color: {{ $isActive ? 'var(--amber)' : '#fff' }};
                          text-decoration: none;
                          font-size: .9rem;
                          {{ $isActive ? '' : 'opacity:.7;' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <hr style="opacity:.2; margin:1rem 0;">
            <a href="{{ url('/') }}" style="color:#fff; text-decoration:none; font-size:.9rem; opacity:.7;">
                ← Lihat Situs
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main style="padding:3rem; background:var(--cream);">
        @yield('content')
    </main>

</div>

@stack('scripts')
</body>
</html>
