@extends('layouts.app')
@section('title', 'Menu Kafe — Kafetani')

@push('scripts')
<script>
// Filter tab aktif
document.addEventListener('DOMContentLoaded', function () {
  const tabs  = document.querySelectorAll('.filter-tab');
  const cards = document.querySelectorAll('.product-card[data-cat]');

  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      const cat = this.dataset.cat;
      cards.forEach(card => {
        card.style.display = (cat === 'Semua' || card.dataset.cat === cat) ? '' : 'none';
      });
    });
  });
});
</script>
@endpush

@section('content')
<div class="page" id="page-menu">

  <div class="page-header">
    <div class="page-header-label">Kafetani · Menu Kafe</div>
    <h1 class="page-header-title">Menu Kafe</h1>
    <p class="page-header-sub">Minuman, bakeri, dan camilan buatan sendiri dari bahan lokal</p>
  </div>

  <div class="filter-bar">
    @foreach($categories as $i => $cat)
      <button class="filter-tab {{ $i === 0 ? 'active' : '' }}" data-cat="{{ $cat }}">
        {{ $cat }}
      </button>
    @endforeach
  </div>

  <div class="products-grid" id="menu-grid">
    @forelse($products as $prod)
      <div class="product-card" data-cat="{{ $prod->category->name ?? '' }}">
        <div class="product-thumb">
          @if($prod->gambar)
            <img src="{{ asset('products/' . $prod->gambar) }}"
                 alt="{{ $prod->nama_produk }}" loading="lazy">
          @endif
        </div>
        <div class="product-body">
          <div class="product-cat">{{ $prod->category->name ?? '' }}</div>
          <div class="product-name">{{ $prod->nama_produk }}</div>
          <p class="product-desc">{{ $prod->deskripsi }}</p>
          <div class="product-footer">
            <span class="product-price">Rp {{ number_format($prod->harga, 0, ',', '.') }}</span>
            <button class="add-btn"
              data-id="{{ $prod->nama_produk }}"
              data-name="{{ $prod->nama_produk }}"
              data-price="{{ $prod->harga }}"
              data-image="{{ $prod->gambar }}">+</button>
          </div>
        </div>
      </div>
    @empty
      <p style="grid-column:1/-1;text-align:center;color:var(--text-light);padding:4rem">
        Menu belum tersedia.
      </p>
    @endforelse
  </div>

</div>
@endsection
