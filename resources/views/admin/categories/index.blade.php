@extends('layouts.admin')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="page-header">
  <h1>Manajemen Kategori</h1>
  <a href="{{ route('admin.categories.create') }}" class="btn-primary">+ Tambah Kategori</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th>Nama</th>
      <th>Slug</th>
      <th>Jumlah Produk</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($categories as $category)
    <tr>
      <td style="font-weight:500;">{{ $category->name }}</td>
      <td style="font-size:.85rem;color:var(--ink3);">{{ $category->slug }}</td>
      <td>{{ $category->products_count }}</td>
      <td>
        <div style="display:flex;gap:.4rem;align-items:center;">
          <a href="{{ route('admin.categories.edit', $category) }}" class="btn-edit">Edit</a>
          <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                onsubmit="return confirm('Hapus kategori ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">Hapus</button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr class="empty-row">
      <td colspan="4">Belum ada data kategori.</td>
    </tr>
    @endforelse
  </tbody>
</table>
@endsection
