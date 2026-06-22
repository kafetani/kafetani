<?php

class FarmerController
{
    private $model;
    private $success = '';
    private $error   = '';

    public function __construct()
    {
        $database    = new Database();
        $db          = $database->connect();
        $this->model = new Farmer($db);
    }

    // Tampilkan daftar semua petani
    public function index()
    {
        $farmers      = $this->model->getAll();
        $success      = $this->success;
        $error        = $this->error;
        $current_page = 'farmers';

        require_once __DIR__ . '/../views/farmers/index.php';
    }

    // Form tambah petani baru
    public function add()
    {
        $action       = 'add';
        $current_page = 'farmers';
        // Data kosong untuk form tambah
        $f = ['id' => '', 'name' => '', 'location' => '', 'contact' => '', 'bio' => '', 'avatar' => ''];

        require_once __DIR__ . '/../views/farmers/form.php';
    }

    // Form edit petani (load data dari DB)
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        $f  = $this->model->getById($id);

        if (!$f) {
            $this->error = 'Data petani tidak ditemukan.';
            $this->index();
            return;
        }

        $action       = 'edit';
        $current_page = 'farmers';

        require_once __DIR__ . '/../views/farmers/form.php';
    }

    // Simpan data petani (tambah atau edit)
    public function save()
    {
        $action   = $_GET['action'] ?? 'add';
        $id       = (int)($_POST['id'] ?? 0);
        $name     = trim($_POST['name']     ?? '');
        $location = trim($_POST['location'] ?? '');
        $contact  = trim($_POST['contact']  ?? '');
        $bio      = trim($_POST['bio']      ?? '');
        $avatar   = $_POST['existing_avatar'] ?? null;

        // Proses upload avatar jika ada
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $target_dir = __DIR__ . '/../../../assets/img/farmers/';
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

            $ext          = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $new_filename = uniqid('farmer_', true) . '.' . $ext;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_dir . $new_filename)) {
                $avatar = $new_filename;
            }
        }

        if ($action === 'add') {
            $ok = $this->model->create($name, $location, $contact, $bio, $avatar);
            if ($ok) {
                $this->success = 'Data petani berhasil ditambahkan!';
            } else {
                $this->error = 'Gagal menambahkan data petani.';
            }
        } else {
            $ok = $this->model->update($id, $name, $location, $contact, $bio, $avatar);
            if ($ok) {
                $this->success = 'Data petani berhasil diupdate!';
            } else {
                $this->error = 'Gagal mengupdate data petani.';
            }
        }

        $this->index();
    }

    // Hapus petani
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($this->model->delete($id)) {
            $this->success = 'Data petani berhasil dihapus!';
        } else {
            $this->error = 'Gagal menghapus data petani.';
        }

        $this->index();
    }
}
