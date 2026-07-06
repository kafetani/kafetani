<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Kafetani</title>
    <meta name="description" content="Reset password akun Kafetani Anda.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style-auth.css') }}">
    <style>
        .auth-logo { display: block; text-align: center; margin-bottom: 1.5rem; }
        .auth-logo img { height: 36px; }
        .alert-success {
            background: #edf7ee;
            color: #2d5016;
            border: 1px solid #d4e8d5;
            padding: .75rem 1rem;
            font-size: .85rem;
            margin-bottom: 1rem;
            text-align: left;
            border-left: 3px solid #2D5016;
        }
        .alert-error {
            background: #fcebea;
            color: #c0392b;
            border: 1px solid #f5d1cf;
            padding: .75rem 1rem;
            font-size: .85rem;
            margin-bottom: 1rem;
            text-align: left;
            border-left: 3px solid #c0392b;
        }
        .info-text {
            font-size: .85rem;
            color: #7A6550;
            text-align: left;
            margin-bottom: 1.2rem;
            line-height: 1.6;
        }
        .back-link { display: block; text-align: center; margin-top: 1rem; font-size: .8rem; color: #A9967E; }

        /* Debug token — tampilkan di dev, hapus di produksi */
        .debug-token {
            background: #fffbea;
            border: 1px solid #f5e79e;
            border-left: 3px solid #f39c12;
            padding: .75rem 1rem;
            margin-top: 1rem;
            font-size: .8rem;
            color: #7A6550;
            text-align: left;
            word-break: break-all;
        }
        .debug-token strong { color: #3B2A1A; }
        .debug-token a { color: #2D5016; font-weight: 500; }
    </style>
</head>
<body>

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <a href="{{ url('/') }}" class="auth-logo">
            <img src="{{ asset('logo_v3.svg') }}" alt="Kafetani">
        </a>

        <h2>Lupa Password</h2>

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

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               required
               placeholder="Masukkan Email"
               autocomplete="email">

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

        <p>Ingat password? <a href="{{ route('login') }}">Login di sini</a></p>

        <a href="{{ url('/') }}" class="back-link">← Kembali ke Beranda</a>
    </form>

</body>
</html>
