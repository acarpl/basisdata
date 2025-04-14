-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2025 at 09:03 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ekstrakulikuler_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ekstrakurikuler`
--

CREATE TABLE `ekstrakurikuler` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ekstrakurikuler`
--

INSERT INTO `ekstrakurikuler` (`id`, `nama`, `deskripsi`, `created_at`) VALUES
(1, 'Pramuka', 'Kegiatan kepramukaan untuk pembentukan karakter', '2025-04-14 07:49:12'),
(2, 'Basket', 'Latihan dan pertandingan bola basket', '2025-04-14 07:49:12'),
(3, 'PMR', 'Palang Merah Remaja untuk kegiatan sosial dan kesehatan', '2025-04-14 07:49:12'),
(4, 'Robotika', 'Pengembangan keterampilan di bidang robotika dan pemrograman', '2025-04-14 07:49:12'),
(5, 'Paduan Suara', 'Latihan dan pertunjukan paduan suara', '2025-04-14 07:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `ekstrakulikuler_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `keterangan` text,
  `status` enum('Aktif','Selesai','Dibatalkan') DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `ekstrakulikuler_id`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `lokasi`, `keterangan`, `status`, `created_at`) VALUES
(1, 1, '2025-04-16', '14:00:00', '16:00:00', 'Lapangan Sekolah', 'Latihan rutin mingguan', 'Aktif', '2025-04-14 07:49:13'),
(2, 2, '2025-04-17', '15:30:00', '17:30:00', 'Gedung Olahraga', 'Persiapan pertandingan antar sekolah', 'Aktif', '2025-04-14 07:49:13'),
(3, 3, '2025-04-19', '13:00:00', '15:00:00', 'Ruang UKS', 'Pelatihan pertolongan pertama', 'Aktif', '2025-04-14 07:49:13'),
(4, 4, '2025-04-18', '14:30:00', '16:30:00', 'Lab Komputer', 'Workshop pemrograman Arduino', 'Aktif', '2025-04-14 07:49:13'),
(5, 5, '2025-04-21', '15:00:00', '17:00:00', 'Aula Sekolah', 'Latihan untuk acara perpisahan', 'Aktif', '2025-04-14 07:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `waktu_reminder` datetime NOT NULL,
  `pesan` text,
  `status` enum('Belum','Sudah') DEFAULT 'Belum',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`id`, `jadwal_id`, `waktu_reminder`, `pesan`, `status`, `created_at`) VALUES
(1, 1, '2025-04-15 00:00:00', 'Jangan lupa membawa perlengkapan pramuka lengkap', 'Belum', '2025-04-14 07:49:13'),
(2, 2, '2025-04-16 00:00:00', 'Persiapkan sepatu dan jersey basket', 'Belum', '2025-04-14 07:49:13'),
(3, 3, '2025-04-18 00:00:00', 'Bawa buku catatan kesehatan', 'Belum', '2025-04-14 07:49:13'),
(4, 4, '2025-04-17 00:00:00', 'Siapkan laptop dan perangkat Arduino', 'Belum', '2025-04-14 07:49:13'),
(5, 5, '2025-04-20 00:00:00', 'Latihan lagu perpisahan', 'Belum', '2025-04-14 07:49:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ekstrakurikuler`
--
ALTER TABLE `ekstrakurikuler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ekstrakulikuler_id` (`ekstrakulikuler_id`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_id` (`jadwal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ekstrakurikuler`
--
ALTER TABLE `ekstrakurikuler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`ekstrakulikuler_id`) REFERENCES `ekstrakurikuler` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `reminder_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
