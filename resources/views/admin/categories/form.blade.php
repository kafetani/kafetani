@extends('layouts.admin')
@section('title', $action === 'edit' ? 'Edit Kategori' : 'Tambah Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('style-farmer-form.css') }}">
@endpush

@section('content')
<div class="page-header">
  <h1>{{ $action === 'edit' ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h1>
  <a href="{{ route('admin.categories.index') }}" class="btn-edit">← Kembali</a>
</div>

<div class="form-card">
  <form
    method="POST"
    action="{{ $action === 'edit' ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
  >
    @csrf
    @if($action === 'edit') @method('PUT') @endif

    <div class="form-grid">
      {{-- Nama --}}
      <div class="form-group full">
        <label for="name">Nama Kategori <span style="color:#c0392b">*</span></label>
        <input type="text" id="name" name="name"
               value="{{ old('name', $category->name) }}"
               placeholder="cth. Kopi, Pastry, Bahan Baku" required>
        @error('name')<div class="field-err">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-primary">
        {{ $action === 'edit' ? 'Simpan Perubahan' : 'Tambahkan Kategori' }} →
      </button>
      <a href="{{ route('admin.categories.index') }}" class="btn-back">Batal</a>
    </div>
  </form>
</div>
@endsection
