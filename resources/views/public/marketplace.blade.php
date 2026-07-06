@extends('layouts.app')
@section('title', 'Marketplace Petani  Kafetani')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const farmerCards = document.querySelectorAll('.farmer-card');
  const prodCards   = document.querySelectorAll('.product-card[data-petani]');

  farmerCards.forEach(fc => {
    fc.addEventListener('click', function () {
      farmerCards.forEach(f => f.classList.remove('active'));
      this.classList.add('active');
      const target = this.dataset.farmer;
      prodCards.forEach(card => {
        card.style.display = (target === 'Semua Petani' || card.dataset.petani.startsWith(target))
          ? '' : 'none';
      });
    });
  });
});
</script>
@endpush

@section('content')
<div class="page" id="page-market">

  <div class="page-header">
    <div class="page-header-label">Kafetani · Marketplace</div>
    <h1 class="page-header-title">Marketplace Petani</h1>
    <p class="page-header-sub">Beli langsung dari petani lokal  biji kopi, gula aren, dan produk segar pilihan</p>
  </div>

  <div class="market-layout">

    {{-- Sidebar Petani --}}
    <aside class="market-sidebar">
      <div class="sidebar-title">Petani Mitra</div>

      <div class="farmer-card active" data-farmer="Semua Petani">
        <div class="farmer-avatar" style="background:var(--green);display:flex;align-items:center;justify-content:center;color:#fff;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75"/></svg></div>
        <div>
          <div class="farmer-info-name">Semua Petani</div>
          <div class="farmer-info-loc">Semua Wilayah</div>
        </div>
      </div>

      @foreach($farmers as $farmer)
        <div class="farmer-card" data-farmer="{{ $farmer->name }}">
          <div class="farmer-avatar">
            @if($farmer->avatar)
              <img src="{{ asset('farmers/' . $farmer->avatar) }}" alt="{{ $farmer->name }}">
            @else
              <div style="width:100%;height:100%;background:var(--cream2);display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            @endif
          </div>
          <div>
            <div class="farmer-info-name">{{ $farmer->name }}</div>
            <div class="farmer-info-loc">{{ $farmer->location }}</div>
          </div>
        </div>
      @endforeach
    </aside>

    {{-- Konten Produk --}}
    <div class="market-products">
      <div class="market-banner">
        <div class="market-banner-text">
          <h3>Langsung dari Kebun</h3>
          <p>Setiap produk dikirim segar, tanpa perantara</p>
        </div>
        <div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75"/></svg></div>
      </div>

      <div class="market-grid">
        @forelse($products as $prod)
          <div class="product-card" data-petani="{{ $prod->petani }}">
            <div class="product-thumb">
              @if($prod->gambar)
                <img src="{{ asset('products/' . $prod->gambar) }}"
                     alt="{{ $prod->nama_produk }}" loading="lazy">
              @endif
            </div>
            <div class="product-body">
              <div class="product-cat">{{ $prod->petani ?? 'Petani Mitra' }}</div>
              <div class="product-name">{{ $prod->nama_produk }}</div>
              <p class="product-desc">{{ $prod->deskripsi }}</p>
              <div class="product-footer">
                <span class="product-price">Rp {{ number_format($prod->harga, 0, ',', '.') }}</span>
                <button class="add-btn"
                  data-id="{{ $prod->id_product }}"
                  data-name="{{ $prod->nama_produk }}"
                  data-price="{{ $prod->harga }}"
                  data-image="{{ $prod->gambar }}">+</button>
              </div>
            </div>
          </div>
        @empty
          <p style="grid-column:1/-1;text-align:center;color:var(--text-light);padding:4rem">
            Produk belum tersedia.
          </p>
        @endforelse
      </div>
    </div>

  </div>
</div>
@endsection
