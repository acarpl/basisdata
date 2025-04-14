<?php
require_once 'config.php';

$message = '';

// Cek apakah ada ID yang dikirimkan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: kelola_ekskul.php');
    exit;
}

$id = $_GET['id'];

// Mengambil data ekstrakurikuler yang akan diedit
$stmt = $conn->prepare("SELECT * FROM ekstrakurikuler WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: kelola_ekskul.php');
    exit;
}

$ekskul = $result->fetch_assoc();

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    
    // Validasi input
    if (empty($nama)) {
        $message = "Nama ekstrakurikuler harus diisi!";
    } else {
        // Update data ke database
        $update_stmt = $conn->prepare("UPDATE ekstrakurikuler SET nama = ?, deskripsi = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $nama, $deskripsi, $id);
        
        if ($update_stmt->execute()) {
            $message = "Ekstrakurikuler berhasil diperbarui!";
            // Refresh data ekstrakurikuler setelah update
            $stmt->execute();
            $result = $stmt->get_result();
            $ekskul = $result->fetch_assoc();
            
            // Redirect ke halaman kelola setelah 2 detik
            header("refresh:2;url=kelola_ekskul.php");
        } else {
            $message = "Terjadi kesalahan: " . $conn->error;
        }
        
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ekstrakurikuler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Edit Ekstrakurikuler</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Ekstrakurikuler</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $ekskul['nama']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo $ekskul['deskripsi']; ?></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="kelola_ekskul.php" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-warning">Perbarui Ekstrakurikuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>