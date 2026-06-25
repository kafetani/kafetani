<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kafetani</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style-auth.css') }}">
</head>
<body>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <h2>Login</h2>

        {{-- Pesan error validasi dari Laravel --}}
        @if ($errors->any())
            <p style="color:red; font-size:.9rem;">{{ $errors->first() }}</p>
        @endif

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               required
               placeholder="Masukkan Email"
               autocomplete="email">
        <br><br>

        <div class="password-row">
            <label for="password">Password:</label>
            <a href="{{ route('password.request') }}" class="forgot">Lupa password?</a>
        </div>
        <div class="password-row">
            <input type="password" id="password" name="password"
                   required
                   placeholder="Masukkan Password"
                   autocomplete="current-password">
            <button type="button" onclick="togglePassword()">👁</button>
        </div>

        <input type="submit" value="Masuk">
        <p>Atau</p>

        <a href="{{ route('auth.google') }}" class="google-btn">
            <img src="{{ asset('assets/img/google-symbol.png') }}">
            <span>Login dengan Google</span>
        </a>

        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar Gratis</a></p>
        <p><a href="{{ url('/') }}">← Kembali ke Beranda</a></p>
    </form>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
