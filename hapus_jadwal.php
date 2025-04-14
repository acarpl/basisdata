<?php
require_once 'config.php';

// Cek apakah ada ID yang dikirimkan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Hapus jadwal dari database
$stmt = $conn->prepare("DELETE FROM jadwal WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Berhasil dihapus, redirect ke halaman utama
    header('Location: index.php');
} else {
    // Gagal menghapus
    echo "Terjadi kesalahan saat menghapus jadwal: " . $conn->error;
    echo "<br><a href='index.php'>Kembali ke halaman utama</a>";
}

$stmt->close();
$conn->close();
?>