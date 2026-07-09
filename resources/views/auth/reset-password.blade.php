<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Kafetani</title>
    <meta name="description" content="Buat password baru untuk akun Kafetani Anda.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset_v('style-auth.css') }}">
    <link rel="stylesheet" href="{{ asset_v('style-reset-password.css') }}">
</head>
<body>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        {{-- Token dan email hidden --}}
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <a href="{{ url('/') }}" class="auth-logo">
            <img src="{{ asset('logo_v3.svg') }}" alt="Kafetani">
        </a>

        <h2>Reset Password</h2>

        {{-- Error --}}
        @if ($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <label>Email:</label>
        <input type="text" value="{{ $email }}" disabled style="opacity:.6; cursor:not-allowed;">

        <label for="password">Password Baru:</label>
        <div class="password-row">
            <input type="password" id="password" name="password"
                   required
                   placeholder="Minimal 6 karakter"
                   autocomplete="new-password"
                   oninput="checkStrength(this.value)">
            <button type="button" onclick="togglePwd('password', this)" title="Tampilkan"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>
        <div class="password-strength" id="pw-strength"></div>

        <label for="konfirmasi_password">Konfirmasi Password Baru:</label>
        <div class="password-row">
            <input type="password" id="konfirmasi_password" name="konfirmasi_password"
                   required
                   placeholder="Ulangi password baru"
                   autocomplete="new-password">
            <button type="button" onclick="togglePwd('konfirmasi_password', this)" title="Tampilkan"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>

        <input type="submit" value="Simpan Password Baru">

        <a href="{{ url('/') }}" class="back-link">← Kembali ke Beranda</a>
    </form>

    <script src="{{ asset_v('auth.js') }}"></script>
</body>
</html>
