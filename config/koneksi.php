<?php
// koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kafetani";

// membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// juga buat PDO instance untuk kode yang menggunakan PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // jika PDO gagal, atur $pdo ke null tapi biarkan mysqli tetap berfungsi
    $pdo = null;
}
?>
