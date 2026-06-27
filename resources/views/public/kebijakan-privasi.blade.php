@extends('layouts.app')
@section('title', 'Kebijakan Privasi — Kafetani')

@push('styles')
<style>
.prose-wrap{max-width:800px;margin:4rem auto;padding:0 1.5rem;line-height:1.8;color:var(--text-mid)}
.prose-section{margin-bottom:3rem}
.prose-section h2{font-family:var(--ff-display);font-size:1.8rem;color:var(--brown);margin-bottom:1rem;font-weight:300}
.prose-footer{margin-top:5rem;font-size:.85rem;color:rgba(0,0,0,.4);text-align:center;padding-top:2rem;border-top:1px solid var(--border)}
</style>
@endpush

@section('content')
<div class="page" id="page-privacy">

  <div class="page-header" style="background:var(--green)">
    <div class="page-header-label">Kafetani · Legal</div>
    <h1 class="page-header-title">Kebijakan Privasi</h1>
    <p class="page-header-sub">Bagaimana kami menjaga dan melindungi data pribadi Anda</p>
  </div>

  <div class="prose-wrap">

    <div class="prose-section">
      <h2>1. Informasi yang Kami Kumpulkan</h2>
      <p>Kami mengumpulkan informasi minimal yang diperlukan untuk memproses pesanan Anda, seperti nama, email, nomor telepon, dan alamat pengiriman.</p>
    </div>

    <div class="prose-section">
      <h2>2. Penggunaan Informasi</h2>
      <p>Data Anda digunakan secara eksklusif untuk layanan Kafetani: memverifikasi pesanan, menghubungi Anda terkait transaksi, dan jika Anda setuju, mengirimkan informasi program petani mitra terbaru.</p>
    </div>

    <div class="prose-section">
      <h2>3. Keamanan Data</h2>
      <p>Kami berkomitmen untuk melindungi data Anda. Kata sandi Anda disimpan menggunakan enkripsi tingkat tinggi yang tidak dapat dibaca oleh tim kami sekalipun.</p>
    </div>

    <div class="prose-section">
      <h2>4. Pihak Ketiga</h2>
      <p>Kafetani tidak akan pernah menjual atau menyewakan data pribadi Anda kepada pihak ketiga manapun. Data hanya dibagikan kepada petani mitra terbatas pada informasi yang diperlukan untuk pengiriman produk marketplace.</p>
    </div>

    <div class="prose-section">
      <h2>5. Kontak</h2>
      <p>Jika Anda memiliki pertanyaan mengenai kebijakan privasi kami, silakan hubungi kami melalui email di <a href="mailto:halo@kafetani.com" style="color:var(--green)">halo@kafetani.com</a>.</p>
    </div>

    <div class="prose-footer">Terakhir diperbarui: 10 April 2026</div>

  </div>
</div>
@endsection
