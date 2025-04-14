<?php
require_once 'config.php';

$message = '';

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
    
    // Validasi input
    if (empty($ekstrakulikuler_id) || empty($tanggal) || empty($waktu_mulai) || empty($waktu_selesai)) {
        $message = "Semua field wajib harus diisi!";
    } else {
        // Insert data ke database
        $stmt = $conn->prepare("INSERT INTO jadwal (ekstrakulikuler_id, tanggal, waktu_mulai, waktu_selesai, lokasi, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $ekstrakulikuler_id, $tanggal, $waktu_mulai, $waktu_selesai, $lokasi, $keterangan);
        
        if ($stmt->execute()) {
            $message = "Jadwal berhasil ditambahkan!";
            // Redirect ke halaman utama setelah 2 detik
            header("refresh:2;url=index.php");
        } else {
            $message = "Terjadi kesalahan: " . $conn->error;
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Ekstrakurikuler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Tambah Jadwal Ekstrakurikuler</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($ekskul_result->num_rows > 0): ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="ekstrakulikuler_id" class="form-label">Ekstrakurikuler</label>
                                    <select class="form-select" id="ekstrakulikuler_id" name="ekstrakulikuler_id" required>
                                        <option value="">-- Pilih Ekstrakurikuler --</option>
                                        <?php while ($row = $ekskul_result->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                        <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                                        <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi">
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-success">Simpan Jadwal</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Tidak ada ekstrakurikuler yang tersedia. Silakan 
                                <a href="tambah_ekskul.php">tambahkan ekstrakurikuler</a> terlebih dahulu.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>