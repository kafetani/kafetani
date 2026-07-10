@extends('layouts.app')
@section('title', 'Kafetani — Farm to Table')

@push('styles')
<meta name="description" content="Kafetani menghadirkan produk segar dari petani lokal langsung ke meja Anda.">
<link rel="stylesheet" href="{{ asset('home.css') }}">
@endpush

@section('content')

{{-- HERO --}}
<section class="hero">
    <div style="position:relative; max-width:700px;">
        <span class="hero-badge"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-.15em;display:inline-block"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75"/></svg> Langsung dari kebun</span>
        <h1>Kafetani<br><em>Farm to Table</em></h1>
        <p class="hero-sub">Nikmati cita rasa kopi & makanan segar yang ditanam petani lokal pilihan, disajikan langsung ke meja Anda.</p>
        <div class="hero-btns">
            <a href="{{ route('menu') }}" class="btn-hero-primary">Lihat Menu Kafe</a>
            <a href="{{ route('marketplace') }}" class="btn-hero-outline">Marketplace Petani</a>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="features">
    <span class="section-label">Keunggulan Kami</span>
    <h2 class="section-title">Mengapa Kafetani?</h2>
    <p class="section-sub">Kami percaya pada transparansi — dari kebun ke cangkir Anda.</p>
    <div class="features-grid">
        <div class="feature-card">
            <img src="{{ asset('about/bahan_segar.webp') }}" alt="Bahan Segar" class="feature-img">
            <div class="feature-body">
                <h3>Bahan Selalu Segar</h3>
                <p>Setiap bahan baku dipilih langsung dari petani lokal berpengalaman setiap harinya.</p>
            </div>
        </div>
        <div class="feature-card">
            <img src="{{ asset('about/petani_lokal.webp') }}" alt="Petani Lokal" class="feature-img">
            <div class="feature-body">
                <h3>Dukung Petani Lokal</h3>
                <p>Setiap pembelian Anda membantu petani lokal mendapatkan harga yang adil dan layak.</p>
            </div>
        </div>
        <div class="feature-card">
            <img src="{{ asset('about/pesan_online.webp') }}" alt="Pesan Online" class="feature-img">
            <div class="feature-body">
                <h3>Mudah Dipesan</h3>
                <p>Pesan produk segar dari marketplace kami dan dikirim langsung ke pintu Anda.</p>
            </div>
        </div>
        <div class="feature-card">
            <img src="{{ asset('about/bawa_pulang.webp') }}" alt="Bawa Pulang" class="feature-img">
            <div class="feature-body">
                <h3>Dine-In atau Bawa Pulang</h3>
                <p>Fleksibel sesuai kebutuhan Anda — makan di tempat atau nikmati di rumah.</p>
            </div>
        </div>
    </div>
</section>

{{-- PETANI STRIP --}}
<section class="farmers-strip">
    <span class="section-label">Mitra Kami</span>
    <h2 class="section-title" style="font-size:clamp(1.6rem,3vw,2.4rem);">Petani Pilihan Kafetani</h2>
    <p class="section-sub">Kenali para petani yang menanam dengan penuh cinta untuk Anda.</p>
    <div class="farmers-row">
        <div class="farmer-item">
            <img src="{{ asset('farmers/pak_budi.webp') }}" alt="Pak Budi">
            <div class="farmer-item-name">Pak Budi</div>
            <div class="farmer-item-loc">Gayo, Aceh</div>
        </div>
        <div class="farmer-item">
            <img src="{{ asset('farmers/bu_sari.webp') }}" alt="Bu Sari">
            <div class="farmer-item-name">Bu Sari</div>
            <div class="farmer-item-loc">Temanggung, Jateng</div>
        </div>
        <div class="farmer-item">
            <img src="{{ asset('farmers/pak_yusuf.webp') }}" alt="Pak Yusuf">
            <div class="farmer-item-name">Pak Yusuf</div>
            <div class="farmer-item-loc">Pangalengan, Jabar</div>
        </div>
        <div class="farmer-item">
            <img src="{{ asset('farmers/semua_petani.webp') }}" alt="Semua Petani">
            <div class="farmer-item-name">& Banyak Lagi</div>
            <div class="farmer-item-loc">Dari seluruh Indonesia</div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta">
    <h2>Siap Merasakan Perbedaannya?</h2>
    <p>Bergabung bersama ribuan pelanggan yang sudah menikmati cita rasa alami Kafetani.</p>
    <a href="{{ route('register') }}" class="btn-cta">Daftar Gratis Sekarang</a>
</section>

@endsection