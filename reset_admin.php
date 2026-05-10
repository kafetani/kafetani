<?php
include 'config/koneksi.php';

// Hapus admin lama, insert ulang dengan password plain text
mysqli_query($conn, "DELETE FROM users WHERE email='admin@gmail.com'");

$query = "INSERT INTO users (nama, email, password, role)
          VALUES ('Administrator', 'admin@gmail.com', 'admin123', 'admin')";

if (mysqli_query($conn, $query)) {
    echo "<h2>✅ Berhasil!</h2>";
    echo "<p>Admin telah di-reset.</p>";
    echo "<p><strong>Email:</strong> admin@gmail.com</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<p><a href='auth/login.php'>→ Ke Halaman Login</a></p>";
    echo "<hr><p style='color:red'>⚠️ Hapus file <strong>reset_admin.php</strong> ini setelah login berhasil!</p>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>
