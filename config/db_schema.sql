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
-- TABEL PRODUCT (untuk marketplace sederhana)
-- =============================================
CREATE TABLE IF NOT EXISTS product (
    id_product INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    deskripsi TEXT,
    petani VARCHAR(100),
    gambar VARCHAR(255)
);


-- =============================================
-- TABEL CATEGORIES (untuk menu.php)
-- =============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
);


-- =============================================
-- TABEL FARMERS (untuk marketplace lama)
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
-- TABEL PRODUCTS (untuk menu.php & halaman lain)
-- =============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    unit VARCHAR(50) DEFAULT 'pcs',
    image VARCHAR(255),
    stock INT DEFAULT 0,
    category_id INT,
    type ENUM('cafe','market') NOT NULL,
    farmer_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE SET NULL
);


-- =============================================
-- TABEL ORDERS
-- =============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('pending','processing','ready','completed','cancelled') DEFAULT 'pending',
    total DECIMAL(12,2) NOT NULL,
    type ENUM('dine-in','pickup') DEFAULT 'pickup',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE NO ACTION
);


-- =============================================
-- TABEL ORDER ITEMS
-- =============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE NO ACTION
);


-- =============================================
-- TABEL CART
-- =============================================
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


-- =============================================
-- DATA ADMIN
-- =============================================
INSERT IGNORE INTO users (nama, email, password, role)
VALUES ('Administrator', 'admin@gmail.com', 'admin123', 'admin');


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
INSERT IGNORE INTO farmers (name, location, avatar) VALUES
('Pak Budi', 'Gayo, Aceh', 'pak_budi.webp'),
('Bu Sari', 'Temanggung, Jateng', 'bu_sari.webp'),
('Pak Yusuf', 'Pangalengan, Jabar', 'pak_yusuf.webp');


-- =============================================
-- DATA PRODUCTS (untuk menu kafe)
-- =============================================
INSERT IGNORE INTO products (name, description, price, image, stock, category_id, type) VALUES
('Americano Arabica', 'Espresso dengan air panas, rasa tegas', 28000, 'americano_arabica.webp', 50, 1, 'cafe'),
('Kopi Susu Gula Aren', 'Kopi lokal dengan gula aren asli', 32000, 'kopi_susu_gula_aren.webp', 50, 1, 'cafe'),
('Cappuccino', 'Espresso, susu steam, dan foam lembut', 30000, 'cappuccino.webp', 50, 1, 'cafe'),
('Croissant Butter', 'Renyah di luar, lembut di dalam', 22000, 'croissant_butter.webp', 30, 3, 'cafe'),
('Chocolate Cake', 'Kue coklat lembab buatan sendiri', 25000, 'chocolate_cake.webp', 20, 4, 'cafe');


-- =============================================
-- DATA PRODUCT (untuk marketplace sederhana)
-- =============================================
INSERT IGNORE INTO product (nama_produk, harga, stok, deskripsi, petani, gambar) VALUES
('Biji Kopi Arabica Gayo', 85000, 100, 'Single origin, medium roast', 'Pak Budi - Gayo, Aceh', 'biji_kopi_arabica_gayo.webp'),
('Gula Aren Organik', 45000, 100, 'Proses alami tanpa pemutih', 'Bu Sari - Temanggung, Jateng', 'gula_aren.webp'),
('Susu Sapi Segar', 28000, 100, 'Segar dipanen pagi hari', 'Pak Yusuf - Pangalengan, Jabar', 'susu_sapi_segar.webp');
