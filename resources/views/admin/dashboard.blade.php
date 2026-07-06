@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.2rem;margin-bottom:2.5rem}
.stat-card{background:#fff;border:1px solid var(--border);padding:1.5rem}
.stat-card h3{font-size:.78rem;font-weight:500;color:var(--text-mid);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.8rem}
.stat-num{font-family:var(--ff-display);font-size:2.2rem;font-weight:300;color:var(--green);display:block}
.quick-actions{display:flex;gap:.8rem;flex-wrap:wrap;margin-bottom:2.5rem;align-items:center}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1>Ringkasan Bisnis</h1>
    <p style="font-size:.85rem;color:var(--text-mid);margin-top:.3rem">Selamat datang, {{ auth()->user()->nama }}. Statistik hari ini.</p>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <h3>Total Pendapatan</h3>
    <span class="stat-num">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</span>
  </div>
  <div class="stat-card">
    <h3>Total Pesanan</h3>
    <span class="stat-num">{{ $stats['total_pesanan'] }}</span>
  </div>
  <div class="stat-card">
    <h3>Produk Tersedia</h3>
    <span class="stat-num">{{ $stats['total_produk'] }}</span>
  </div>
  <div class="stat-card">
    <h3>Petani Mitra</h3>
    <span class="stat-num">{{ $stats['total_petani'] }}</span>
  </div>
</div>

<div class="quick-actions">
  <a href="{{ route('admin.products.index') }}" class="btn-primary">+ Tambah Produk Baru</a>
  <a href="{{ route('admin.farmers.create') }}" class="btn-primary">+ Daftarkan Petani</a>
  <a href="{{ route('admin.kasir') }}" class="btn-primary"><svg class="icon-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg> Buka Kasir POS</a>
  <a href="{{ route('admin.orders.index') }}" class="btn-edit">Lihat Pesanan Masuk</a>
</div>
@endsection
