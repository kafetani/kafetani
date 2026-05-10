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

        <!-- Input Nama Lengkap -->
        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required placeholder="Masukkan Nama Lengkap"><br>

        <!-- Input Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="example@gmail.com"><br>

        <!-- Input Password -->
        <label for="password">Password:</label>

        <div class="password-row">
            <input type="password" id="password" name="password" required placeholder="Masukkan Password">
            <button type="button" onclick="togglePassword()">👁</button>
        </div>

        <!-- Input Konfirmasi Password -->
        <label for="konfirmasi_password">Konfirmasi Password:</label>
        
        <div class="password-row">
            <input type="password" id="konfirmasi_password" name="konfirmasi_password" required placeholder="Konfirmasi Password">
            <button type="button" onclick="toggleKonfirmasiPassword()">👁</button>
        </div>

        <!-- Tombol submit -->
        <input type="submit" value="Daftar Sekarang">

        <!-- Link ke login -->
        <p>
            Sudah punya akun? 
            <a href="login.php">Login di sini</a>
        </p>

        <!-- Kembali -->
        <p><a href="../index.php">← Kembali ke Beranda</a></p>

    </form>
    <script src="../assets/js/script.js"></script>
</body>
</html>
