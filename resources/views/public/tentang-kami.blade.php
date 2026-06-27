@extends('layouts.app')
@section('title', 'Tentang Kami — Kafetani')

@push('styles')
<style>
.prose-wrap{max-width:800px;margin:4rem auto;padding:0 1.5rem}
.prose-section{margin-bottom:4rem}
.prose-title{font-family:var(--ff-display);font-size:2.3rem;color:var(--brown);margin-bottom:1.5rem;line-height:1.2;font-weight:300}
.prose-body{line-height:1.8;color:var(--text-mid);font-size:1.05rem}
.prose-body p+p{margin-top:1.2rem}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:4rem}
.info-card{background:var(--cream2);padding:2rem}
.info-card h3{font-family:var(--ff-display);font-size:1.5rem;color:var(--green);margin-bottom:1rem;font-weight:300}
.info-card p{font-size:.9rem;line-height:1.6;color:var(--text-mid)}
.cta-band{text-align:center;padding:4rem 0;border-top:1px solid var(--border)}
.cta-band h3{font-family:var(--ff-display);font-size:1.8rem;color:var(--brown);margin-bottom:1rem;font-weight:300}
.cta-btn{background:var(--green);color:#fff;border:none;padding:.8rem 2rem;font-family:var(--ff-body);font-size:.9rem;font-weight:500;cursor:pointer;text-decoration:none;display:inline-block;transition:background .2s;margin-top:1rem}
.cta-btn:hover{background:var(--green2)}
</style>
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
