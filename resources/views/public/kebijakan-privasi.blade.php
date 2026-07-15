@extends('layouts.app')
@section('title', 'Kebijakan Privasi  Kafetani')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-kebijakan-privasi.css') }}">
@endpush

@section('content')
<div class="page" id="page-privacy">

  <div class="page-header" style="background:var(--green)">
    <div class="page-header-label">Kafetani · Legal</div>
    <h1 class="page-header-title">Kebijakan Privasi</h1>
    <p class="page-header-sub">Bagaimana kami menjaga dan melindungi data pribadi anda</p>
  </div>

  <div class="prose-wrap">

    <div class="prose-section">
      <h2>1. Informasi yang Kami Kumpulkan</h2>
      <p>Kami mengumpulkan informasi minimal yang diperlukan untuk memproses pesanan anda, seperti nama, email, nomor telepon, dan alamat pengiriman.</p>
    </div>

    <div class="prose-section">
      <h2>2. Penggunaan Informasi</h2>
      <p>Data anda digunakan secara eksklusif untuk layanan Kafetani: memverifikasi pesanan, menghubungi anda terkait transaksi, dan jika anda setuju, mengirimkan informasi program petani mitra terbaru.</p>
    </div>

    <div class="prose-section">
      <h2>3. Keamanan Data</h2>
      <p>Kami berkomitmen untuk melindungi data anda. Kata sandi anda disimpan menggunakan enkripsi tingkat tinggi yang tidak dapat dibaca oleh tim kami sekalipun.</p>
    </div>

    <div class="prose-section">
      <h2>4. Pihak Ketiga</h2>
      <p>Kafetani tidak akan pernah menjual atau menyewakan data pribadi anda kepada pihak ketiga manapun. Data hanya dibagikan kepada petani mitra terbatas pada informasi yang diperlukan untuk pengiriman produk marketplace.</p>
    </div>

    <div class="prose-section">
      <h2>5. Pembayaran melalui Pihak Ketiga</h2>
      <p>Transaksi pembayaran diproses melalui penyedia layanan pembayaran pihak ketiga (Midtrans). Kafetani tidak menyimpan data kartu atau rekening anda — seluruh proses pembayaran ditangani langsung oleh penyedia layanan tersebut sesuai standar keamanan mereka.</p>
    </div>

    <div class="prose-section">
      <h2>6. Cookie dan Pelacakan</h2>
      <p>Website kami dapat menggunakan cookie untuk menjaga sesi login anda tetap aktif dan meningkatkan pengalaman berbelanja. Anda dapat menonaktifkan cookie melalui pengaturan browser, namun beberapa fitur situs mungkin tidak berfungsi optimal.</p>
    </div>

    <div class="prose-section">
      <h2>7. Hak Anda atas Data Pribadi</h2>
      <p>Anda berhak untuk mengakses, memperbarui, atau meminta penghapusan data pribadi yang kami simpan, dengan menghubungi kami melalui kontak di bawah ini.</p>
    </div>

    <div class="prose-section">
      <h2>8. Retensi Data</h2>
      <p>Data pribadi anda disimpan selama akun anda aktif. Jika anda menghapus akun, data akan dihapus dari sistem kami dalam waktu maksimal 14 hari kerja, kecuali data yang wajib disimpan untuk kepentingan hukum atau pajak.</p>
    </div>

    <div class="prose-section">
      <h2>9. Kontak</h2>
      <p>Jika anda memiliki pertanyaan mengenai kebijakan privasi kami, silakan hubungi kami melalui email di <a href="mailto:halo@kafetani.store" style="color:var(--green)">halo@kafetani.store</a>.</p>
    </div>

    <div class="prose-footer">Terakhir diperbarui: 15 Juli 2026</div>

  </div>
</div>
@endsection
