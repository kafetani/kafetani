<?php

class User
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Untuk login — ambil semua kolom supaya bisa password_verify()
    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1"
        );
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Untuk register — cek apakah email sudah dipakai
    public function emailExists($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1"
        );
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Tambah user baru dengan role 'user'
    public function create($nama, $email, $password)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO " . $this->table . "
             (nama, email, password, role)
             VALUES (:nama, :email, :password, 'user')"
        );

        $stmt->bindParam(':nama',     $nama);
        $stmt->bindParam(':email',    $email);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }
}
