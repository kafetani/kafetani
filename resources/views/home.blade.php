@extends('layouts.app')
@section('title', 'Kafetani — Farm to Table')

@push('styles')
<meta name="description" content="Kafetani menghadirkan produk segar dari petani lokal langsung ke meja Anda.">
<style>
    /* ─── Hero ─────────────────────────────────────────── */
    .hero {
        min-height: 92vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: linear-gradient(160deg, var(--cream) 0%, var(--cream2) 60%, #e8dcc8 100%);
        padding: calc(60px + 7rem) 2rem 5rem;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%232D5016' fill-opacity='0.04'%3E%3Cpath d='M40 0v80M0 40h80'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .hero-badge {
        display: inline-block;
        font-family: var(--ff-display);
        font-size: .72rem;
        letter-spacing: .25em;
        text-transform: uppercase;
        color: var(--amber);
        border: 1px solid var(--amber);
        padding: .32rem 1.1rem;
        border-radius: 30px;
        margin-bottom: 1.8rem;
    }
    .hero h1 {
        font-family: var(--ff-display);
        font-size: clamp(3rem, 8vw, 6rem);
        font-weight: 300;
        color: var(--brown);
        line-height: 1.05;
        margin-bottom: 1.5rem;
        position: relative;
    }
    .hero h1 em {
        font-style: italic;
        color: var(--green);
    }
    .hero-sub {
        font-size: 1.05rem;
        color: var(--text-mid);
        max-width: 460px;
        margin: 0 auto 2.8rem;
        line-height: 1.75;
    }
    .hero-btns {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-hero-primary {
        background: var(--green);
        color: #fff;
        padding: .9rem 2.4rem;
        font-family: var(--ff-body);
        font-size: .88rem;
        letter-spacing: .06em;
        text-decoration: none;
        border-radius: 2px;
        transition: background .2s, transform .18s;
    }
    .btn-hero-primary:hover {
        background: var(--green2);
        transform: translateY(-2px);
        color: #fff;
        text-decoration: none;
    }
    .btn-hero-outline {
        border: 1.5px solid var(--brown);
        color: var(--brown);
        padding: .9rem 2.4rem;
        font-size: .88rem;
        letter-spacing: .06em;
        text-decoration: none;
        border-radius: 2px;
        transition: all .2s;
    }
    .btn-hero-outline:hover {
        background: var(--brown);
        color: #fff;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* ─── Features section ────────────────────────────── */
    .features {
        padding: 6rem 3rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .section-label {
        display: block;
        font-family: var(--ff-display);
        font-size: .72rem;
        letter-spacing: .22em;
        text-transform: uppercase;
        color: var(--amber);
        text-align: center;
        margin-bottom: .7rem;
    }
    .section-title {
        font-family: var(--ff-display);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 300;
        color: var(--brown);
        text-align: center;
        margin-bottom: .5rem;
    }
    .section-sub {
        text-align: center;
        color: var(--text-mid);
        font-size: .95rem;
        margin-bottom: 3.5rem;
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.8rem;
    }
    .feature-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 3px;
        overflow: hidden;
        transition: transform .22s, box-shadow .22s;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 40px rgba(42,31,18,.1);
    }
    .feature-img {
        width: 100%;
        height: 170px;
        object-fit: cover;
    }
    .feature-body { padding: 1.4rem; }
    .feature-body h3 {
        font-family: var(--ff-display);
        font-size: 1.3rem;
        font-weight: 400;
        color: var(--brown);
        margin-bottom: .4rem;
    }
    .feature-body p {
        font-size: .85rem;
        color: var(--text-mid);
        line-height: 1.65;
    }

    /* ─── Petani strip ────────────────────────────────── */
    .farmers-strip {
        background: var(--cream2);
        padding: 4rem 3rem;
        text-align: center;
    }
    .farmers-row {
        display: flex;
        justify-content: center;
        gap: 2.5rem;
        flex-wrap: wrap;
        margin-top: 2.5rem;
    }
    .farmer-item { text-align: center; }
    .farmer-item img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 4px 16px rgba(42,31,18,.12);
        margin: 0 auto .7rem;
    }
    .farmer-item-name {
        font-family: var(--ff-display);
        font-size: 1rem;
        font-weight: 400;
        color: var(--brown);
    }
    .farmer-item-loc {
        font-size: .78rem;
        color: var(--text-light);
        margin-top: .1rem;
    }

    /* ─── CTA section ─────────────────────────────────── */
    .cta {
        background: var(--brown);
        color: #fff;
        text-align: center;
        padding: 6rem 2rem;
    }
    .cta h2 {
        font-family: var(--ff-display);
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 300;
        margin-bottom: .8rem;
    }
    .cta p {
        opacity: .72;
        font-size: .95rem;
        max-width: 460px;
        margin: 0 auto 2.2rem;
        line-height: 1.7;
    }
    .btn-cta {
        display: inline-block;
        background: var(--amber);
        color: #fff;
        padding: 1rem 2.8rem;
        font-family: var(--ff-body);
        font-size: .9rem;
        letter-spacing: .06em;
        border-radius: 2px;
        text-decoration: none;
        transition: background .2s, transform .18s;
    }
    .btn-cta:hover {
        background: #b37330;
        color: #fff;
        transform: translateY(-2px);
        text-decoration: none;
    }
</style>
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