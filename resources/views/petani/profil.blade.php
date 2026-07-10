@extends('layouts.petani')
@section('title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-produk.css') }}">
@endpush

@section('content')
<div class="page-header">
  <h1>Profil Saya</h1>
</div>

<div class="modal-box" style="max-width:640px;margin:0;">
  <form method="POST" action="{{ route('petani.profil.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group" style="display:flex;align-items:center;gap:1rem;">
      <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;background:var(--cream2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <img id="img-preview" src="{{ $farmer->avatar_url }}" alt="{{ $farmer->name }}" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div style="flex:1;">
        <label>Foto Profil</label>
        <input type="file" name="avatar" accept="image/*" onchange="previewImg(this)">
      </div>
    </div>

    <div class="form-group">
      <label>Nama *</label>
      <input type="text" name="name" value="{{ old('name', $farmer->name) }}" required>
      @error('name')<div class="field-err">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label>Lokasi *</label>
      <input type="text" name="location" value="{{ old('location', $farmer->location) }}" required>
      @error('location')<div class="field-err">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label>Kontak</label>
      <input type="text" name="contact" value="{{ old('contact', $farmer->contact) }}">
    </div>

    <div class="form-group">
      <label>Bio</label>
      <textarea name="bio" rows="4">{{ old('bio', $farmer->bio) }}</textarea>
    </div>

    <div class="modal-actions">
      <button type="submit" class="btn-primary">Simpan Perubahan →</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function previewImg(input) {
  if (!input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => { document.getElementById('img-preview').src = e.target.result; };
  reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
