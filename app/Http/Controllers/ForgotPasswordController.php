<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim email reset password berisi link ke halaman reset.
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Selalu tampilkan pesan yang sama, ada/tidaknya user, agar tidak bocorkan
        // email mana yang terdaftar (user enumeration).
        $status = 'Jika email tersebut terdaftar, link reset password telah dikirim. Silakan cek email Anda.';

        if (!$user) {
            return back()->with('status', $status);
        }

        // Buat token reset
        $token = Str::random(64);

        // Simpan ke tabel password_reset_tokens (Laravel bawaan)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email'      => $request->email,
                'token'      => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // Kirim link reset ke email pengguna (route() otomatis pakai APP_URL,
        // jadi link ikut domain production tanpa perlu hardcode).
        Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

        return back()->with('status', $status);
    }

    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('password.request')
                             ->with('error', 'Link reset tidak valid atau sudah kadaluarsa.');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Proses reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email'                => ['required', 'email'],
            'token'                => ['required'],
            'password'             => ['required', 'min:6'],
            'konfirmasi_password'  => ['required', 'same:password'],
        ], [
            'email.required'               => 'Email wajib diisi.',
            'token.required'               => 'Token tidak valid.',
            'password.required'            => 'Password baru wajib diisi.',
            'password.min'                 => 'Password minimal 6 karakter.',
            'konfirmasi_password.required' => 'Konfirmasi password wajib diisi.',
            'konfirmasi_password.same'     => 'Password dan konfirmasi tidak cocok.',
        ]);

        // Cari record token di DB
        $record = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        // Token expired setelah 60 menit
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'Token sudah kadaluarsa. Minta link baru.']);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Hapus token setelah dipakai
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Bersihkan session
        session()->forget(['reset_token', 'reset_email']);

        return redirect()->route('login')
                         ->with('status', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
