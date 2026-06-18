<?php
/**
 * admin/products.php — Router tipis (MVC)
 *
 * File ini hanya bertugas:
 * 1. Memeriksa sesi & hak akses admin
 * 2. Memuat class yang dibutuhkan
 * 3. Mendelegasikan request ke ProductController
 *
 * Logika bisnis ada di : app/controllers/ProductController.php
 * Query database ada di : app/models/Product.php
 * Tampilan HTML ada di  : app/views/products/index.php
 */

session_start();
require_once '../includes/auth_check.php';
checkAdmin();

require_once '../app/config/Database.php';
require_once '../app/models/Product.php';
require_once '../app/controllers/ProductController.php';

$controller = new ProductController();

// Routing berdasarkan parameter request
if (isset($_GET['hapus']) && ctype_digit($_GET['hapus'])) {
    $controller->delete();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $controller->save();
} else {
    $controller->index();
}
