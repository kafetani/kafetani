@extends('layouts.app')
@section('title', 'Cara Pesan — Kafetani')

@push('styles')
<style>
.prose-wrap{max-width:800px;margin:4rem auto;padding:0 1.5rem}
.prose-section{margin-bottom:4rem}
.prose-title{font-family:var(--ff-display);font-size:2rem;color:var(--brown);margin-bottom:1.5rem;font-weight:300}
.prose-body{line-height:1.8;color:var(--text-mid)}
.prose-body ul{padding-left:1.5rem;margin-top:1rem}
.prose-body li{margin-bottom:.8rem}
.quote-block{background:var(--cream2);padding:2rem;border-left:4px solid var(--green);margin-top:4rem}
.quote-block p{font-style:italic;margin:0;color:var(--brown);line-height:1.7}
</style>
@endpush

@section('content')
<div class="page" id="page-how-to-order">

  <div class="page-header" style="background:var(--brown)">
    <div class="page-header-label">Kafetani · Bantuan</div>
    <h1 class="page-header-title">Cara Pesan</h1>
    <p class="page-header-sub">Panduan mudah berbelanja di Kafe dan Marketplace Petani kami</p>
  </div>

  <div class="prose-wrap">

    <div class="prose-section">
      <h2 class="prose-title">1. Menu Kafe (Dine-in / Pickup)</h2>
      <div class="prose-body">
        <p>Nikmati sajian kopi dan panganan lokal kami dengan sistem pemesanan online yang praktis:</p>
        <ul>
          <li>Buka halaman <strong>Menu Kafe</strong> melalui navigasi atas.</li>
          <li>Pilih menu favoritmu dan tekan tombol <strong>+</strong> untuk memasukkan ke keranjang.</li>
          <li>Klik tombol <strong>Keranjang</strong> di kanan atas untuk meninjau pesananmu.</li>
          <li>Tekan <strong>Konfirmasi Pesanan</strong>. Pastikan kamu sudah login untuk memproses transaksi.</li>
          <li>Kunjungi kedai kami, tunjukkan bukti pesanan, dan pesananmu siap dinikmati!</li>
        </ul>
      </div>
    </div>

    <div class="prose-section">
      <h2 class="prose-title">2. Marketplace Petani</h2>
      <div class="prose-body">
        <p>Beli bahan baku segar langsung dari tangan petani mitra kami:</p>
        <ul>
          <li>Masuk ke halaman <strong><a href="{{ route('marketplace') }}" style="color:var(--green)">Marketplace</a></strong>.</li>
          <li>Gunakan filter di sidebar sebelah kiri jika kamu ingin mencari produk dari petani spesifik.</li>
          <li>Pilih produk petani (seperti Biji Kopi Gayo atau Gula Aren) dan tambahkan ke keranjang.</li>
          <li>Lakukan checkout seperti biasa. Tim kami akan memverifikasi ketersediaan stok dari petani terkait.</li>
          <li>Pesanan akan dikirimkan ke alamatmu atau tersedia untuk diambil di kedai Kafetani terdekat.</li>
        </ul>
      </div>
    </div>

    <div class="quote-block">
      <p>"Setiap pesananmu membantu menyejahterakan petani lokal secara langsung tanpa melalui rantai distribusi yang panjang."</p>
    </div>

  </div>
</div>
@endsection
