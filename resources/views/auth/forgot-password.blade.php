<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Kafetani</title>
    <meta name="description" content="Reset password akun Kafetani anda.">
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
            Masukkan email yang terdaftar. Kami akan mengirimkan link untuk mengatur ulang password anda.
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

        {{-- Link menuju halaman login --}}
        <p>Ingat password? <a href="{{ route('login') }}">Login di sini</a></p>

        {{-- Link kembali ke beranda --}}
        <a href="{{ url('/') }}" class="back-link">← Kembali ke Beranda</a>
    </form>

</body>
</html>
