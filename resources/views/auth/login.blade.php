<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Kafetani</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset_v('favicon.svg') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('auth.css') }}">
</head>

<body>
    {{-- Kontainer box login --}}
    <div class="auth-box">
      {{-- Judul dan subjudul halaman --}}
      <h1 class="auth-title">Login</h1>
      <p class="auth-sub">Selamat datang kembali di Kafetani</p>

      {{-- Pesan error validasi --}}
      @if($errors->any())
      <div class="alert-err">{{ $errors->first() }}</div>
      @endif

      {{-- Form login utama --}}
      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- Input email --}}
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email"
            value="{{ old('email') }}"
            placeholder="nama@email.com"
            autocomplete="email" required>
        </div>

        {{-- Input password dan lupa password --}}
        <div class="form-group">
          <div class="password-label-row">
            <label for="password">Password</label>
            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
          </div>
          <div class="password-row">
            <input type="password" id="password" name="password"
              placeholder="Masukkan password"
              autocomplete="current-password" required>
            {{-- Tombol lihat password --}}
            <button type="button" class="password-toggle" onclick="togglePwd('password', this)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg></button>
          </div>
        </div>

        {{-- Tombol submit login --}}
        <button type="submit" class="btn-submit">Masuk →</button>
      </form>

      {{-- Pemisah antar metode login --}}
      <div class="divider">atau</div>

      {{-- Tombol login via Google --}}
      <a href="{{ route('auth.google.redirect') }}" class="btn-google">
        <img src="{{ asset_v('google-symbol.png') }}" alt="Google">
        Masuk dengan Google
      </a>

      {{-- Link menuju halaman daftar --}}
      <div class="auth-links auth-links--first">
        Belum punya akun? <a href="{{ route('register') }}">Daftar Gratis</a>
      </div>
      {{-- Link kembali ke beranda --}}
      <div class="auth-links auth-links--last">
        <a href="{{ route('home') }}">← Kembali ke Beranda</a>
      </div>
    </div>
  </div>
  <script src="{{ asset('auth.js') }}"></script>
</body>

</html>
