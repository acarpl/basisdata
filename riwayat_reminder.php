<?php
require_once 'config.php';

// Mengambil semua reminder yang sudah selesai
$query = "SELECT r.*, j.tanggal, j.waktu_mulai, e.nama as nama_ekskul
          FROM reminder r
          JOIN jadwal j ON r.jadwal_id = j.id
          JOIN ekstrakurikuler e ON j.ekstrakulikuler_id = e.id
          WHERE r.status = 'Sudah'
          ORDER BY r.waktu_reminder DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reminder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Riwayat Reminder</h1>
        
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                <a href="tambah_reminder.php" class="btn btn-primary">Tambah Reminder Baru</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0">Reminder yang Sudah Selesai</h4>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Tanggal Kegiatan</th>
                                    <th>Waktu Reminder</th>
                                    <th>Pesan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while ($row = $result->fetch_assoc()): 
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $row['nama_ekskul']; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($row['tanggal'])) . ' ' . date('H:i', strtotime($row['waktu_mulai'])); ?></td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($row['waktu_reminder'])); ?></td>
                                        <td><?php echo $row['pesan']; ?></td>
                                        <td>
                                            <a href="hapus_reminder.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat reminder ini?')">Hapus</a>
                                            <a href="aktifkan_reminder.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" onclick="return confirm('Aktifkan kembali reminder ini?')">Aktifkan Kembali</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Belum ada riwayat reminder yang sudah selesai.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>