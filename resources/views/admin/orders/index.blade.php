@extends('layouts.admin')
@section('title', 'Daftar Pesanan')

@push('styles')
<style>
.status-tabs{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:1.8rem;flex-wrap:wrap}
.status-tab{padding:.7rem 1.1rem;font-size:.82rem;cursor:pointer;color:var(--text-mid);border-bottom:2px solid transparent;text-decoration:none;white-space:nowrap;transition:all .2s}
.status-tab.active{color:var(--green);border-bottom-color:var(--green);font-weight:500}
.badge{display:inline-block;padding:.2rem .6rem;font-size:.7rem;font-weight:500;border-radius:2px;letter-spacing:.04em;text-transform:uppercase}
.badge-pending{background:#FEF3C7;color:#92400E}
.badge-processing{background:#DBEAFE;color:#1E40AF}
.badge-ready{background:#D1FAE5;color:#065F46}
.badge-completed{background:#F3F4F6;color:#374151}
.badge-cancelled{background:#FEE2E2;color:#991B1B}
.badge-online{background:#EDE9FE;color:#5B21B6}
.badge-offline{background:#F3F4F6;color:#374151}
.items-list{font-size:.78rem;color:var(--text-mid);line-height:1.7}
/* Detail modal */
.modal-bg{display:none;position:fixed;inset:0;background:rgba(42,31,18,.5);z-index:200;align-items:center;justify-content:center}
.modal-bg.open{display:flex}
.modal-box{background:var(--cream);border:1px solid var(--border);padding:2rem;width:100%;max-width:500px;max-height:90vh;overflow-y:auto}
.modal-box h2{font-family:var(--ff-display);font-size:1.6rem;font-weight:300;color:var(--brown);margin-bottom:1.2rem}
.detail-row{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:.5px solid var(--border);font-size:.85rem}
.detail-row:last-of-type{border-bottom:none}
.detail-label{color:var(--text-mid)}
.detail-val{font-weight:500;color:var(--brown);text-align:right}
.detail-items{margin:1rem 0;background:#fff;border:1px solid var(--border);padding:.8rem 1rem}
.detail-item-row{display:flex;justify-content:space-between;font-size:.82rem;padding:.35rem 0;border-bottom:.5px solid var(--border)}
.detail-item-row:last-child{border-bottom:none}
.detail-total{display:flex;justify-content:space-between;font-size:.9rem;font-weight:500;margin-top:.8rem;color:var(--brown)}
select.status-select{padding:.35rem .55rem;border:1px solid var(--border);font-family:var(--ff-body);font-size:.82rem;background:#fff;cursor:pointer;outline:none;transition:border-color .2s;color:var(--text)}
select.status-select:focus{border-color:var(--green)}
.btn-update-status{background:var(--green);color:#fff;border:none;padding:.35rem .75rem;font-family:var(--ff-body);font-size:.78rem;cursor:pointer;transition:background .2s}
.btn-update-status:hover{background:var(--green2)}
</style>
@endpush

@section('content')
<div class="page-header">
  <h1>Daftar Pesanan</h1>
</div>

{{-- Status filter tabs --}}
<div class="status-tabs">
  @foreach(['all' => 'Semua', 'pending' => 'Masuk', 'processing' => 'Proses', 'ready' => 'Siap', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
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
              <option value="pending"    {{ $order->status === 'pending'    ? 'selected' : '' }}>Masuk</option>
              <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Proses</option>
              <option value="ready"      {{ $order->status === 'ready'      ? 'selected' : '' }}>Siap</option>
              <option value="completed"  {{ $order->status === 'completed'  ? 'selected' : '' }}>Selesai</option>
              <option value="cancelled"  {{ $order->status === 'cancelled'  ? 'selected' : '' }}>Batal</option>
            </select>
            <button type="submit" class="btn-update-status">✓</button>
          </form>
        @else
          <span style="font-size:.78rem;color:var(--text-light);">—</span>
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
