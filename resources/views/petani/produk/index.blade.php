@extends('layouts.petani')
@section('title', 'Produk Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-produk.css') }}">
@endpush

@section('content')
<div class="page-header">
  <h1>Produk Saya</h1>
  <button class="btn-primary" onclick="openModal()">+ Daftarkan Produk</button>
</div>

<p style="font-size:.85rem;color:var(--text-mid);margin-bottom:1.5rem;">
  Produk baru atau yang diedit akan berstatus <span class="badge badge-pending">pending</span>
  sampai disetujui Admin, sebelum tampil di halaman Marketplace publik.
</p>

<table class="data-table">
  <thead>
    <tr>
      <th>Gambar</th>
      <th>Nama Produk</th>
      <th>Kategori</th>
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
          <img class="product-thumb-sm" src="{{ asset_v('products/' . $prod->gambar) }}" alt="{{ $prod->nama_produk }}">
        @else
          <div class="product-thumb-sm"></div>
        @endif
      </td>
      <td style="font-weight:500;">{{ $prod->nama_produk }}</td>
      <td style="font-size:.83rem;">{{ $prod->category->name ?? '' }}</td>
      <td><span class="badge badge-{{ $prod->status }}">{{ $prod->status }}</span></td>
      <td>Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
      <td>
        <span style="{{ $prod->stok < 5 ? 'color:#c0392b;font-weight:500;' : '' }}">{{ $prod->stok }}</span>
      </td>
      <td>
        <div style="display:flex;gap:.4rem;">
          <button class="btn-edit"
            onclick="openModal({{ json_encode([
              'id'          => $prod->id_product,
              'nama_produk' => $prod->nama_produk,
              'harga'       => $prod->harga,
              'stok'        => $prod->stok,
              'deskripsi'   => $prod->deskripsi,
              'category_id' => $prod->category_id,
              'gambar'      => $prod->gambar,
            ]) }})">Edit</button>
          <a href="{{ route('petani.produk.delete', ['hapus' => $prod->id_product]) }}"
             class="btn-danger"
             onclick="return confirm('Hapus produk ini?')">Hapus</a>
        </div>
      </td>
    </tr>
    @empty
    <tr class="empty-row"><td colspan="7">anda belum mendaftarkan produk apa pun.</td></tr>
    @endforelse
  </tbody>
</table>

{{-- Modal Tambah / Edit --}}
<div class="modal-bg" id="product-modal">
  <div class="modal-box">
    <h2 id="modal-title">Daftarkan Produk</h2>

    <form method="POST" action="{{ route('petani.produk.save') }}" enctype="multipart/form-data">
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

      <div class="form-group">
        <label>Kategori</label>
        <select name="category_id" id="f-cat">
          <option value=""> Pilih </option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" id="f-desc" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>Gambar Produk</label>
        <input type="file" name="gambar" id="f-gambar" accept="image/*" onchange="previewImg(this)">
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
<script src="{{ asset('script-petani-produk.js') }}"></script>
@endpush
