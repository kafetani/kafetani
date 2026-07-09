@extends('layouts.app')
@section('title', 'Tentang Kami  Kafetani')

@push('styles')
<link rel="stylesheet" href="{{ asset_v('style-tentang-kami.css') }}">
@endpush

@section('content')
<div class="page" id="page-about">

  <div class="page-header" style="background:var(--green)">
    <div class="page-header-label">Kafetani · Cerita Kami</div>
    <h1 class="page-header-title">Tentang Kami</h1>
    <p class="page-header-sub">Menghubungkan lumbung desa dengan cangkir di kota</p>
  </div>

  <div class="prose-wrap">

    <div class="prose-section">
      <h2 class="prose-title">Misi Kami: Keadilan di Setiap Tegukan</h2>
      <div class="prose-body">
        <p>Kafetani lahir dari sebuah kegelisahan sederhana: mengapa hasil tani yang luar biasa dari pelosok Indonesia seringkali dihargai rendah, sementara penikmat di kota membayar harga yang mahal?</p>
        <p>Kami hadir untuk memotong rantai distribusi yang panjang dan tidak efisien. Di Kafetani, kami tidak hanya menjual kopi dan pangan; kami membangun jembatan digital yang menghubungkan lumbung petani mitra kami langsung ke meja Anda.</p>
      </div>
    </div>

    <div class="two-col">
      <div class="info-card">
        <h3>Farm to Table</h3>
        <p>Setiap bahan baku yang kami gunakan di kafe maupun yang tersedia di marketplace dipastikan kesegarannya karena dikirim langsung dari petani mitra.</p>
      </div>
      <div class="info-card">
        <h3>Pemberdayaan</h3>
        <p>Kami memberikan pendampingan dan harga beli yang jauh lebih adil kepada petani, membantu mereka meningkatkan taraf hidup dan kualitas hasil tanam.</p>
      </div>
    </div>

    <div class="prose-section">
      <h2 class="prose-title">Mengapa Kafetani?</h2>
      <div class="prose-body">
        <p>Nama <strong>Kafetani</strong> adalah gabungan dari <em>Kafe</em> dan <em>Petani</em>. Kami percaya bahwa kualitas rasa yang Anda nikmati di setiap cangkir kopi kami adalah buah dari kerja keras dan dedikasi para petani kami. Dengan memilih Kafetani, Anda telah menjadi bagian dari pergerakan ekonomi mikro yang mendukung ketahanan pangan lokal Indonesia.</p>
      </div>
    </div>

    <div class="cta-band">
      <h3>Mari Menjadi Bagian dari Cerita Kami</h3>
      <a href="{{ route('marketplace') }}" class="cta-btn">Kunjungi Marketplace Petani →</a>
    </div>

  </div>
</div>
@endsection
