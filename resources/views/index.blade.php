@extends('layouts.app')
@section('title', 'Kafetani — Farm to Table Cafe & Market')

@push('styles')
<style>
/* Hero */
.hero{display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 60px);background:var(--green);align-items:center}
.hero-left{padding:5rem 4rem;color:#fff}
.hero-tag{font-size:.72rem;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:1.5rem}
.hero-title{font-family:var(--ff-display);font-size:4.5rem;font-weight:300;line-height:1.05;color:#fff;margin-bottom:1.5rem}
.hero-title em{font-style:italic;color:var(--amber)}
.hero-desc{font-size:1rem;color:rgba(255,255,255,.75);line-height:1.8;max-width:420px;margin-bottom:2.5rem;font-weight:300}
.hero-actions{display:flex;gap:1rem;flex-wrap:wrap}
.btn-primary{background:#fff;color:var(--green);border:none;padding:.75rem 1.8rem;font-family:var(--ff-body);font-size:.9rem;font-weight:500;cursor:pointer;text-decoration:none;display:inline-block;transition:all .2s;letter-spacing:.04em}
.btn-primary:hover{background:var(--amber);color:#fff}
.btn-outline{background:transparent;color:#fff;border:1px solid rgba(255,255,255,.4);padding:.75rem 1.8rem;font-family:var(--ff-body);font-size:.9rem;font-weight:300;cursor:pointer;text-decoration:none;display:inline-block;transition:all .2s;letter-spacing:.04em}
.btn-outline:hover{border-color:#fff;background:rgba(255,255,255,.08)}
/* Hero Right */
.hero-right{height:100%;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center}
.hero-pattern{position:absolute;inset:0;width:100%;height:100%;opacity:.12}
.hero-visual{position:relative;z-index:2;text-align:center;padding:2rem}
.hero-circle{margin-bottom:1.5rem}
.hero-circle-icon{width:140px;height:140px;border-radius:50%;margin:0 auto .6rem;overflow:hidden;border:2px solid rgba(255,255,255,.3)}
.hero-circle-icon img{width:100%;height:100%;object-fit:cover}
.hero-circle-label{font-family:var(--ff-display);font-size:1.2rem;color:#fff;font-weight:300}
.hero-pills{display:flex;flex-direction:column;gap:.8rem}
.hero-pill{border:1px solid rgba(255,255,255,.3);color:#fff;padding:.7rem 2rem;font-size:.85rem;letter-spacing:.04em;font-family:var(--ff-body);font-weight:300;border-radius:0;position:relative;overflow:hidden;height:52px;min-width:180px;display:flex;align-items:center;justify-content:center}
.hero-pill img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.5)}
.hero-pill span{position:relative;z-index:2}
/* Stats */
.home-stats{display:grid;grid-template-columns:repeat(3,1fr);background:var(--cream2);border-bottom:1px solid var(--border)}
.stat{padding:2rem;text-align:center;border-right:1px solid var(--border)}
.stat:last-child{border-right:none}
.stat-num{font-family:var(--ff-display);font-size:2.6rem;font-weight:300;color:var(--green);display:block;margin-bottom:.3rem}
.stat-label{font-size:.8rem;color:var(--text-light);letter-spacing:.06em;text-transform:uppercase}
/* Featured Section */
.home-section{padding:4rem 3.5rem}
.section-header{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:2rem}
.section-title{font-family:var(--ff-display);font-size:2rem;font-weight:300;color:var(--brown)}
.section-link{font-size:.82rem;color:var(--green);text-decoration:none;letter-spacing:.04em}
.section-link:hover{text-decoration:underline}
.featured-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
.feat-card{background:#fff;border:1px solid var(--border);cursor:pointer;transition:all .2s;overflow:hidden}
.feat-card:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(45,80,22,.12)}
.feat-thumb{height:180px;overflow:hidden}
.feat-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.feat-card:hover .feat-thumb img{transform:scale(1.04)}
.feat-body{padding:1.2rem}
.feat-tag{font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--text-light);margin-bottom:.5rem}
.feat-name{font-family:var(--ff-display);font-size:1.3rem;font-weight:400;color:var(--brown);margin-bottom:.5rem}
.feat-price{font-size:.88rem;color:var(--green);font-weight:500}
/* About band */
.about-band{background:var(--green);padding:4rem 3.5rem}
.about-band-grid{display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center}
.about-band-label{font-size:.72rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:.8rem}
.about-band-title{font-family:var(--ff-display);font-size:2.2rem;font-weight:300;color:#fff;margin-bottom:1rem;line-height:1.2}
.about-band-desc{color:rgba(255,255,255,.75);font-size:.9rem;line-height:1.8;font-weight:300}
.about-cards{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.about-card{position:relative;overflow:hidden;padding:2rem 1.5rem;min-height:150px;display:flex;flex-direction:column;justify-content:flex-end;border:1px solid rgba(255,255,255,.1)}
.about-card img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.4)}
.about-card-content{position:relative;z-index:2}
.about-card-title{font-family:var(--ff-display);font-size:1.1rem;color:#fff;margin-bottom:.2rem}
.about-card-sub{font-size:.75rem;color:rgba(255,255,255,.8);line-height:1.5}
@media(max-width:900px){.hero{grid-template-columns:1fr}.hero-right{display:none}.featured-grid{grid-template-columns:1fr 1fr}.about-band-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="page" id="page-home">

  {{-- Hero --}}
  <div class="hero">
    <div class="hero-left">
      <div class="hero-tag">Farm to Table · Sejak Panen</div>
      <h1 class="hero-title">Dari <em>ladang</em><br>ke cangkirmu</h1>
      <p class="hero-desc">Kafetani menghubungkan petani lokal langsung ke meja kamu — kopi, bakeri, dan bahan segar pilihan tanpa perantara.</p>
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
        <p class="about-band-desc">Setiap biji kopi dan butiran gula aren yang kamu nikmati berasal dari petani lokal yang sudah kami kenal namanya. Kafetani bukan sekadar kafe — ini adalah etalase langsung dari ladang ke cangkir.</p>
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
