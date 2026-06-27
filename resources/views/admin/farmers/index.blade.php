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
            <img src="{{ asset('farmers/' . $farmer->avatar) }}"
                 style="width:100%;height:100%;object-fit:cover;"
                 alt="{{ $farmer->name }}">
          @else
            <span>👨‍🌾</span>
          @endif
        </div>
      </td>
      <td style="font-weight:500;">{{ $farmer->name }}</td>
      <td style="font-size:.85rem;">{{ $farmer->location }}</td>
      <td style="font-size:.85rem;">{{ $farmer->contact ?? '—' }}</td>
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
