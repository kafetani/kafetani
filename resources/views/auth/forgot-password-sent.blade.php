<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Email Anda - Kafetani</title>
    <meta name="description" content="Link reset password telah dikirim ke email anda.">
    <link rel="icon" href="{{ asset_v('favicon.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style-forgot-password.css') }}">
</head>
<body>

    <div class="card">
        {{-- Judul halaman --}}
        <h2>Cek Email Anda</h2>

        {{-- Teks penjelasan --}}
        <p class="info-text">
            Jika email
            @if ($email)
                <strong>{{ $email }}</strong>
            @else
                tersebut
            @endif
            terdaftar, link reset password telah dikirim. Silakan cek kotak masuk (atau folder spam) email anda.
        </p>

        {{-- Link menuju halaman login --}}
        <p>Ingat password? <a href="{{ route('login') }}">Login di sini</a></p>

        {{-- Link kembali ke beranda --}}
        <a href="{{ url('/') }}" class="back-link">← Kembali ke Beranda</a>
    </div>

</body>
</html>
