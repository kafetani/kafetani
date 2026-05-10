<?php
include 'config/koneksi.php';

// mengambil data product dari database
$query = mysqli_query($conn, "SELECT * FROM product");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marketplace Petani - Kafetani</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/marketplace.css">
</head>
<body>

    <!-- Navbar -->
    <header>
        <img src="assets/img/logo_v3.svg" alt="Kafetani Logo" class="logo">
        <nav>
            <a href="index.php">BERANDA</a> 
            <a href="menu.php">MENU KAFE</a> 
            <a href="marketplace.php">MARKETPLACE</a> 
            <a href="auth/login.php">LOGIN</a>
        </nav>
        <button class="cart-btn">🛒 Keranjang (0)</button>
    </header>

    <!-- Judul / Hero -->
    <section class="hero">
        <p>Kafetani · Marketplace</p>
        <h1>Marketplace Petani</h1>
        <p>Beli langsung dari petani lokal — biji kopi, gula aren, dan produk segar pilihan</p>
    </section>

    <!-- Sidebar petani -->
    <aside class="sidebar">
        <h3>PETANI MITRA</h3>
        <!-- Semua Petani -->
        <li class="active">
            <img src="assets/img/farmers/semua_petani.webp" class="petani-img">
            Semua Petani - Semua Wilayah
        </li>
        <ul>
            <li>
                <img src="assets/img/farmers/pak_budi.webp" class="petani-img">
                Pak Budi - Gayo, Aceh
            </li><br>
            <li>
                <img src="assets/img/farmers/bu_sari.webp" class="petani-img">
                Bu Sari - Temanggung, Jateng
            </li><br>
            <li>
                <img src="assets/img/farmers/pak_yusuf.webp" class="petani-img">
                Pak Yusuf - Pangalengan, Jabar
            </li>
        </ul>
    </aside>

    <!-- Highlight -->
    <section class="highlight">
        <h2>Langsung dari Kebun</h2>
        <p>Setiap produk dikirim segar, tanpa perantara</p>
    </section>

    <!-- Produk -->
    <section class="produk">

        <!-- Menampilkan semua data produk dari database -->
        <?php while($data = mysqli_fetch_assoc($query)) { ?>

            <!-- Card produk -->
            <div class="card">
                
            <!-- Gambar produk -->
                <img src="assets/img/products/<?php echo $data['gambar']; ?>">

                <div class="content">

                    <!-- Nama petani -->
                    <p>
                        <strong>
                            <?php echo $data['petani']; ?>
                        </strong>
                    </p>

                    <!-- Nama produk -->
                    <h3> <?php echo $data['nama_produk']; ?> </h3>

                    <!-- Deskripsi produk -->
                    <p> <?php echo $data['deskripsi']; ?> </p>

                    <!-- Harga dan tombol keranjang -->
                    <div class="row">
                        <!-- Harga produk -->
                        <p class="harga"> Rp <?php echo number_format($data['harga']); ?> </p>

                        <!-- Tombol tambah ke keranjang -->
                        <button class="add-to-cart">+</button>
                    </div>
                </div>
            </div>
        <?php } ?>

    </section>
    <script src="assets/js/script.js"></script>
</body>
</html>
