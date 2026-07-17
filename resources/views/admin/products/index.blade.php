@extends('layouts.admin')
@section('title', 'Manajemen Produk')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-produk.css') }}">
@endpush

@section('content')
<div class="page-header">
  <h1>Manajemen Produk</h1>
  <div style="display:flex;gap:.6rem;align-items:center;">
    @if($pendingCount > 0)
      <a href="{{ route('admin.products.index', ['type' => 'market', 'status' => 'pending']) }}"
         class="badge badge-pending" style="text-decoration:none;">{{ $pendingCount }} produk petani menunggu review</a>
    @endif
    <button class="btn-primary" onclick="openModal()">+ Tambah Produk</button>
  </div>
</div>

{{-- Filter tabs --}}
<div class="type-tabs">
  <a href="{{ route('admin.products.index') }}"
     class="type-tab {{ $type === 'all'    ? 'active' : '' }}">Semua</a>
  <a href="{{ route('admin.products.index', ['type' => 'cafe']) }}"
     class="type-tab {{ $type === 'cafe'   ? 'active' : '' }}">Menu Kafe</a>
  <a href="{{ route('admin.products.index', ['type' => 'market']) }}"
     class="type-tab {{ $type === 'market' ? 'active' : '' }}">Marketplace</a>
  <a href="{{ route('admin.products.index', ['type' => 'market', 'status' => 'pending']) }}"
     class="type-tab {{ request('status') === 'pending' ? 'active' : '' }}">Menunggu Review</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th>Gambar</th>
      <th>Nama Produk</th>
      <th>Kategori</th>
      <th>Tipe</th>
      <th>Petani</th>
      <th>Status</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($products as $prod)
    <tr>
      <td>
        @if($prod->gambar)
          <img class="product-thumb-sm"
               src="{{ asset_v('products/' . $prod->gambar) }}"
               alt="{{ $prod->nama_produk }}">
        @else
          <div class="product-thumb-sm" style="display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></div>
        @endif
      </td>
      <td style="font-weight:500;">{{ $prod->nama_produk }}</td>
      <td style="font-size:.83rem;">{{ $prod->category->name ?? '' }}</td>
      <td>
        <span class="badge {{ $prod->type === 'cafe' ? 'badge-cafe' : 'badge-market' }}">
          {{ $prod->type }}
        </span>
      </td>
      <td style="font-size:.83rem;">{{ $prod->farmer->name ?? '-' }}</td>
      <td>
        @if($prod->type === 'market' && $prod->farmer_id)
          <span class="badge badge-{{ $prod->status ?? 'approved' }}">{{ $prod->status ?? 'approved' }}</span>
        @else
          <span class="badge badge-approved">approved</span>
        @endif
      </td>
      <td>Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
      <td>
        <span style="{{ $prod->stok < 5 ? 'color:#c0392b;font-weight:500;' : '' }}">
          {{ $prod->stok }}
        </span>
      </td>
      <td>
        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
          @if($prod->type === 'market' && $prod->farmer_id && $prod->status === 'pending')
            <form method="POST" action="{{ route('admin.products.approve', $prod->id_product) }}">
              @csrf
              <button type="submit" class="btn-edit">Setujui</button>
            </form>
            <form method="POST" action="{{ route('admin.products.reject', $prod->id_product) }}"
                  onsubmit="return confirm('Tolak produk ini?')">
              @csrf
              <button type="submit" class="btn-danger">Tolak</button>
            </form>
          @endif
          <button class="btn-edit"
            onclick="openModal({{ json_encode([
              'id'          => $prod->id_product,
              'nama_produk' => $prod->nama_produk,
              'harga'       => $prod->harga,
              'stok'        => $prod->stok,
              'deskripsi'   => $prod->deskripsi,
              'category_id' => $prod->category_id,
              'type'        => $prod->type,
              'farmer_id'   => $prod->farmer_id,
              'gambar'      => $prod->gambar,
            ]) }})">Edit</button>
          <a href="{{ route('admin.products.delete', ['hapus' => $prod->id_product]) }}"
             class="btn-danger"
             onclick="return confirm('Hapus produk ini?')">Hapus</a>
        </div>
      </td>
    </tr>
    @empty
    <tr class="empty-row"><td colspan="9">Belum ada produk.</td></tr>
    @endforelse
  </tbody>
</table>

{{-- Tampilan kartu untuk layar kecil (ganti tabel di HP) --}}
<div class="product-cards">
  @forelse($products as $prod)
  <div class="product-card">
    <div class="product-card-top">
      @if($prod->gambar)
        <img class="product-thumb-sm"
             src="{{ asset_v('products/' . $prod->gambar) }}"
             alt="{{ $prod->nama_produk }}">
      @else
        <div class="product-thumb-sm" style="display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></div>
      @endif
      <div class="product-card-info">
        <div class="product-card-name">{{ $prod->nama_produk }}</div>
        <div class="product-card-cat">{{ $prod->category->name ?? '' }}</div>
        <div class="product-card-badges">
          <span class="badge {{ $prod->type === 'cafe' ? 'badge-cafe' : 'badge-market' }}">
            {{ $prod->type }}
          </span>
          @if($prod->type === 'market' && $prod->farmer_id)
            <span class="badge badge-{{ $prod->status ?? 'approved' }}">{{ $prod->status ?? 'approved' }}</span>
          @else
            <span class="badge badge-approved">approved</span>
          @endif
        </div>
      </div>
    </div>

    <div class="product-card-meta">
      <div>
        <span class="meta-label">Harga</span>
        <span class="meta-value">Rp {{ number_format($prod->harga, 0, ',', '.') }}</span>
      </div>
      <div>
        <span class="meta-label">Stok</span>
        <span class="meta-value" style="{{ $prod->stok < 5 ? 'color:#c0392b;font-weight:500;' : '' }}">{{ $prod->stok }}</span>
      </div>
      <div>
        <span class="meta-label">Petani</span>
        <span class="meta-value">{{ $prod->farmer->name ?? '-' }}</span>
      </div>
    </div>

    <div class="product-card-actions">
      @if($prod->type === 'market' && $prod->farmer_id && $prod->status === 'pending')
        <form method="POST" action="{{ route('admin.products.approve', $prod->id_product) }}">
          @csrf
          <button type="submit" class="btn-edit">Setujui</button>
        </form>
        <form method="POST" action="{{ route('admin.products.reject', $prod->id_product) }}"
              onsubmit="return confirm('Tolak produk ini?')">
          @csrf
          <button type="submit" class="btn-danger">Tolak</button>
        </form>
      @endif
      <button class="btn-edit"
        onclick="openModal({{ json_encode([
          'id'          => $prod->id_product,
          'nama_produk' => $prod->nama_produk,
          'harga'       => $prod->harga,
          'stok'        => $prod->stok,
          'deskripsi'   => $prod->deskripsi,
          'category_id' => $prod->category_id,
          'type'        => $prod->type,
          'farmer_id'   => $prod->farmer_id,
          'gambar'      => $prod->gambar,
        ]) }})">Edit</button>
      <a href="{{ route('admin.products.delete', ['hapus' => $prod->id_product]) }}"
         class="btn-danger"
         onclick="return confirm('Hapus produk ini?')">Hapus</a>
    </div>
  </div>
  @empty
  <div class="product-card-empty">Belum ada produk.</div>
  @endforelse
</div>

{{-- Modal Tambah / Edit --}}
<div class="modal-bg" id="product-modal">
  <div class="modal-box">
    <h2 id="modal-title">Tambah Produk</h2>

    <form method="POST" action="{{ route('admin.products.save') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="f-id">
      <input type="hidden" name="gambar_lama" id="f-gambar-lama">

      <div class="form-group">
        <label>Nama Produk *</label>
        <input type="text" name="nama_produk" id="f-nama" required>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label>Harga (Rp) *</label>
          <input type="number" name="harga" id="f-harga" min="0" required>
        </div>
        <div class="form-group">
          <label>Stok *</label>
          <input type="number" name="stok" id="f-stok" min="0" required>
        </div>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label>Tipe *</label>
          <select name="type" id="f-type">
            <option value="cafe">Kafe</option>
            <option value="market">Marketplace</option>
          </select>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select name="category_id" id="f-cat">
            <option value=""> Pilih </option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" id="f-desc" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>Petani (opsional, khusus marketplace)</label>
        <select name="farmer_id" id="f-farmer">
          <option value="">Tidak ada</option>
          @foreach($farmers as $farmer)
            <option value="{{ $farmer->id }}">{{ $farmer->name }} - {{ $farmer->location }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Gambar Produk</label>
        <input type="file" name="gambar" id="f-gambar" accept="image/*"
               onchange="previewImg(this)">
        <img id="img-preview" src="" alt="Preview">
      </div>

      <div class="modal-actions">
        <button type="submit" class="btn-primary">Simpan →</button>
        <button type="button" class="btn-modal-cancel" onclick="closeModal()">Batal</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('script-produk.js') }}"></script>
@endpush
