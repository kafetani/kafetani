<?php

class Farmer
{
    private $conn;
    private $table = "farmers";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Ambil semua petani
    public function getAll()
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->table . " ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Ambil satu petani berdasarkan id
    public function getById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1"
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tambah petani baru
    public function create($name, $location, $contact, $bio, $avatar)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO " . $this->table . "
             (name, location, contact, bio, avatar)
             VALUES (:name, :location, :contact, :bio, :avatar)"
        );

        $stmt->bindParam(':name',     $name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':contact',  $contact);
        $stmt->bindParam(':bio',      $bio);
        $stmt->bindParam(':avatar',   $avatar);

        return $stmt->execute();
    }

    // Update data petani
    public function update($id, $name, $location, $contact, $bio, $avatar)
    {
        $stmt = $this->conn->prepare(
            "UPDATE " . $this->table . "
             SET name     = :name,
                 location = :location,
                 contact  = :contact,
                 bio      = :bio,
                 avatar   = :avatar
             WHERE id     = :id"
        );

        $stmt->bindParam(':name',     $name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':contact',  $contact);
        $stmt->bindParam(':bio',      $bio);
        $stmt->bindParam(':avatar',   $avatar);
        $stmt->bindParam(':id',       $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Hapus petani
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM " . $this->table . " WHERE id = :id"
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
