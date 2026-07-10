@extends('layouts.admin')
@section('title', $action === 'edit' ? 'Edit Petani' : 'Tambah Petani')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-farmer-form.css') }}">
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
            <span id="avatar-fallback"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
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
<script src="{{ asset('script-farmer-form.js') }}"></script>
@endpush
