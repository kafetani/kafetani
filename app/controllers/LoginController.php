<?php

class LoginController
{
    private $model;

    public function __construct()
    {
        $database    = new Database();
        $db          = $database->connect();
        $this->model = new User($db);
    }

    // Tampilkan form login
    public function showForm()
    {
        $error = $_GET['error'] ?? '';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Proses form login
    public function login()
    {
        $email = trim($_POST['email']    ?? '');
        $pass  = $_POST['password']      ?? '';

        if (!$email || !$pass) {
            $this->redirectError('Email dan password wajib diisi.');
        }

        $user = $this->model->findByEmail($email);

        // Gunakan password_verify() untuk password yang sudah di-hash
        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['role'];

            header("Location: ../index.php");
            exit;
        }

        $this->redirectError('Email atau password salah.');
    }

    private function redirectError($pesan)
    {
        header("Location: login.php?error=" . urlencode($pesan));
        exit;
    }
}
