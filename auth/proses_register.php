<?php
/**
 * auth/proses_register.php — Router tipis (MVC)
 *
 * File ini hanya bertugas:
 * 1. Memuat class yang dibutuhkan
 * 2. Mendelegasikan ke RegisterController
 *
 * Perbaikan dari versi asli:
 * - Tidak ada SQL injection (pakai PDO + prepared statement)
 * - Password di-hash dengan bcrypt (password_hash)
 * - Validasi input lengkap (email, panjang password, kecocokan)
 * - Cek duplikat email sebelum INSERT
 *
 * Logika bisnis ada di : app/controllers/RegisterController.php
 * Query database ada di : app/models/User.php
 */

session_start();

require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/RegisterController.php';

$controller = new RegisterController();
$controller->register();
