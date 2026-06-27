@extends('layouts.admin')
@section('title', $action === 'edit' ? 'Edit Petani' : 'Tambah Petani')

@push('styles')
<style>
.form-card{background:#fff;border:1px solid var(--border);padding:2rem;max-width:620px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem}
.form-group{margin-bottom:1.2rem}
.form-group.full{grid-column:1/-1}
.form-group label{display:block;font-size:.8rem;font-weight:500;color:var(--text-mid);margin-bottom:.4rem;letter-spacing:.02em}
.form-group input,.form-group textarea,.form-group select{
  width:100%;padding:.65rem .8rem;border:1px solid var(--border);
  font-family:var(--ff-body);font-size:.9rem;color:var(--text);
  background:var(--cream);outline:none;transition:border-color .15s;
}
.form-group input:focus,.form-group textarea:focus{border-color:var(--green)}
.form-group textarea{resize:vertical;min-height:90px}
.field-err{font-size:.75rem;color:#c0392b;margin-top:.3rem}
.avatar-preview{width:64px;height:64px;border-radius:50%;overflow:hidden;background:var(--cream2);margin-bottom:.6rem;display:flex;align-items:center;justify-content:center;font-size:1.8rem}
.avatar-preview img{width:100%;height:100%;object-fit:cover}
.form-actions{display:flex;gap:.8rem;align-items:center;margin-top:1.5rem}
.btn-back{font-size:.85rem;color:var(--text-mid);text-decoration:none}
.btn-back:hover{color:var(--green)}
</style>
@endpush

@section('content')
<div class="page-header">
  <h1>{{ $action === 'edit' ? 'Edit Petani' : 'Tambah Petani Baru' }}</h1>
  <a href="{{ route('admin.farmers.index') }}" class="btn-edit">← Kembali</a>
</div>

<div class="form-card">
  <form
    method="POST"
    action="{{ $action === 'edit' ? route('admin.farmers.update', $farmer) : route('admin.farmers.store') }}"
    enctype="multipart/form-data"
  >
    @csrf
    @if($action === 'edit') @method('PUT') @endif

    <div class="form-grid">

      {{-- Avatar --}}
      <div class="form-group full">
        <label>Avatar</label>
        <div class="avatar-preview" id="avatar-preview">
          @if($farmer->avatar)
            <img id="avatar-img" src="{{ asset('farmers/' . $farmer->avatar) }}" alt="">
          @else
            <span id="avatar-fallback">👨‍🌾</span>
            <img id="avatar-img" src="" alt="" style="display:none;">
          @endif
        </div>
        <input type="file" name="avatar" id="avatar-input" accept="image/*"
               onchange="previewAvatar(this)">
        <div class="field-err">{{ $errors->first('avatar') }}</div>
      </div>

      {{-- Nama --}}
      <div class="form-group">
        <label for="name">Nama Petani <span style="color:#c0392b">*</span></label>
        <input type="text" id="name" name="name"
               value="{{ old('name', $farmer->name) }}"
               placeholder="cth. Pak Budi" required>
        @error('name')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      {{-- Kontak --}}
      <div class="form-group">
        <label for="contact">No. Kontak</label>
        <input type="text" id="contact" name="contact"
               value="{{ old('contact', $farmer->contact) }}"
               placeholder="cth. 0812-3456-7890">
        @error('contact')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      {{-- Lokasi --}}
      <div class="form-group full">
        <label for="location">Lokasi <span style="color:#c0392b">*</span></label>
        <input type="text" id="location" name="location"
               value="{{ old('location', $farmer->location) }}"
               placeholder="cth. Gayo, Aceh" required>
        @error('location')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      {{-- Bio --}}
      <div class="form-group full">
        <label for="bio">Bio Singkat</label>
        <textarea id="bio" name="bio" placeholder="Ceritakan tentang petani ini...">{{ old('bio', $farmer->bio) }}</textarea>
        @error('bio')<div class="field-err">{{ $message }}</div>@enderror
      </div>

    </div>

    <div class="form-actions">
      <button type="submit" class="btn-primary">
        {{ $action === 'edit' ? 'Simpan Perubahan' : 'Tambahkan Petani' }} →
      </button>
      <a href="{{ route('admin.farmers.index') }}" class="btn-back">Batal</a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('avatar-img');
    const fb  = document.getElementById('avatar-fallback');
    img.src = e.target.result;
    img.style.display = 'block';
    if (fb) fb.style.display = 'none';
  };
  reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
