@extends('layouts.admin')
@section('title', 'Kasir POS')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-kasir.css') }}">
@endpush

@section('content')

{{-- Flash receipt dari redirect --}}
@if(session('success_order'))
  @php $so = session('success_order'); @endphp
  <div class="modal-bg open" id="receipt-modal">
    <div class="receipt-box">
      <div class="receipt-header">
        <div style="margin-bottom:.3rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
        <h3>Pesanan #{{ $so['id'] }}</h3>
        <p>{{ $so['customer_name'] }} &mdash;
          {{ $so['order_type'] === 'dine-in' ? 'Makan di Tempat' : 'Take Away' }}
        </p>
      </div>
      @foreach($so['items'] as $item)
        <div class="receipt-item-row">
          <span>{{ $item['product']['nama_produk'] }} ×{{ $item['qty'] }}</span>
          <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
        </div>
      @endforeach
      <hr class="receipt-divider">
      <div class="receipt-total-row">
        <span>Total</span>
        <span>Rp {{ number_format($so['total'], 0, ',', '.') }}</span>
      </div>
      <div class="receipt-thank">Terima kasih! Pesanan sedang diproses. <svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></div>
      <div class="receipt-actions">
        <button class="btn-print" onclick="window.print()"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg> Cetak</button>
        <button class="btn-close-receipt" onclick="closeReceipt()">Tutup</button>
      </div>
    </div>
  </div>
@endif

<div class="page-header" style="margin-bottom:1rem;">
  <h1>Kasir POS</h1>
  <span style="font-size:.82rem;color:var(--text-mid)">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
</div>

<div class="kasir-layout">

  {{-- LEFT: Menu --}}
  <div class="menu-panel">
    {{-- Search --}}
    <div class="menu-search">
      <span class="menu-search-icon"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
      <input type="text" id="menu-search" placeholder="Cari menu..." oninput="filterMenu()">
    </div>
    {{-- Kategori tabs --}}
    <div class="menu-cats">
      <button class="menu-cat-btn active" data-cat="Semua" onclick="setCat(this)">Semua</button>
      @foreach($categories as $cat)
        <button class="menu-cat-btn" data-cat="{{ $cat }}" onclick="setCat(this)">{{ $cat }}</button>
      @endforeach
    </div>
    {{-- Grid produk --}}
    <div class="menu-grid" id="menu-grid">
      @foreach($products as $prod)
        <div class="menu-item {{ $prod->stok < 1 ? 'no-stok' : '' }}"
             data-cat="{{ $prod->category->name ?? '' }}"
             data-name="{{ strtolower($prod->nama_produk) }}"
             onclick="addItem({{ $prod->id_product }}, '{{ addslashes($prod->nama_produk) }}', {{ $prod->harga }}, '{{ $prod->gambar }}')">
          <div class="menu-item-img">
            @if($prod->gambar)
              <img src="{{ asset_v('products/' . $prod->gambar) }}" alt="{{ $prod->nama_produk }}" loading="lazy">
            @else
              <div style="height:100%;display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></div>
            @endif
          </div>
          <div class="menu-item-body">
            <div class="menu-item-name">{{ $prod->nama_produk }}</div>
            <div class="menu-item-price">Rp {{ number_format($prod->harga, 0, ',', '.') }}</div>
            <div class="menu-item-stok">Stok: {{ $prod->stok }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- RIGHT: Order panel --}}
  <div class="order-panel">
    <div class="order-top">
      <h3>Pesanan</h3>
      <span id="item-count" style="font-size:.8rem;color:var(--text-light)">0 item</span>
    </div>

    <div class="order-items" id="order-items">
      <div class="order-empty" id="order-empty">
        <div class="order-empty-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/></svg></div>
        <p>Pilih menu dari daftar</p>
      </div>
    </div>

    <div class="order-bottom">
      <div class="order-meta">
        <label>Tipe Pesanan</label>
        <select id="order-type" name="order_type">
          <option value="dine-in">Makan di Tempat</option>
          <option value="pickup">Take Away</option>
        </select>
        <label>Nama Pelanggan (opsional)</label>
        <input type="text" id="customer-name" placeholder="cth. Meja 3 / Budi">
      </div>
      <div class="order-subtotal"><span>Subtotal</span><span id="subtotal-val">Rp 0</span></div>
      <div class="order-total"><span>Total</span><span id="total-val">Rp 0</span></div>
      <form method="POST" action="{{ route('admin.kasir.order') }}" id="kasir-form">
        @csrf
        <input type="hidden" name="items" id="f-items">
        <input type="hidden" name="order_type" id="f-order-type">
        <input type="hidden" name="customer_name" id="f-customer-name">
        <button type="submit" class="place-order-btn" id="place-btn" disabled>
          Buat Pesanan →
        </button>
      </form>
      <button class="clear-btn" onclick="clearOrder()"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Kosongkan</button>
    </div>
  </div>
</div>

{{-- Receipt modal placeholder (diisi via session flash di atas) --}}
<div class="modal-bg" id="receipt-modal" style="display:none;"></div>
@endsection

@push('scripts')
<script src="{{ asset('script-kasir.js') }}"></script>
@endpush
