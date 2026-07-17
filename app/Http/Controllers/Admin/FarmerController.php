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
        $farmers     = Farmer::orderByDesc('created_at')->get();
        $pendingCount = Farmer::pending()->count();
        return view('admin.farmers.index', compact('farmers', 'pendingCount'));
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

        // Petani yang ditambahkan langsung oleh admin dianggap otomatis
        // terverifikasi — admin sudah memvalidasi sendiri saat menginput.
        $data['status'] = 'approved';

        Farmer::create($data);

        return redirect()->route('admin.farmers.index')
                         ->with('success', 'Data petani berhasil ditambahkan!');
    }

    /**
     * Verifikasi kemitraan petani: setujui akun petani yang mendaftar sendiri
     * agar resmi masuk jaringan dan tampil di direktori/marketplace publik.
     */
    public function approve(Farmer $farmer)
    {
        $farmer->update(['status' => 'approved']);

        return redirect()->back()
                         ->with('success', "Kemitraan \"{$farmer->name}\" telah diverifikasi dan resmi masuk jaringan.");
    }

    /**
     * Tolak kemitraan petani: akun tetap ada tapi tidak tampil di halaman publik.
     */
    public function reject(Farmer $farmer)
    {
        $farmer->update(['status' => 'rejected']);

        return redirect()->back()
                         ->with('success', "Kemitraan \"{$farmer->name}\" ditolak dan tidak akan tampil di direktori publik.");
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
                @unlink(public_path('farmers/' . $farmer->avatar));
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
            @unlink(public_path('farmers/' . $farmer->avatar));
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
        $request->file('avatar')->move(public_path('farmers'), $filename);
        return $filename;
    }
}
