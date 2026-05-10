<?php

include '../config/koneksi.php';

// mengambil data dari form
$nama_lengkap = $_POST['nama_lengkap'];
$email        = $_POST['email'];
$password     = $_POST['password'];

// query insert ke database
$query = "INSERT INTO users (nama, email, password, role)
VALUES ('$nama_lengkap', '$email', '$password', 'user')";

// menjalankan query
if (mysqli_query($conn, $query)) {
    
    // kembali ke login
    header("Location: login.php");
} else {
    echo "Register gagal. <a href='register.php'>Coba lagi</a>";
}
?>
