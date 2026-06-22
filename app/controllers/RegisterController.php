<?php

class RegisterController
{
    private $model;

    public function __construct()
    {
        $database    = new Database();
        $db          = $database->connect();
        $this->model = new User($db);
    }

    // Tampilkan form register
    public function showForm()
    {
        $error = $_GET['error'] ?? '';
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Proses form register
    public function register()
    {
        $nama    = trim($_POST['nama_lengkap']       ?? '');
        $email   = trim($_POST['email']              ?? '');
        $pass    = $_POST['password']               ?? '';
        $konfirm = $_POST['konfirmasi_password']     ?? '';

        if (!$nama || !$email || !$pass || !$konfirm) {
            $this->redirectError('Semua field wajib diisi.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectError('Format email tidak valid.');
        }

        if (strlen($pass) < 6) {
            $this->redirectError('Password minimal 6 karakter.');
        }

        if ($pass !== $konfirm) {
            $this->redirectError('Password dan konfirmasi password tidak cocok.');
        }

        if ($this->model->emailExists($email)) {
            $this->redirectError('Email sudah terdaftar. Gunakan email lain.');
        }

        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        if ($this->model->create($nama, $email, $hashed)) {
            header("Location: login.php");
            exit;
        }

        $this->redirectError('Terjadi kesalahan server. Silakan coba lagi.');
    }

    private function redirectError($pesan)
    {
        header("Location: register.php?error=" . urlencode($pesan));
        exit;
    }
}
