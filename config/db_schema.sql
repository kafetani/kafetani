-- DATABASE : db_kafetani
CREATE DATABASE IF NOT EXISTS db_kafetani;
USE db_kafetani;


-- =============================================
-- TABEL USERS (untuk login & register)
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user'
);


-- =============================================
-- TABEL CATEGORIES
-- =============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
);


-- =============================================
-- TABEL FARMERS
-- =============================================
CREATE TABLE IF NOT EXISTS farmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    contact VARCHAR(50),
    bio TEXT,
    avatar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =============================================
-- TABEL PRODUCT
-- =============================================
CREATE TABLE IF NOT EXISTS product (
    id_product INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    deskripsi TEXT,
    petani VARCHAR(100),
    gambar VARCHAR(255),
    category_id INT DEFAULT NULL,
    type ENUM('cafe','market') DEFAULT 'market',
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);


-- =============================================
-- TABEL ORDERS
-- =============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total INT NOT NULL DEFAULT 0,
    type ENUM('cafe','market','mixed') DEFAULT 'cafe',
    status ENUM('pending','processing','ready','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- =============================================
-- TABEL ORDER ITEMS
-- =============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price INT NOT NULL DEFAULT 0,
    subtotal INT NOT NULL DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id_product) ON DELETE CASCADE
);


-- =============================================
-- DATA ADMIN
-- =============================================
INSERT IGNORE INTO users (nama, email, password, role)
VALUES ('Administrator', 'admin@gmail.com', 'kafetani2025', 'admin');


-- =============================================
-- DATA CATEGORIES
-- =============================================
INSERT IGNORE INTO categories (name, slug) VALUES
('Kopi', 'kopi'),
('Non-Kopi', 'non-kopi'),
('Bakeri', 'bakeri'),
('Camilan', 'camilan'),
('Bahan Baku', 'bahan-baku');


-- =============================================
-- DATA FARMERS
-- =============================================
INSERT IGNORE INTO farmers (name, location, contact, bio, avatar) VALUES
('Pak Budi', 'Gayo, Aceh', '0812-3456-7890', 'Petani kopi Arabica generasi ketiga di dataran tinggi Gayo. Sudah 20 tahun mengelola kebun seluas 3 hektar dengan metode organik tanpa pestisida kimia.', 'pak_budi.webp'),
('Bu Sari', 'Temanggung, Jateng', '0856-9876-5432', 'Pelopor gula aren tradisional di Temanggung. Bu Sari mengolah nira aren secara manual menggunakan tungku kayu bakar warisan leluhur untuk menjaga cita rasa autentik.', 'bu_sari.webp'),
('Pak Yusuf', 'Pangalengan, Jabar', '0821-5544-3322', 'Petani muda yang fokus pada sayuran hidroponik dan bahan baku bakeri segar. Lulusan pertanian IPB yang memilih kembali ke desa untuk mengembangkan pertanian modern ramah lingkungan.', 'pak_yusuf.webp');


-- =============================================
-- DATA PRODUCT
-- =============================================
INSERT IGNORE INTO product (nama_produk, harga, stok, deskripsi, petani, gambar, category_id, type) VALUES
-- Menu Kafe
('Americano Arabica', 28000, 50, 'Espresso dengan air panas, rasa tegas', NULL, 'americano_arabica.webp', 1, 'cafe'),
('Kopi Susu Gula Aren', 32000, 50, 'Kopi lokal dengan gula aren asli', NULL, 'kopi_susu_gula_aren.webp', 1, 'cafe'),
('Cappuccino', 30000, 50, 'Espresso, susu steam, dan foam lembut', NULL, 'cappuccino.webp', 1, 'cafe'),
('Croissant Butter', 22000, 30, 'Renyah di luar, lembut di dalam', NULL, 'croissant_butter.webp', 3, 'cafe'),
('Chocolate Cake', 25000, 20, 'Kue coklat lembab buatan sendiri', NULL, 'chocolate_cake.webp', 4, 'cafe'),
-- Marketplace
('Biji Kopi Arabica Gayo', 85000, 100, 'Single origin, medium roast', 'Pak Budi - Gayo, Aceh', 'biji_kopi_arabica_gayo.webp', 5, 'market'),
('Gula Aren Organik', 45000, 100, 'Proses alami tanpa pemutih', 'Bu Sari - Temanggung, Jateng', 'gula_aren.webp', 5, 'market'),
('Susu Sapi Segar', 28000, 100, 'Segar dipanen pagi hari', 'Pak Yusuf - Pangalengan, Jabar', 'susu_sapi_segar.webp', 5, 'market');
