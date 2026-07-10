@extends('layouts.app')
@section('title', 'Kafetani  Farm to Table Cafe & Market')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-home.css') }}">
@endpush

@section('content')
<div class="page" id="page-home">

  {{-- Hero --}}
  <div class="hero">
    <div class="hero-left">
      <div class="hero-tag">Farm to Table · Sejak Panen</div>
      <h1 class="hero-title">Dari <em>ladang</em><br>ke cangkirmu</h1>
      <p class="hero-desc">Kafetani menghubungkan petani lokal langsung ke meja kamu  kopi, bakeri, dan bahan segar pilihan tanpa perantara.</p>
      <div class="hero-actions">
        <a href="{{ route('menu') }}" class="btn-primary">Pesan Sekarang</a>
        <a href="{{ route('marketplace') }}" class="btn-outline">Lihat Marketplace</a>
      </div>
    </div>
    <div class="hero-right">
      <svg class="hero-pattern" viewBox="0 0 500 600" fill="none">
        <circle cx="250" cy="300" r="200" stroke="white" stroke-width="1"/>
        <circle cx="250" cy="300" r="150" stroke="white" stroke-width="0.5"/>
        <circle cx="250" cy="300" r="100" stroke="white" stroke-width="0.5"/>
        <line x1="50" y1="300" x2="450" y2="300" stroke="white" stroke-width="0.5"/>
        <line x1="250" y1="100" x2="250" y2="500" stroke="white" stroke-width="0.5"/>
        <line x1="109" y1="159" x2="391" y2="441" stroke="white" stroke-width="0.4"/>
        <line x1="391" y1="159" x2="109" y2="441" stroke="white" stroke-width="0.4"/>
      </svg>
      <div class="hero-visual">
        <div class="hero-circle">
          <div class="hero-circle-icon">
            <img src="{{ asset('products/kopi_lokal.webp') }}" alt="Kopi Lokal">
          </div>
          <div class="hero-circle-label">Kopi Lokal</div>
        </div>
        <div class="hero-pills">
          <div class="hero-pill"><img src="{{ asset('products/arabica_gayo.webp') }}" alt=""><span>Arabica Gayo</span></div>
          <div class="hero-pill"><img src="{{ asset('products/gula_aren.webp') }}" alt=""><span>Gula Aren</span></div>
          <div class="hero-pill"><img src="{{ asset('products/bakeri_segar.webp') }}" alt=""><span>Bakeri Segar</span></div>
        </div>
      </div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="home-stats">
    <div class="stat"><span class="stat-num">12+</span><div class="stat-label">Petani Mitra</div></div>
    <div class="stat"><span class="stat-num">38</span><div class="stat-label">Produk Tersedia</div></div>
    <div class="stat"><span class="stat-num">2 Kota</span><div class="stat-label">Jangkauan Pengiriman</div></div>
  </div>

  {{-- Featured --}}
  <div class="home-section">
    <div class="section-header">
      <h2 class="section-title">Pilihan Unggulan</h2>
      <a href="{{ route('menu') }}" class="section-link">Lihat semua menu →</a>
    </div>
    <div class="featured-grid">
      <div class="feat-card" onclick="location.href='{{ route('menu') }}'">
        <div class="feat-thumb">
          <img src="{{ asset('products/kopi_susu_gula_aren.webp') }}" alt="Kopi Susu Gula Aren">
        </div>
        <div class="feat-body">
          <div class="feat-tag">Menu Kafe</div>
          <div class="feat-name">Kopi Susu Gula Aren</div>
          <div class="feat-price">Rp 32.000</div>
        </div>
      </div>
      <div class="feat-card" onclick="location.href='{{ route('menu') }}'">
        <div class="feat-thumb">
          <img src="{{ asset('products/croissant_butter.webp') }}" alt="Croissant Butter">
        </div>
        <div class="feat-body">
          <div class="feat-tag">Bakeri</div>
          <div class="feat-name">Croissant Butter</div>
          <div class="feat-price">Rp 22.000</div>
        </div>
      </div>
      <div class="feat-card" onclick="location.href='{{ route('marketplace') }}'">
        <div class="feat-thumb">
          <img src="{{ asset('products/biji_kopi_arabica_gayo.webp') }}" alt="Biji Kopi Arabica">
        </div>
        <div class="feat-body">
          <div class="feat-tag">Produk Petani</div>
          <div class="feat-name">Biji Kopi Arabica Gayo</div>
          <div class="feat-price">Rp 85.000 / 250g</div>
        </div>
      </div>
    </div>
  </div>

  {{-- About band --}}
  <div class="about-band">
    <div class="about-band-grid">
      <div>
        <div class="about-band-label">Tentang Kafetani</div>
        <h2 class="about-band-title">Kafe yang terhubung<br>langsung ke kebun</h2>
        <p class="about-band-desc">Setiap biji kopi dan butiran gula aren yang kamu nikmati berasal dari petani lokal yang sudah kami kenal namanya. Kafetani bukan sekadar kafe  ini adalah etalase langsung dari ladang ke cangkir.</p>
      </div>
      <div class="about-cards">
        <div class="about-card">
          <img src="{{ asset('about/bahan_segar.webp') }}" alt="">
          <div class="about-card-content">
            <div class="about-card-title">Bahan Segar</div>
            <div class="about-card-sub">Langsung dari petani mitra tanpa rantai distribusi panjang</div>
          </div>
        </div>
        <div class="about-card">
          <img src="{{ asset('about/petani_lokal.webp') }}" alt="">
          <div class="about-card-content">
            <div class="about-card-title">Petani Lokal</div>
            <div class="about-card-sub">Mendukung penghasilan petani Indonesia secara langsung</div>
          </div>
        </div>
        <div class="about-card">
          <img src="{{ asset('about/pesan_online.webp') }}" alt="">
          <div class="about-card-content">
            <div class="about-card-title">Pesan Online</div>
            <div class="about-card-sub">Order dari web, pickup atau dine-in sesuai preferensi</div>
          </div>
        </div>
        <div class="about-card">
          <img src="{{ asset('about/bawa_pulang.webp') }}" alt="">
          <div class="about-card-content">
            <div class="about-card-title">Bawa Pulang</div>
            <div class="about-card-sub">Beli bahan baku segar untuk diolah sendiri di rumah</div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
