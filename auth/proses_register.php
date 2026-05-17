<?php
include '../config/koneksi.php';

$nama_lengkap = $_POST['nama_lengkap'];
$email        = $_POST['email'];
$password     = $_POST['password'];

$query = "INSERT INTO users (nama, email, password, role)
VALUES ('$nama_lengkap', '$email', '$password', 'user')";

if (mysqli_query($conn, $query)) {
    header("Location: login.php");
} else {
    echo "Register gagal. <a href='register.php'>Coba lagi</a>";
}
?>
