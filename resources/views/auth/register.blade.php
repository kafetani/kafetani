<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar  Kafetani</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<!-- <style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--cream:#F7F3EC;--brown:#3B2A1A;--green:#2D5016;--green2:#4A7C23;--text:#2A1F12;--text-mid:#7A6550;--border:#D9CEBC;--ff-display:'Cormorant Garamond',serif;--ff-body:'DM Sans',sans-serif}
body{background:var(--cream);font-family:var(--ff-body);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem}
.auth-wrap{width:100%;max-width:420px}
.auth-logo{text-align:center;margin-bottom:2rem}
.auth-logo img{height:40px}
.auth-box{background:#fff;border:1px solid var(--border);padding:2.5rem}
.auth-title{font-family:var(--ff-display);font-size:2rem;font-weight:300;color:var(--brown);margin-bottom:.4rem}
.auth-sub{font-size:.82rem;color:var(--text-mid);margin-bottom:1.8rem}
.form-group{margin-bottom:1.1rem}
.form-group label{display:block;font-size:.8rem;font-weight:500;color:var(--text-mid);margin-bottom:.4rem}
.form-group input{width:100%;padding:.65rem .8rem;border:1px solid var(--border);font-family:var(--ff-body);font-size:.9rem;color:var(--text);background:var(--cream);outline:none;transition:border-color .15s}
.form-group input:focus{border-color:var(--green)}
.form-group input.is-invalid{border-color:#c0392b}
.password-row{position:relative}
.password-row input{padding-right:2.8rem}
.password-toggle{position:absolute;right:.7rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-mid);font-size:1rem}
.btn-submit{width:100%;background:var(--green);color:#fff;border:none;padding:.85rem;font-family:var(--ff-body);font-size:.9rem;font-weight:500;cursor:pointer;letter-spacing:.04em;transition:background .2s;margin-top:1.2rem}
.btn-submit:hover{background:var(--green2)}
.auth-links{text-align:center;margin-top:1.2rem;font-size:.82rem;color:var(--text-mid)}
.auth-links a{color:var(--green);text-decoration:none}
.auth-links a:hover{text-decoration:underline}
.alert-err{background:#FCEBEB;border-left:3px solid #c0392b;color:#7b2d1e;padding:.7rem .9rem;font-size:.83rem;margin-bottom:1.2rem}
.field-err{font-size:.75rem;color:#c0392b;margin-top:.25rem}
</style> -->
</head>
<body>
<div class="auth-wrap">
  <div class="auth-logo">
    <a href="{{ route('home') }}">
      <img src="{{ asset('logo_v3.svg') }}" alt="Kafetani">
    </a>
  </div>
  <div class="auth-box">
    <h1 class="auth-title">Daftar Akun</h1>
    <p class="auth-sub">Bergabung dengan komunitas Kafetani</p>

    @if($errors->any())
      <div class="alert-err">
        @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
      @csrf

      <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap"
               value="{{ old('nama_lengkap') }}"
               placeholder="Nama lengkap kamu"
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
          <button type="button" class="password-toggle" onclick="togglePwd('password', this)">👁</button>
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
          <button type="button" class="password-toggle" onclick="togglePwd('konfirmasi_password', this)">👁</button>
        </div>
        @error('konfirmasi_password')<div class="field-err">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-submit">Daftar Sekarang →</button>
    </form>

    <div class="auth-links" style="margin-top:1.5rem">
      Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
    <div class="auth-links" style="margin-top:.6rem">
      <a href="{{ route('home') }}">← Kembali ke Beranda</a>
    </div>
  </div>
</div>
<script>
function togglePwd(id, btn) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
</script>
</body>
</html>
