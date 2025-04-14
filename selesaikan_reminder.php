<?php
require_once 'config.php';

// Cek apakah ada ID yang dikirimkan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Update status reminder menjadi 'Sudah'
$stmt = $conn->prepare("UPDATE reminder SET status = 'Sudah' WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Berhasil diupdate, redirect ke halaman utama
    header('Location: index.php?pesan=reminder_selesai');
} else {
    // Gagal mengupdate
    echo "Terjadi kesalahan saat mengupdate status reminder: " . $conn->error;
    echo "<br><a href='index.php'>Kembali ke halaman utama</a>";
}

$stmt->close();
$conn->close();
?>