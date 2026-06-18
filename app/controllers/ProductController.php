<?php

class ProductController
{
    private $model;
    private $success = '';
    private $error   = '';

    public function __construct()
    {
        $database    = new Database();
        $db          = $database->connect();
        $this->model = new Product($db);
    }

    // Tampilkan halaman daftar produk
    public function index()
    {
        $ft           = $_GET['type'] ?? 'all';
        $products     = $this->model->getAll($ft);
        $categories   = $this->model->getCategories();
        $success      = $this->success;
        $error        = $this->error;
        $current_page = 'products';

        require_once __DIR__ . '/../views/products/index.php';
    }

    // Simpan produk (tambah atau edit)
    public function save()
    {
        $id          = (isset($_POST['id']) && ctype_digit($_POST['id'])) ? (int)$_POST['id'] : 0;
        $nama        = trim($_POST['nama_produk']  ?? '');
        $harga       = (int)($_POST['harga']       ?? 0);
        $stok        = (int)($_POST['stok']        ?? 0);
        $deskripsi   = trim($_POST['deskripsi']    ?? '');
        $cat_raw     = (int)($_POST['category_id'] ?? 0);
        $category_id = $cat_raw > 0 ? $cat_raw : null;
        $type        = in_array($_POST['type'] ?? '', ['cafe', 'market']) ? $_POST['type'] : 'cafe';
        $petani      = trim($_POST['petani']       ?? '');
        $gambar      = $_POST['gambar_lama']       ?? null;

        // Validasi input wajib
        if (!$nama || $harga <= 0) {
            $this->error = 'Nama produk dan harga wajib diisi.';
        }

        // Proses upload gambar jika ada
        if (!$this->error && !empty($_FILES['gambar']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowed)) {
                $this->error = 'Format gambar tidak didukung. Gunakan JPG, PNG, atau WEBP.';
            } else {
                $filename = uniqid('prod_', true) . '.' . $ext;
                // Path relatif dari letak file ini: app/controllers/ → naik 3 level ke root
                $dest = __DIR__ . '/../../../assets/img/products/' . $filename;

                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $dest)) {
                    // Hapus gambar lama jika ada
                    if ($gambar) {
                        $lama = __DIR__ . '/../../../assets/img/products/' . $gambar;
                        if (file_exists($lama)) @unlink($lama);
                    }
                    $gambar = $filename;
                } else {
                    $this->error = 'Gagal mengupload gambar. Cek permission folder.';
                }
            }
        }

        // Simpan ke database
        if (!$this->error) {
            if ($id > 0) {
                $ok = $this->model->update(
                    $id, $nama, $harga, $stok, $deskripsi,
                    $category_id, $type, $petani, $gambar
                );
                $this->success = $ok ? 'Produk berhasil diperbarui.' : 'Gagal menyimpan perubahan.';
                if (!$ok) $this->error = 'Gagal menyimpan perubahan.';
            } else {
                $ok = $this->model->create(
                    $nama, $harga, $stok, $deskripsi,
                    $category_id, $type, $petani, $gambar
                );
                $this->success = $ok ? 'Produk baru berhasil ditambahkan.' : 'Gagal menambahkan produk.';
                if (!$ok) $this->error = 'Gagal menambahkan produk.';
            }
        }

        $this->index();
    }

    // Hapus produk
    public function delete()
    {
        if (isset($_GET['hapus']) && ctype_digit($_GET['hapus'])) {
            $id     = (int)$_GET['hapus'];
            $gambar = $this->model->getGambarById($id);

            if ($this->model->delete($id)) {
                // Hapus file gambar dari disk
                if (!empty($gambar)) {
                    $file = __DIR__ . '/../../../assets/img/products/' . $gambar;
                    if (file_exists($file)) @unlink($file);
                }
                $this->success = 'Produk berhasil dihapus.';
            } else {
                $this->error = 'Gagal menghapus produk.';
            }
        }

        $this->index();
    }
}
