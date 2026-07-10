@extends('layouts.admin')
@section('title', 'Daftar Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-orders.css') }}">
@endpush

@section('content')
<div class="page-header">
  <h1>Daftar Pesanan</h1>
</div>

{{-- Status filter tabs --}}
<div class="status-tabs">
  @foreach(['all' => 'Semua', 'pending_payment' => 'Belum Bayar', 'pending' => 'Masuk', 'processing' => 'Proses', 'ready' => 'Siap', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
    <a href="{{ route('admin.orders.index', ['status' => $val]) }}"
       class="status-tab {{ $statusFilter === $val ? 'active' : '' }}">
      {{ $label }}
    </a>
  @endforeach
</div>

<table class="data-table">
  <thead>
    <tr>
      <th>#</th>
      <th>Pelanggan</th>
      <th>Sumber</th>
      <th>Items</th>
      <th>Total</th>
      <th>Waktu</th>
      <th>Status</th>
      <th>Update Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse($orders as $order)
    <tr>
      <td style="font-weight:500;">{{ $order->id }}</td>
      <td>
        <div style="font-weight:500;font-size:.88rem;">
          {{ $order->customer_name ?? $order->user->nama ?? 'Tamu' }}
        </div>
        <div style="font-size:.75rem;color:var(--text-light);">{{ $order->user->email ?? '' }}</div>
      </td>
      <td>
        <span class="badge badge-{{ $order->source }}">{{ $order->source }}</span>
      </td>
      <td>
        <div class="items-list">
          @foreach($order->items->take(3) as $item)
            {{ $item->product->nama_produk ?? 'Produk dihapus' }} ×{{ $item->quantity }}<br>
          @endforeach
          @if($order->items->count() > 3)
            <span style="color:var(--text-light)">+{{ $order->items->count() - 3 }} lainnya</span>
          @endif
        </div>
      </td>
      <td style="font-weight:500;white-space:nowrap;">
        Rp {{ number_format($order->total, 0, ',', '.') }}
      </td>
      <td style="font-size:.78rem;color:var(--text-mid);white-space:nowrap;">
        {{ $order->created_at?->format('d M Y') }}<br>
        {{ $order->created_at?->format('H:i') }}
      </td>
      <td>
        <span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
        @if($order->payment_type)
          <div style="font-size:.7rem;margin-top:.25rem;color:var(--text-mid);font-weight:500;">
            {{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}
            <span style="color:{{ $order->payment_status === 'paid' ? 'var(--green)' : 'var(--amber)' }}">
              ({{ strtoupper($order->payment_status) }})
            </span>
          </div>
        @endif
      </td>
      <td>
        @if(!in_array($order->status, ['completed', 'cancelled']))
          <form method="POST" action="{{ route('admin.orders.updateStatus') }}"
                style="display:flex;gap:.4rem;align-items:center;">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="status" id="status-{{ $order->id }}" value="{{ $order->status }}">
            <select class="status-select"
                    onchange="document.getElementById('status-{{ $order->id }}').value = this.value">
              <option value="pending_payment" {{ $order->status === 'pending_payment' ? 'selected' : '' }}>Belum Bayar</option>
              <option value="pending"    {{ $order->status === 'pending'    ? 'selected' : '' }}>Masuk</option>
              <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Proses</option>
              <option value="ready"      {{ $order->status === 'ready'      ? 'selected' : '' }}>Siap</option>
              <option value="completed"  {{ $order->status === 'completed'  ? 'selected' : '' }}>Selesai</option>
              <option value="cancelled"  {{ $order->status === 'cancelled'  ? 'selected' : '' }}>Batal</option>
            </select>
            <button type="submit" class="btn-update-status">✓</button>
          </form>
        @else
          <span style="font-size:.78rem;color:var(--text-light);"></span>
        @endif
      </td>
    </tr>
    @empty
    <tr class="empty-row">
      <td colspan="8">Tidak ada pesanan untuk filter ini.</td>
    </tr>
    @endforelse
  </tbody>
</table>
@endsection
