<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'kuliner_sultan';

$pdo = null;
$databaseError = null;
$accountTableError = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $e) {
    error_log('Database initialization failed: ' . $e->getMessage());
    if ($pdo) {
        $accountTableError = 'Tabel akun belum siap. Pastikan pengguna database memiliki izin membuat tabel.';
    } else {
        $databaseError = 'Koneksi database tidak tersedia. Periksa konfigurasi MySQL.';
    }
}

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
