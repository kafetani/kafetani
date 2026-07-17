<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterPetaniController extends Controller
{
    /**
     * Tampilkan form registrasi khusus Petani Lokal.
     * Registrasi ini membuat 1 akun users(role: petani) sekaligus 1 profil
     * farmers(user_id: ...) dalam satu transaksi (SRS Bab 2.3 & 6, FR-19).
     */
    public function showForm()
    {
        return view('auth.register-petani');
    }

    /**
     * Proses form registrasi petani.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap'        => ['required', 'string', 'max:100'],
            'email'               => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'            => ['required', Password::min(6)],
            'konfirmasi_password' => ['required', 'same:password'],
            'location'            => ['required', 'string', 'max:255'],
            'contact'             => ['nullable', 'string', 'max:50'],
            'bio'                 => ['nullable', 'string'],
            'avatar'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama_lengkap.required'        => 'Nama lengkap wajib diisi.',
            'email.required'                => 'Email wajib diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'email.unique'                  => 'Email sudah terdaftar. Gunakan email lain.',
            'password.required'             => 'Password wajib diisi.',
            'password.min'                  => 'Password minimal 6 karakter.',
            'konfirmasi_password.required'   => 'Konfirmasi password wajib diisi.',
            'konfirmasi_password.same'       => 'Password dan konfirmasi password tidak cocok.',
            'location.required'             => 'Lokasi/domisili wajib diisi.',
        ]);

        $user = DB::transaction(function () use ($request, $data) {
            $user = User::create([
                'nama'     => $data['nama_lengkap'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => 'petani',
            ]);

            $avatarFile = null;
            if ($request->hasFile('avatar')) {
                $ext        = $request->file('avatar')->getClientOriginalExtension();
                $avatarFile = uniqid('farmer_', true) . '.' . strtolower($ext);
                $request->file('avatar')->move(public_path('farmers'), $avatarFile);
            }

            Farmer::create([
                'user_id'  => $user->id,
                'name'     => $data['nama_lengkap'],
                'location' => $data['location'],
                'contact'  => $data['contact'] ?? null,
                'bio'      => $data['bio'] ?? null,
                'avatar'   => $avatarFile,
                // Akun baru menunggu verifikasi admin sebelum resmi masuk
                // jaringan (tampil di direktori petani/marketplace publik).
                'status'   => 'pending',
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('petani.dashboard')
                         ->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu verifikasi admin sebelum tampil di direktori petani publik.');
    }
}
