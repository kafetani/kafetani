<?php
/**
 * admin/farmers.php — Router tipis (MVC)
 *
 * File ini hanya bertugas:
 * 1. Memeriksa sesi & hak akses admin
 * 2. Memuat class yang dibutuhkan
 * 3. Mendelegasikan request ke FarmerController
 *
 * Logika bisnis ada di : app/controllers/FarmerController.php
 * Query database ada di : app/models/Farmer.php
 * Tampilan HTML ada di  : app/views/farmers/index.php & form.php
 */

session_start();
require_once '../includes/auth_check.php';
checkAdmin();

require_once '../app/config/Database.php';
require_once '../app/models/Farmer.php';
require_once '../app/controllers/FarmerController.php';

$controller = new FarmerController();

// Routing berdasarkan parameter ?action=
$action = $_GET['action'] ?? 'list';

if ($action === 'delete' && isset($_GET['id'])) {
    $controller->delete();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['add', 'edit'])) {
    $controller->save();
} elseif ($action === 'add') {
    $controller->add();
} elseif ($action === 'edit' && isset($_GET['id'])) {
    $controller->edit();
} else {
    $controller->index();
}
