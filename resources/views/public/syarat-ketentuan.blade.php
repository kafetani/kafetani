@extends('layouts.app')
@section('title', 'Syarat & Ketentuan  Kafetani')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-syarat-ketentuan.css') }}">
@endpush

@section('content')
<div class="page" id="page-terms">

  <div class="page-header" style="background:var(--brown)">
    <div class="page-header-label">Kafetani · Legal</div>
    <h1 class="page-header-title">Syarat &amp; Ketentuan</h1>
    <p class="page-header-sub">Aturan penggunaan layanan dan transaksi di platform Kafetani</p>
  </div>

  <div class="prose-wrap">

    <div class="prose-section">
      <h2>1. Umum</h2>
      <p>Dengan mengakses dan menggunakan website Kafetani, Anda setuju untuk terikat oleh syarat dan ketentuan yang berlaku. Kafetani berhak memperbarui syarat ini sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>
    </div>

    <div class="prose-section">
      <h2>2. Akun Pengguna</h2>
      <p>Anda bertanggung jawab untuk menjaga kerahasiaan informasi akun dan password Anda. Setiap aktivitas yang dilakukan menggunakan akun Anda menjadi tanggung jawab Anda sepenuhnya.</p>
    </div>

    <div class="prose-section">
      <h2>3. Pemesanan dan Pembayaran</h2>
      <p>Semua pesanan yang dilakukan melalui website ini bersifat final setelah konfirmasi pembayaran diterima. Untuk pesanan menu kafe, pengambilan dilakukan di gerai sesuai waktu yang disepakati.</p>
    </div>

    <div class="prose-section">
      <h2>4. Marketplace Petani</h2>
      <p>Kafetani bertindak sebagai jembatan antara petani mitra dan pembeli. Kualitas bahan baku produk marketplace dijamin oleh standar yang telah kami tetapkan bersama petani mitra kami.</p>
    </div>

    <div class="prose-section">
      <h2>5. Pembatalan</h2>
      <p>Pembatalan pesanan dapat dilakukan jika status pesanan belum diproses oleh tim kami atau petani mitra. Dana akan dikembalikan sesuai dengan kebijakan pengembalian yang berlaku.</p>
    </div>

    <div class="prose-footer">Terakhir diperbarui: 10 April 2026</div>

  </div>
</div>
@endsection
