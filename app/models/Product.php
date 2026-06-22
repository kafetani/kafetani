<?php

class Product
{
    private $conn;
    private $table = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ambil semua produk, bisa difilter by type
    public function getAll($type = 'all')
    {
        $sql = "SELECT p.*, c.name AS cat_name
                FROM " . $this->table . " p
                LEFT JOIN categories c ON p.category_id = c.id";

        if ($type === 'cafe' || $type === 'market') {
            $sql .= " WHERE p.type = :type";
        }

        $sql .= " ORDER BY p.type, p.nama_produk";

        $stmt = $this->conn->prepare($sql);

        if ($type === 'cafe' || $type === 'market') {
            $stmt->bindParam(':type', $type);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ambil semua kategori buat dropdown
    public function getCategories()
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ambil nama file gambar dari id (buat apus file lama)
    public function getGambarById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT gambar FROM " . $this->table . " WHERE id_product = :id LIMIT 1"
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? $row['gambar'] : null;
    }

    // tambah produk baru
    public function create($nama, $harga, $stok, $deskripsi, $category_id, $type, $petani, $gambar)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO " . $this->table . "
             (nama_produk, harga, stok, deskripsi, category_id, type, petani, gambar)
             VALUES (:nama, :harga, :stok, :deskripsi, :category_id, :type, :petani, :gambar)"
        );

        $stmt->bindParam(':nama',        $nama);
        $stmt->bindParam(':harga',       $harga,       PDO::PARAM_INT);
        $stmt->bindParam(':stok',        $stok,        PDO::PARAM_INT);
        $stmt->bindParam(':deskripsi',   $deskripsi);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':type',        $type);
        $stmt->bindParam(':petani',      $petani);
        $stmt->bindParam(':gambar',      $gambar);

        return $stmt->execute();
    }

    // update produk yang udah ada
    public function update($id, $nama, $harga, $stok, $deskripsi, $category_id, $type, $petani, $gambar)
    {
        $stmt = $this->conn->prepare(
            "UPDATE " . $this->table . "
             SET nama_produk  = :nama,
                 harga        = :harga,
                 stok         = :stok,
                 deskripsi    = :deskripsi,
                 category_id  = :category_id,
                 type         = :type,
                 petani       = :petani,
                 gambar       = :gambar
             WHERE id_product = :id"
        );

        $stmt->bindParam(':nama',        $nama);
        $stmt->bindParam(':harga',       $harga,       PDO::PARAM_INT);
        $stmt->bindParam(':stok',        $stok,        PDO::PARAM_INT);
        $stmt->bindParam(':deskripsi',   $deskripsi);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':type',        $type);
        $stmt->bindParam(':petani',      $petani);
        $stmt->bindParam(':gambar',      $gambar);
        $stmt->bindParam(':id',          $id,          PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Hapus produk
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM " . $this->table . " WHERE id_product = :id"
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
