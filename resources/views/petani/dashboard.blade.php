@extends('layouts.petani')
@section('title', 'Dashboard Petani')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('style-produk.css') }}">
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1>Halo, {{ $farmer->name }}</h1>
    <p style="font-size:.85rem;color:var(--text-mid);margin-top:.3rem">Ringkasan produk marketplace milik Anda.</p>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <h3>Total Produk</h3>
    <span class="stat-num">{{ $stats['total_produk'] }}</span>
  </div>
  <div class="stat-card">
    <h3>Menunggu Review</h3>
    <span class="stat-num">{{ $stats['total_pending'] }}</span>
  </div>
  <div class="stat-card">
    <h3>Disetujui</h3>
    <span class="stat-num">{{ $stats['total_approved'] }}</span>
  </div>
  <div class="stat-card">
    <h3>Total Terjual (pcs)</h3>
    <span class="stat-num">{{ $stats['total_terjual'] }}</span>
  </div>
</div>

<div class="quick-actions" style="margin-bottom:2rem;">
  <a href="{{ route('petani.produk.index') }}" class="btn-primary">+ Daftarkan Produk Baru</a>
  <a href="{{ route('petani.profil') }}" class="btn-edit">Kelola Profil</a>
</div>

<h2 style="font-family:var(--ff-display);font-weight:300;font-size:1.5rem;color:var(--brown);margin-bottom:1rem;">Produk Terbaru</h2>
<table class="data-table">
  <thead>
    <tr>
      <th>Nama Produk</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse($produkTerbaru as $prod)
    <tr>
      <td style="font-weight:500;">{{ $prod->nama_produk }}</td>
      <td>Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
      <td>{{ $prod->stok }}</td>
      <td><span class="badge badge-{{ $prod->status }}">{{ $prod->status }}</span></td>
    </tr>
    @empty
    <tr class="empty-row"><td colspan="4">Anda belum mendaftarkan produk apa pun.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection
