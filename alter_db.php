<?php
// Jalankan file ini SEKALI di browser untuk update database yang sudah ada
// Lalu HAPUS file ini
include 'config/koneksi.php';

$fixes = [
    "ALTER TABLE farmers ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE products ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE orders ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
];

echo "<h2>Update Database</h2><ul>";
foreach ($fixes as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "<li>✅ " . htmlspecialchars($sql) . "</li>";
    } else {
        echo "<li>⚠️ " . htmlspecialchars(mysqli_error($conn)) . "</li>";
    }
}
echo "</ul>";
echo "<p><strong>Selesai!</strong> Hapus file <code>alter_db.php</code> ini sekarang.</p>";
echo "<p><a href='index.php'>→ Ke Beranda</a></p>";
?>
