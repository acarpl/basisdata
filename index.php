<?php
require_once 'config.php';

// Pesan notifikasi
$pesan = '';
if (isset($_GET['pesan'])) {
    switch ($_GET['pesan']) {
        case 'reminder_selesai':
            $pesan = "Reminder berhasil ditandai selesai.";
            break;
        case 'reminder_diaktifkan':
            $pesan = "Reminder berhasil diaktifkan kembali.";
            break;
    }
}

// Mengambil semua jadwal yang aktif berdasarkan tanggal hari ini dan ke depan
$today = date('Y-m-d');
$query = "SELECT j.*, e.nama as nama_ekskul 
          FROM jadwal j 
          JOIN ekstrakurikuler e ON j.ekstrakulikuler_id = e.id 
          WHERE j.tanggal >= '$today' AND j.status = 'Aktif' 
          ORDER BY j.tanggal ASC, j.waktu_mulai ASC";
$result = $conn->query($query);

// Mengambil semua reminder yang belum terlaksana
$reminder_query = "SELECT r.*, j.tanggal, e.nama as nama_ekskul
                  FROM reminder r
                  JOIN jadwal j ON r.jadwal_id = j.id
                  JOIN ekstrakurikuler e ON j.ekstrakulikuler_id = e.id
                  WHERE r.status = 'Belum' AND j.status = 'Aktif'
                  ORDER BY r.waktu_reminder ASC";
$reminder_result = $conn->query($reminder_query);

// Menghitung total reminder yang aktif
$total_reminder = $reminder_result->num_rows;

// Menghitung total jadwal yang aktif
$total_jadwal = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Reminder Ekstrakurikuler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .countdown {
            font-weight: bold;
            color: #dc3545;
        }
        .dashboard-box {
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            color: white;
        }
        .dashboard-box h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .dashboard-reminder {
            background-color: #17a2b8;
        }
        .dashboard-jadwal {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Sistem Reminder Jadwal Ekstrakurikuler</h1>
        
        <?php if ($pesan): ?>
            <div class="alert alert-success mb-4"><?php echo $pesan; ?></div>
        <?php endif; ?>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="dashboard-box dashboard-reminder">
                    <h2><?php echo $total_reminder; ?></h2>
                    <p>Reminder Aktif</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-box dashboard-jadwal">
                    <h2><?php echo $total_jadwal; ?></h2>
                    <p>Jadwal Mendatang</p>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between">
                <div>
                    <a href="tambah_ekskul.php" class="btn btn-primary me-2">Tambah Ekstrakurikuler</a>
                    <a href="kelola_ekskul.php" class="btn btn-secondary me-2">Kelola Ekstrakurikuler</a>
                </div>
                <div>
                    <a href="tambah_jadwal.php" class="btn btn-success me-2">Tambah Jadwal</a>
                    <a href="tambah_reminder.php" class="btn btn-info me-2">Tambah Reminder</a>
                    <a href="riwayat_reminder.php" class="btn btn-secondary">Riwayat Reminder</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Jadwal Ekstrakurikuler Mendatang</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ekstrakurikuler</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Lokasi</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['nama_ekskul']; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                                <td><?php echo date('H:i', strtotime($row['waktu_mulai'])) . ' - ' . date('H:i', strtotime($row['waktu_selesai'])); ?></td>
                                                <td><?php echo $row['lokasi']; ?></td>
                                                <td><?php echo $row['keterangan']; ?></td>
                                                <td>
                                                    <a href="edit_jadwal.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="hapus_jadwal.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</a>
                                                    <a href="tambah_reminder.php?jadwal_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">Tambah Reminder</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-center">Tidak ada jadwal ekstrakurikuler mendatang.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Reminder Aktif</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($reminder_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ekstrakurikuler</th>
                                            <th>Tanggal Kegiatan</th>
                                            <th>Waktu Reminder</th>
                                            <th>Pesan</th>
                                            <th>Countdown</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Reset pointer
                                        $reminder_result->data_seek(0);
                                        while ($row = $reminder_result->fetch_assoc()): 
                                        ?>
                                            <tr>
                                                <td><?php echo $row['nama_ekskul']; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                                <td><?php echo date('d-m-Y H:i', strtotime($row['waktu_reminder'])); ?></td>
                                                <td><?php echo $row['pesan']; ?></td>
                                                <td class="countdown" data-target="<?php echo strtotime($row['waktu_reminder']) * 1000; ?>">Menghitung...</td>
                                                <td>
                                                    <a href="edit_reminder.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="hapus_reminder.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus reminder ini?')">Hapus</a>
                                                    <a href="selesaikan_reminder.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Selesai</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-center">Tidak ada reminder aktif saat ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk memperbarui countdown
        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown');
            const now = new Date().getTime();

            countdownElements.forEach(element => {
                const targetTime = parseInt(element.dataset.target);
                const timeLeft = targetTime - now;

                if (timeLeft <= 0) {
                    element.innerHTML = "Waktu sudah lewat!";
                    element.style.color = "#dc3545";
                } else {
                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    let countdownText = "";
                    if (days > 0) countdownText += days + " hari ";
                    if (hours > 0 || days > 0) countdownText += hours + " jam ";
                    if (minutes > 0 || hours > 0 || days > 0) countdownText += minutes + " menit ";
                    countdownText += seconds + " detik";

                    element.innerHTML = countdownText;
                }
            });
        }

        // Update countdown setiap detik
        setInterval(updateCountdowns, 1000);
        updateCountdowns(); // Jalankan sekali saat halaman dimuat
    </script>
</body>
</html>