<?php
require_once 'config.php';

// Cek apakah ada ID yang dikirimkan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: riwayat_reminder.php');
    exit;
}

$id = $_GET['id'];

// Update status reminder menjadi 'Belum'
$stmt = $conn->prepare("UPDATE reminder SET status = 'Belum' WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Berhasil diupdate, redirect ke halaman utama
    header('Location: index.php?pesan=reminder_diaktifkan');
} else {
    // Gagal mengupdate
    echo "Terjadi kesalahan saat mengaktifkan reminder: " . $conn->error;
    echo "<br><a href='riwayat_reminder.php'>Kembali ke halaman riwayat</a>";
}

$stmt->close();
$conn->close();
?>