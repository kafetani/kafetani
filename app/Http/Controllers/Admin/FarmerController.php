<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FarmerController extends Controller
{
    /**
     * Daftar semua petani
     */
    public function index()
    {
        $farmers = Farmer::orderByDesc('created_at')->get();
        return view('admin.farmers.index', compact('farmers'));
    }

    /**
     * Form tambah petani baru
     */
    public function create()
    {
        return view('admin.farmers.form', ['farmer' => new Farmer(), 'action' => 'add']);
    }

    /**
     * Simpan petani baru
     */
    public function store(Request $request)
    {
        $data = $this->validateFarmer($request);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploadAvatar($request);
        }

        Farmer::create($data);

        return redirect()->route('admin.farmers.index')
                         ->with('success', 'Data petani berhasil ditambahkan!');
    }

    /**
     * Form edit petani
     */
    public function edit(Farmer $farmer)
    {
        return view('admin.farmers.form', ['farmer' => $farmer, 'action' => 'edit']);
    }

    /**
     * Update data petani
     */
    public function update(Request $request, Farmer $farmer)
    {
        $data = $this->validateFarmer($request);

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika bukan file default
            if ($farmer->avatar) {
                @unlink(public_path('img/farmers/' . $farmer->avatar));
            }
            $data['avatar'] = $this->uploadAvatar($request);
        }

        $farmer->update($data);

        return redirect()->route('admin.farmers.index')
                         ->with('success', 'Data petani berhasil diupdate!');
    }

    /**
     * Hapus petani
     */
    public function destroy(Farmer $farmer)
    {
        if ($farmer->avatar) {
            @unlink(public_path('img/farmers/' . $farmer->avatar));
        }

        $farmer->delete();

        return redirect()->route('admin.farmers.index')
                         ->with('success', 'Data petani berhasil dihapus!');
    }

    // ─── Private helpers ───────────────────────────────────────────────────────

    private function validateFarmer(Request $request): array
    {
        return $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'contact'  => ['nullable', 'string', 'max:50'],
            'bio'      => ['nullable', 'string'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function uploadAvatar(Request $request): string
    {
        $ext      = $request->file('avatar')->getClientOriginalExtension();
        $filename = uniqid('farmer_', true) . '.' . strtolower($ext);
        $request->file('avatar')->move(public_path('img/farmers'), $filename);
        return $filename;
    }
}
