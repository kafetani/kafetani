<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kafetani</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style-auth.css') }}">
</head>
<body>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <h2>Daftar Akun</h2>

        {{-- Pesan error validasi dari Laravel --}}
        @if ($errors->any())
            <p style="color:red; font-size:.9rem;">{{ $errors->first() }}</p>
        @endif

        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap"
               value="{{ old('nama_lengkap') }}"
               required
               placeholder="Masukkan Nama Lengkap"
               autocomplete="name">
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               required
               placeholder="example@gmail.com"
               autocomplete="email">
        <br>

        <label for="password">Password:</label>
        <div class="password-row">
            <input type="password" id="password" name="password"
                   required
                   placeholder="Masukkan Password"
                   autocomplete="new-password">
            <button type="button" onclick="togglePassword()">👁</button>
        </div>

        <label for="konfirmasi_password">Konfirmasi Password:</label>
        <div class="password-row">
            <input type="password" id="konfirmasi_password" name="konfirmasi_password"
                   required
                   placeholder="Konfirmasi Password"
                   autocomplete="new-password">
            <button type="button" onclick="toggleKonfirmasiPassword()">👁</button>
        </div>

        <input type="submit" value="Daftar Sekarang">

        <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
        <p><a href="{{ url('/') }}">← Kembali ke Beranda</a></p>
    </form>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
