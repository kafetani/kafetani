<?php
session_start();
include '../config/koneksi.php';

// mengambil input login
$email    = $_POST['email'];
$password = $_POST['password'];

// cek ke database
$query = mysqli_query($conn,
    "SELECT * FROM users WHERE email='$email' AND password='$password'"
);

// mengambil data user
$data = mysqli_fetch_assoc($query);

// jika data ditemukan
if ($data) {

    // simpan session
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['nama']    = $data['nama'];
    $_SESSION['name']    = $data['nama'];
    $_SESSION['role']    = $data['role'];

    // jika admin
    if ($data['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit;

// jika gagal login
} else {
    echo "Email atau Password Salah. <a href='login.php'>Kembali</a>";
}
?>
