<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Reset Password Kafetani</title>
</head>
<body style="margin:0;padding:24px;background:#f7f2ec;font-family:Arial, Helvetica, sans-serif;color:#222;">
  <div style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:8px;padding:32px;">
    <h2 style="margin-top:0;color:#2f6b3a;">Reset Password Kafetani</h2>

    <p>Halo,</p>
    <p>Kami menerima permintaan untuk reset password akun Kafetani yang terdaftar dengan email ini.</p>
    <p>Klik tombol di bawah untuk membuat password baru. Link ini berlaku selama <strong>60 menit</strong>.</p>

    <p style="text-align:center;margin:32px 0;">
      <a href="{{ $resetUrl }}"
         style="background:#2f6b3a;color:#ffffff;padding:12px 28px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:bold;">
        Reset Password
      </a>
    </p>

    <p>Kalau tombol di atas tidak berfungsi, salin dan buka link berikut di browser:</p>
    <p style="word-break:break-all;color:#2f6b3a;font-size:14px;">{{ $resetUrl }}</p>

    <p>Kalau kamu tidak merasa meminta reset password, abaikan saja email ini. Password akun kamu tidak akan berubah.</p>

    <hr style="margin:28px 0;border:none;border-top:1px solid #eee;">
    <p style="font-size:12px;color:#999;margin:0;">Email ini dikirim otomatis oleh sistem Kafetani, mohon tidak membalas email ini.</p>
  </div>
</body>
</html>
