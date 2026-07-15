<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar sebagai Petani &middot; Kafetani</title>
<link rel="icon" type="image/svg+xml" href="{{ asset_v('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('auth.css') }}">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-logo">
    <a href="{{ route('home') }}">
      <img src="{{ asset_v('logo_v3.svg') }}" alt="Kafetani">
    </a>
  </div>
  <div class="auth-box">
    <h1 class="auth-title">Daftar sebagai Petani</h1>
    <p class="auth-sub">Jual produk segar anda langsung ke pelanggan Kafetani</p>

    @if($errors->any())
      <div class="alert-err">
        @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('register-petani.post') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap"
               value="{{ old('nama_lengkap') }}"
               placeholder="Nama lengkap anda"
               class="{{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
               autocomplete="name" required>
        @error('nama_lengkap')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="nama@email.com"
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
               autocomplete="email" required>
        @error('email')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="password-row">
          <input type="password" id="password" name="password"
                 placeholder="Minimal 6 karakter"
                 class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                 autocomplete="new-password" required>
          <button type="button" class="password-toggle" onclick="togglePwd('password', this)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>
        @error('password')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="konfirmasi_password">Konfirmasi Password</label>
        <div class="password-row">
          <input type="password" id="konfirmasi_password" name="konfirmasi_password"
                 placeholder="Ulangi password"
                 class="{{ $errors->has('konfirmasi_password') ? 'is-invalid' : '' }}"
                 autocomplete="new-password" required>
          <button type="button" class="password-toggle" onclick="togglePwd('konfirmasi_password', this)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>
        @error('konfirmasi_password')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="location">Lokasi / Domisili</label>
        <input type="text" id="location" name="location"
               value="{{ old('location') }}"
               placeholder="Contoh: Gayo, Aceh"
               class="{{ $errors->has('location') ? 'is-invalid' : '' }}"
               required>
        @error('location')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="contact">Kontak (opsional)</label>
        <input type="text" id="contact" name="contact"
               value="{{ old('contact') }}"
               placeholder="Nomor HP / WhatsApp">
        @error('contact')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="bio">Bio Singkat (opsional)</label>
        <textarea id="bio" name="bio" rows="3" placeholder="Ceritakan sedikit tentang usaha tani anda">{{ old('bio') }}</textarea>
        @error('bio')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="avatar">Foto Profil (opsional)</label>
        <input type="file" id="avatar" name="avatar" accept="image/*">
        @error('avatar')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-submit">Daftar sebagai Petani →</button>
    </form>

    <div class="auth-links auth-links--first">
      Ingin daftar sebagai pelanggan? <a href="{{ route('register') }}">Daftar di sini</a>
    </div>
    <div class="auth-links auth-links--first">
      Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
    <div class="auth-links auth-links--last">
      <a href="{{ route('home') }}">← Kembali ke Beranda</a>
    </div>
  </div>
</div>
<script src="{{ asset('auth.js') }}"></script>
</body>
</html>
