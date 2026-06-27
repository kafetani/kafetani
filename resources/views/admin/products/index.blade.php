@extends('layouts.admin')
@section('title', 'Manajemen Produk')

@push('styles')
<style>
.type-tabs{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:1.8rem}
.type-tab{padding:.7rem 1.3rem;font-size:.82rem;cursor:pointer;color:var(--text-mid);border-bottom:2px solid transparent;text-decoration:none;white-space:nowrap;transition:all .2s}
.type-tab.active{color:var(--green);border-bottom-color:var(--green);font-weight:500}
.product-thumb-sm{width:40px;height:40px;object-fit:cover;background:var(--cream2)}
.badge{display:inline-block;padding:.15rem .55rem;font-size:.7rem;font-weight:500;border-radius:2px;letter-spacing:.04em;text-transform:uppercase}
.badge-cafe{background:#EAF3DE;color:var(--green)}
.badge-market{background:var(--amber-light);color:#7A4A10}
/* Modal */
.modal-bg{display:none;position:fixed;inset:0;background:rgba(42,31,18,.5);z-index:200;align-items:center;justify-content:center}
.modal-bg.open{display:flex}
.modal-box{background:var(--cream);border:1px solid var(--border);padding:2rem;width:100%;max-width:540px;max-height:90vh;overflow-y:auto}
.modal-box h2{font-family:var(--ff-display);font-size:1.6rem;font-weight:300;color:var(--brown);margin-bottom:1.5rem}
.form-group{margin-bottom:1rem}
.form-group label{display:block;font-size:.8rem;font-weight:500;color:var(--text-mid);margin-bottom:.35rem}
.form-group input,.form-group textarea,.form-group select{
  width:100%;padding:.6rem .8rem;border:1px solid var(--border);
  font-family:var(--ff-body);font-size:.88rem;background:var(--cream);
  outline:none;transition:border-color .15s;color:var(--text);
}
.form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:var(--green)}
.form-group textarea{resize:vertical;min-height:70px}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.field-err{font-size:.75rem;color:#c0392b;margin-top:.25rem}
.modal-actions{display:flex;gap:.8rem;margin-top:1.5rem;align-items:center}
.btn-modal-cancel{background:none;border:1px solid var(--border);padding:.6rem 1.1rem;font-family:var(--ff-body);font-size:.85rem;cursor:pointer;color:var(--text-mid)}
#img-preview{width:60px;height:60px;object-fit:cover;display:none;margin-top:.5rem}
</style>
@endpush

@section('content')
<div class="page-header">
  <h1>Manajemen Produk</h1>
  <button class="btn-primary" onclick="openModal()">+ Tambah Produk</button>
</div>

{{-- Filter tabs --}}
<div class="type-tabs">
  <a href="{{ route('admin.products.index') }}"
     class="type-tab {{ $type === 'all'    ? 'active' : '' }}">Semua</a>
  <a href="{{ route('admin.products.index', ['type' => 'cafe']) }}"
     class="type-tab {{ $type === 'cafe'   ? 'active' : '' }}">Menu Kafe</a>
  <a href="{{ route('admin.products.index', ['type' => 'market']) }}"
     class="type-tab {{ $type === 'market' ? 'active' : '' }}">Marketplace</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th>Gambar</th>
      <th>Nama Produk</th>
      <th>Kategori</th>
      <th>Tipe</th>
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
               src="{{ asset('products/' . $prod->gambar) }}"
               alt="{{ $prod->nama_produk }}">
        @else
          <div class="product-thumb-sm" style="display:flex;align-items:center;justify-content:center;font-size:1.3rem;">☕</div>
        @endif
      </td>
      <td style="font-weight:500;">{{ $prod->nama_produk }}</td>
      <td style="font-size:.83rem;">{{ $prod->category->name ?? '—' }}</td>
      <td>
        <span class="badge {{ $prod->type === 'cafe' ? 'badge-cafe' : 'badge-market' }}">
          {{ $prod->type }}
        </span>
      </td>
      <td>Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
      <td>
        <span style="{{ $prod->stok < 5 ? 'color:#c0392b;font-weight:500;' : '' }}">
          {{ $prod->stok }}
        </span>
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
              'type'        => $prod->type,
              'petani'      => $prod->petani,
              'gambar'      => $prod->gambar,
            ]) }})">Edit</button>
          <a href="{{ route('admin.products.delete', ['hapus' => $prod->id_product]) }}"
             class="btn-danger"
             onclick="return confirm('Hapus produk ini?')">Hapus</a>
        </div>
      </td>
    </tr>
    @empty
    <tr class="empty-row"><td colspan="7">Belum ada produk.</td></tr>
    @endforelse
  </tbody>
</table>

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
            <option value="">— Pilih —</option>
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
        <input type="text" name="petani" id="f-petani" placeholder="cth. Pak Budi — Gayo, Aceh">
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
<script>
function openModal(data) {
  const modal = document.getElementById('product-modal');
  if (data) {
    document.getElementById('modal-title').textContent = 'Edit Produk';
    document.getElementById('f-id').value          = data.id;
    document.getElementById('f-nama').value        = data.nama_produk;
    document.getElementById('f-harga').value       = data.harga;
    document.getElementById('f-stok').value        = data.stok;
    document.getElementById('f-desc').value        = data.deskripsi ?? '';
    document.getElementById('f-type').value        = data.type;
    document.getElementById('f-cat').value         = data.category_id ?? '';
    document.getElementById('f-petani').value      = data.petani ?? '';
    document.getElementById('f-gambar-lama').value = data.gambar ?? '';
    const prev = document.getElementById('img-preview');
    if (data.gambar) {
      prev.src = '/products/' + data.gambar;
      prev.style.display = 'block';
    } else {
      prev.style.display = 'none';
    }
  } else {
    document.getElementById('modal-title').textContent = 'Tambah Produk';
    document.getElementById('f-id').value = '';
    document.querySelector('#product-modal form').reset();
    document.getElementById('img-preview').style.display = 'none';
  }
  modal.classList.add('open');
}
function closeModal() {
  document.getElementById('product-modal').classList.remove('open');
}
function previewImg(input) {
  if (!input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('img-preview');
    img.src = e.target.result;
    img.style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
}
// Tutup modal klik luar
document.getElementById('product-modal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});
</script>
@endpush
