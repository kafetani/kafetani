<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Tampilkan form register
     */
    public function showForm()
    {
        return view('auth.register');
    }

    /**
     * Proses form register
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap'         => ['required', 'string', 'max:100'],
            'email'                => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'             => ['required', Password::min(6)],
            'konfirmasi_password'  => ['required', 'same:password'],
        ], [
            'nama_lengkap.required'        => 'Nama lengkap wajib diisi.',
            'email.required'               => 'Email wajib diisi.',
            'email.email'                  => 'Format email tidak valid.',
            'email.unique'                 => 'Email sudah terdaftar. Gunakan email lain.',
            'password.required'            => 'Password wajib diisi.',
            'password.min'                 => 'Password minimal 6 karakter.',
            'konfirmasi_password.required' => 'Konfirmasi password wajib diisi.',
            'konfirmasi_password.same'     => 'Password dan konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'nama'     => $data['nama_lengkap'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user',
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
