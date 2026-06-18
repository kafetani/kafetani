<!DOCTYPE html>
<html>
<head>
    <title>Register - Kafetani</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/css/style-auth.css">
</head>
<body>
    <form action="proses_register.php" method="post">
        <h2>Daftar Akun</h2>

        <?php if (!empty($error)): ?>
            <p style="color:red;font-size:.9rem;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required
               placeholder="Masukkan Nama Lengkap" autocomplete="name"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required
               placeholder="example@gmail.com" autocomplete="email"><br>

        <label for="password">Password:</label>
        <div class="password-row">
            <input type="password" id="password" name="password" required
                   placeholder="Masukkan Password" autocomplete="new-password">
            <button type="button" onclick="togglePassword()">👁</button>
        </div>

        <label for="konfirmasi_password">Konfirmasi Password:</label>
        <div class="password-row">
            <input type="password" id="konfirmasi_password" name="konfirmasi_password" required
                   placeholder="Konfirmasi Password" autocomplete="new-password">
            <button type="button" onclick="toggleKonfirmasiPassword()">👁</button>
        </div>

        <input type="submit" value="Daftar Sekarang">

        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        <p><a href="../index.php">← Kembali ke Beranda</a></p>
    </form>
    <script src="../assets/js/script.js"></script>
</body>
</html>
