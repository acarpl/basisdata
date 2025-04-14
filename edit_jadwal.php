<?php
require_once 'config.php';

$message = '';

// Cek apakah ada ID yang dikirimkan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Mengambil data jadwal yang akan diedit
$stmt = $conn->prepare("SELECT * FROM jadwal WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$jadwal = $result->fetch_assoc();

// Mengambil daftar ekstrakurikuler untuk dropdown
$query = "SELECT id, nama FROM ekstrakurikuler ORDER BY nama ASC";
$ekskul_result = $conn->query($query);

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ekstrakulikuler_id = $_POST['ekstrakulikuler_id'];
    $tanggal = $_POST['tanggal'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $lokasi = $_POST['lokasi'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];
    
    // Validasi input
    if (empty($ekstrakulikuler_id) || empty($tanggal) || empty($waktu_mulai) || empty($waktu_selesai)) {
        $message = "Semua field wajib harus diisi!";
    } else {
        // Update data ke database
        $update_stmt = $conn->prepare("UPDATE jadwal SET ekstrakulikuler_id = ?, tanggal = ?, waktu_mulai = ?, waktu_selesai = ?, lokasi = ?, keterangan = ?, status = ? WHERE id = ?");
        $update_stmt->bind_param("issssssi", $ekstrakulikuler_id, $tanggal, $waktu_mulai, $waktu_selesai, $lokasi, $keterangan, $status, $id);
        
        if ($update_stmt->execute()) {
            $message = "Jadwal berhasil diperbarui!";
            // Refresh data jadwal setelah update
            $stmt->execute();
            $result = $stmt->get_result();
            $jadwal = $result->fetch_assoc();
            
            // Redirect ke halaman utama setelah 2 detik
            header("refresh:2;url=index.php");
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
    <title>Edit Jadwal Ekstrakurikuler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Edit Jadwal Ekstrakurikuler</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="ekstrakulikuler_id" class="form-label">Ekstrakurikuler</label>
                                <select class="form-select" id="ekstrakulikuler_id" name="ekstrakulikuler_id" required>
                                    <?php 
                                    // Reset pointer
                                    $ekskul_result->data_seek(0);
                                    while ($row = $ekskul_result->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $row['id']; ?>" <?php echo ($jadwal['ekstrakulikuler_id'] == $row['id']) ? 'selected' : ''; ?>>
                                            <?php echo $row['nama']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $jadwal['tanggal']; ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                    <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" value="<?php echo $jadwal['waktu_mulai']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                                    <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" value="<?php echo $jadwal['waktu_selesai']; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php echo $jadwal['lokasi']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo $jadwal['keterangan']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Aktif" <?php echo ($jadwal['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="Selesai" <?php echo ($jadwal['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                    <option value="Dibatalkan" <?php echo ($jadwal['status'] == 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-warning">Perbarui Jadwal</button>
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