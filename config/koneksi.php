<?php
// ── Base URL ────────────────────────────────────────────────────────────────
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path     = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    define('BASE_URL', $protocol . '://' . $host . $path . '/');
}

// ── Koneksi database (MySQLi) ───────────────────────────────────────────────
// 127.0.0.1 bukan 'localhost' — bypass DNS resolution IPv6 di Windows/XAMPP
$conn = mysqli_connect("127.0.0.1", "root", "", "db_kafetani");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

