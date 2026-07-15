<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Kafetani</title>
    <meta name="description" content="Reset password akun Kafetani Anda.">
    <link rel="icon" href="{{ asset_v('favicon.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style-forgot-password.css') }}">
</head>
<body>

    {{-- Form kirim link reset --}}
    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        {{-- Judul halaman --}}
        <h2>Lupa Password</h2>

        {{-- Teks penjelasan instruksi --}}
        <p class="info-text">
            Masukkan email yang terdaftar. Kami akan mengirimkan link untuk mengatur ulang password Anda.
        </p>

        {{-- Status sukses --}}
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        {{-- Pesan error tambahan session --}}
        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        {{-- Input email pengguna --}}
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               required
               placeholder="Masukkan Email"
               autocomplete="email">

        {{-- Tombol submit form --}}
        <input type="submit" value="Kirim Link Reset">

        {{-- DEBUG: tampilkan token & link reset (hapus di produksi) --}}
        @if (session('debug_token'))
            <div class="debug-token">
                <strong><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-.15em;display:inline-block"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Mode Development:</strong> Token reset berhasil dibuat.<br>
                <a href="{{ route('password.reset.form', ['token' => session('debug_token'), 'email' => old('email', session('reset_email'))]) }}">
                    → Klik di sini untuk lanjut ke halaman reset password
                </a>
                <br><small>(Hapus blok debug ini di produksi dan ganti dengan email SMTP)</small>
            </div>
        @endif

        {{-- Link menuju halaman login --}}
        <p>Ingat password? <a href="{{ route('login') }}">Login di sini</a></p>

        {{-- Link kembali ke beranda --}}
        <a href="{{ url('/') }}" class="back-link">← Kembali ke Beranda</a>
    </form>

</body>
</html>
