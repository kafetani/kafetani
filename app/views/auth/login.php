<!DOCTYPE html>
<html>
<head>
    <title>Login - Kafetani</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/css/style-auth.css">
</head>
<body>
    <form action="proses_login.php" method="post">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <p style="color:red;font-size:.9rem;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required
               placeholder="Masukkan Email" autocomplete="email"><br><br>

        <div class="password-row">
            <label for="password">Password:</label>
            <a href="forgot_password.php" class="forgot">Lupa password?</a>
        </div>
        <div class="password-row">
            <input type="password" id="password" name="password" required
                   placeholder="Masukkan Password" autocomplete="current-password">
            <button type="button" onclick="togglePassword()">👁</button>
        </div>

        <input type="submit" value="Masuk">
        <p>Atau</p>

        <a href="google_login.php" class="google-btn">
            <img src="../assets/img/google-symbol.png">
            <span>Login dengan Google</span>
        </a>

        <p>Belum punya akun? <a href="register.php">Daftar Gratis</a></p>
        <p><a href="../index.php">← Kembali ke Beranda</a></p>
    </form>
    <script src="../assets/js/script.js"></script>
</body>
</html>
