<?php
session_start();
include '../config/koneksi.php';

$email    = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($conn,
    "SELECT * FROM users WHERE email='$email' AND password='$password'"
);

$data = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['nama']    = $data['nama'];
    $_SESSION['role']    = $data['role'];

    header("Location: ../index.php");
    exit;
} else {
    echo "Email atau Password Salah. <a href='login.php'>Kembali</a>";
}
?>
