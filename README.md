# Kafetani — Refactoring ke MVC

Folder ini berisi hasil **refactoring** tiga file utama dari proyek Kafetani ke pola arsitektur **MVC (Model-View-Controller)**.

---

## File yang Diubah / Ditambahkan

```
kafetani/
├── app/                              ← FOLDER BARU
│   ├── config/
│   │   └── Database.php             ← BARU: Koneksi PDO (ganti koneksi.php MySQLi)
│   ├── models/
│   │   ├── Product.php              ← BARU: Semua query SQL produk
│   │   ├── Farmer.php               ← BARU: Semua query SQL petani
│   │   └── User.php                 ← BARU: Query cek email & insert user
│   ├── controllers/
│   │   ├── ProductController.php    ← BARU: Logika bisnis produk (CRUD + upload)
│   │   ├── FarmerController.php     ← BARU: Logika bisnis petani (CRUD + upload)
│   │   └── RegisterController.php  ← BARU: Validasi & proses registrasi
│   └── views/
│       ├── products/
│       │   └── index.php            ← BARU: HTML/CSS tabel & modal produk
│       └── farmers/
│           ├── index.php            ← BARU: HTML tabel daftar petani
│           └── form.php             ← BARU: HTML form tambah/edit petani
│
├── admin/
│   ├── products.php                 ← DIUBAH: Sekarang hanya router tipis (10 baris)
│   └── farmers.php                  ← DIUBAH: Sekarang hanya router tipis (10 baris)
│
└── auth/
    └── proses_register.php          ← DIUBAH: Sekarang hanya router tipis (5 baris)
```

---

## Perbandingan Sebelum vs Sesudah

| Aspek               | Sebelum (Original)                  | Sesudah (MVC)                         |
|---------------------|-------------------------------------|---------------------------------------|
| **Arsitektur**      | Spaghetti (logika + HTML bercampur) | MVC (Model / View / Controller)       |
| **Database driver** | MySQLi                              | PDO + Prepared Statements             |
| **Password**        | Plain text (tidak aman!)            | Bcrypt via `password_hash()`          |
| **SQL injection**   | Rentan (string interpolation)       | Aman (`:named` placeholders)          |
| **Duplikat email**  | Tidak dicek                         | Dicek sebelum INSERT                  |
| **Validasi input**  | Tidak ada                           | Validasi lengkap di Controller        |
| **Ukuran file**     | admin/products.php: 120+ baris campur | Router: 20 baris, sisanya terpisah  |

---

## Cara Instalasi

### 1. Salin folder `app/` ke root proyek Kafetani
```
kafetani-main/
└── app/          ← salin ke sini
```

### 2. Ganti tiga file lama dengan versi baru
```bash
cp admin/products.php        /path/to/kafetani-main/admin/products.php
cp admin/farmers.php         /path/to/kafetani-main/admin/farmers.php
cp auth/proses_register.php  /path/to/kafetani-main/auth/proses_register.php
```

### 3. Tidak perlu ubah database
Skema database (`db_schema.sql`) tidak berubah sama sekali. Nama tabel dan kolom tetap sama.

### 4. Koneksi database
Edit `app/config/Database.php` jika nama database atau password berbeda:
```php
private $db_name  = "db_kafetani"; // sesuaikan
private $username = "root";        // sesuaikan
private $password = "";            // sesuaikan
```

---

## Penjelasan Pola MVC

### Model (`app/models/`)
Berisi **hanya query SQL**. Tidak ada HTML, tidak ada logika bisnis.
```php
// Contoh: Product.php
public function getAll($type = 'all') {
    $stmt = $this->conn->prepare("SELECT * FROM product ...");
    $stmt->execute();
    return $stmt->fetchAll();
}
```

### View (`app/views/`)
Berisi **hanya HTML/CSS**. Tidak ada query SQL, tidak ada logika bisnis.
Menerima data dari controller melalui variabel PHP (`$products`, `$farmers`, dll).

### Controller (`app/controllers/`)
**Jembatan** antara Model dan View.
- Menerima input dari user (`$_GET`, `$_POST`, `$_FILES`)
- Memanggil Model untuk baca/tulis data
- Menyiapkan variabel untuk View
- Meload file View yang sesuai

### Router (file `admin/*.php` dan `auth/proses_register.php`)
File lama yang URL-nya tidak boleh berubah, sekarang menjadi **router tipis**:
- Cek autentikasi
- Load class yang dibutuhkan
- Delegasikan ke Controller

---

## Referensi

Pola ini mengikuti struktur yang sama seperti contoh `crud-mahasiswa-mvc` di mata kuliah:

```
public/index.php  → router tipis
app/controllers/MahasiswaController.php  → logika
app/models/Mahasiswa.php                 → database
app/views/mahasiswa/*.php                → tampilan
```
