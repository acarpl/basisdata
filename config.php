<?php
// config.php - File konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ekstrakulikuler_db';

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>