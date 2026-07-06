@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')

<div class="page" id="page-market">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-label">Kafetani · Marketplace</div>
        <h1 class="page-header-title">Marketplace Petani</h1>
        <p class="page-header-sub">Beli langsung dari petani lokal — biji kopi, gula aren, dan produk segar pilihan</p>
    </div>

    {{-- Layout: Sidebar + Konten --}}
    <div class="market-layout">

        {{-- Sidebar Petani Mitra --}}
        <aside class="market-sidebar">
            <div class="sidebar-title">Petani Mitra</div>

            @foreach ($farmers as $farmer)
                <div class="farmer-card {{ $farmer['active'] ? 'active' : '' }}">
                    <div class="farmer-avatar">
                        <img src="{{ asset('assets/img/farmers/' . $farmer['img']) }}"
                             alt="{{ $farmer['name'] }}">
                    </div>
                    <div>
                        <div class="farmer-info-name">{{ $farmer['name'] }}</div>
                        <div class="farmer-info-loc">{{ $farmer['loc'] }}</div>
                    </div>
                </div>
            @endforeach

        </aside>

        {{-- Konten Produk --}}
        <div class="market-products">

            {{-- Banner --}}
            <div class="market-banner">
                <div class="market-banner-text">
                    <h3>Langsung dari Kebun</h3>
                    <p>Setiap produk dikirim segar, tanpa perantara</p>
                </div>
                <div class="market-banner-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75"/></svg></div>
            </div>

            {{-- Grid Produk --}}
            <div class="market-grid">

                @forelse ($products as $product)
                    <div class="product-card">
                        <div class="product-thumb green">
                            <img src="{{ asset('assets/img/products/' . $product->gambar) }}"
                                 alt="{{ $product->nama_produk }}">
                        </div>
                        <div class="product-body">
                            <div class="product-cat">{{ $product->petani }}</div>
                            <div class="product-name">{{ $product->nama_produk }}</div>
                            <p class="product-desc">{{ $product->deskripsi }}</p>
                            <div class="product-footer">
                                <span class="product-price">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </span>
                                <button class="add-btn"
                                    data-id="{{ $product->id_product }}"
                                    data-name="{{ $product->nama_produk }}"
                                    data-price="{{ $product->harga }}"
                                    data-image="{{ $product->gambar }}">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="grid-column:1/-1; text-align:center; color:var(--text-light); padding:4rem;">
                        Produk belum tersedia.
                    </p>
                @endforelse

            </div>
        </div>
    </div>
</div>

@endsection
