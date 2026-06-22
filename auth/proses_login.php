<?php
/**
 * auth/proses_login.php — Router tipis (MVC)
 *
 * Perbaikan dari versi asli:
 * - Tidak ada SQL injection (PDO + prepared statement)
 * - Password dicek dengan password_verify() bukan plain text di SQL
 */
session_start();

require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/LoginController.php';

$controller = new LoginController();
$controller->login();
