@extends('layouts.admin')
@section('title', 'Manajemen Petani')

@section('content')
<div class="page-header">
  <h1>Manajemen Petani</h1>
  <a href="{{ route('admin.farmers.create') }}" class="btn-primary">+ Tambah Petani</a>
</div>

<table class="data-table">
  <thead>
    <tr>
      <th>Avatar</th>
      <th>Nama</th>
      <th>Lokasi</th>
      <th>Kontak</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($farmers as $farmer)
    <tr>
      <td>
        <div style="width:40px;height:40px;border-radius:50%;overflow:hidden;background:var(--cream2);display:flex;align-items:center;justify-content:center;">
          @if($farmer->avatar)
            <img src="{{ asset_v('farmers/' . $farmer->avatar) }}"
                 style="width:100%;height:100%;object-fit:cover;"
                 alt="{{ $farmer->name }}">
          @else
            <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
          @endif
        </div>
      </td>
      <td style="font-weight:500;">{{ $farmer->name }}</td>
      <td style="font-size:.85rem;">{{ $farmer->location }}</td>
      <td style="font-size:.85rem;">{{ $farmer->contact ?? '' }}</td>
      <td>
        <div style="display:flex;gap:.4rem;align-items:center;">
          <a href="{{ route('admin.farmers.edit', $farmer) }}" class="btn-edit">Edit</a>
          <form method="POST" action="{{ route('admin.farmers.destroy', $farmer) }}"
                onsubmit="return confirm('Hapus data petani ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">Hapus</button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr class="empty-row">
      <td colspan="5">Belum ada data petani.</td>
    </tr>
    @endforelse
  </tbody>
</table>
@endsection
