@extends('admin.layouts.admin')

@section('title', 'Manajemen Petani')

@section('content')

    {{-- Header --}}
    <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
        <h1 style="font-family:var(--ff-display); font-size:2.2rem; color:var(--brown);">
            Manajemen Petani
        </h1>
        <a href="{{ route('admin.farmers.create') }}"
           style="text-decoration:none; padding:.8rem 1.5rem; background:var(--green);
                  color:white; border-radius:2px; font-size:0.9rem;">
            + Tambah Petani
        </a>
    </header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div style="background:#edf7ee; color:#2d5016; padding:1rem; margin-bottom:1.5rem; border:1px solid #d4e8d5;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fcebea; color:#c0392b; padding:1rem; margin-bottom:1.5rem; border:1px solid #f5d1cf;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabel Petani --}}
    <table style="width:100%; background:#fff; border-collapse:collapse; border:1px solid var(--border);">
        <thead style="background:var(--cream2); text-align:left;">
            <tr>
                <th style="padding:1rem; font-size:.85rem; border-bottom:1px solid var(--border);">Avatar</th>
                <th style="padding:1rem; font-size:.85rem; border-bottom:1px solid var(--border);">Nama</th>
                <th style="padding:1rem; font-size:.85rem; border-bottom:1px solid var(--border);">Lokasi</th>
                <th style="padding:1rem; font-size:.85rem; border-bottom:1px solid var(--border);">Kontak</th>
                <th style="padding:1rem; font-size:.85rem; border-bottom:1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($farmers as $farmer)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:1rem;">
                        <div style="width:40px; height:40px; border-radius:50%; background:var(--cream2);
                                    display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            @if ($farmer->avatar)
                                <img src="{{ asset('assets/img/farmers/' . $farmer->avatar) }}"
                                     style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            @endif
                        </div>
                    </td>
                    <td style="padding:1rem; font-weight:500;">{{ $farmer->name }}</td>
                    <td style="padding:1rem; font-size:.85rem;">{{ $farmer->location }}</td>
                    <td style="padding:1rem; font-size:.85rem;">{{ $farmer->contact }}</td>
                    <td style="padding:1rem;">
                        <a href="{{ route('admin.farmers.edit', $farmer->id) }}"
                           style="color:var(--green); font-size:.8rem; text-decoration:none; margin-right:.8rem;">
                            Edit
                        </a>

                        {{-- Form DELETE pakai method spoofing Laravel --}}
                        <form action="{{ route('admin.farmers.destroy', $farmer->id) }}"
                              method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Hapus data petani ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="background:none; border:none; color:#c0392b;
                                           font-size:.8rem; cursor:pointer; padding:0;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:2rem; text-align:center; color:var(--text-light);">
                        Belum ada data petani.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection
