<?php
// KAFETANI/layar/dashboard.php (atau DashboardController.php)

session_start();

// 1. Proteksi Keamanan: Memastikan hanya Admin yang bisa mengakses halaman ini
// Jalur disesuaikan keluar 1 folder ('../') menuju folder includes
require_once '../includes/auth_check.php';
checkAdmin(); 

// 2. Memanggil Model Layar PHP yang mengelola data bisnis
require_once 'DashboardModel.php';

class DashboardController {
    private $model;

    // Konstruktor untuk inisialisasi objek Model
    public function __construct() {
        $this->model = new DashboardModel();
    }

    // Fungsi utama untuk mengatur data dan memanggil View Layar PHP
    public function index() {
        // A. Mengambil nama admin dari session login
        $admin_name = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';

        // B. Mengambil data array statistik dan menu dari Model
        $stats = $this->model->getStatistikBisnis();
        $sidebar_menu = $this->model->getSidebarMenu();

        // C. Memecah data dari Model ke variabel mandiri agar bisa langsung dibaca oleh DashboardView.php
        $total_pendapatan = $stats['total_pendapatan'];
        $total_pesanan    = $stats['total_pesanan'];
        $total_produk     = $stats['total_produk'];
        $total_petani     = $stats['total_petani'];

        // D. Menampilkan halaman (View Layar PHP)
        require_once 'DashboardView.php';
    }
}

// 3. Menjalankan fungsi index pada Controller
$app = new DashboardController();
$app->index();
?>