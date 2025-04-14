# Aplikasi Reminder Jadwal Ekstrakurikuler

Aplikasi sederhana untuk mengelola jadwal dan reminder kegiatan ekstrakurikuler menggunakan PHP dan MySQL.

## Fitur

- Mengelola data ekstrakurikuler (tambah, edit, hapus)
- Mengelola jadwal kegiatan ekstrakurikuler (tambah, edit, hapus)
- Membuat reminder untuk jadwal kegiatan (tambah, edit, hapus, tandai selesai)
- Melihat riwayat reminder yang sudah selesai
- Countdown timer untuk setiap reminder
- Auto-check reminder (via script yang dapat dijalankan sebagai CRON job)

## Persyaratan Sistem

- PHP 7.0 atau lebih tinggi
- MySQL 5.6 atau lebih tinggi
- Web server (Apache/Nginx)

## Instalasi

1. Clone atau download repository ini ke direktori web server Anda

2. Import struktur database dari file `ekstrakulikuler_db.sql` ke MySQL Anda:
   ```
   mysql -u username -p database_name < ekstrakulikuler_db.sql
   ```
   
   Atau gunakan phpMyAdmin untuk mengimport file SQL tersebut.

3. Edit file `config.php` sesuai dengan pengaturan database Anda:
   ```php
   $host = 'localhost';     // Host database
   $username = 'root';      // Username database
   $password = '';          // Password database
   $database = 'ekstrakulikuler_db'; // Nama database
   ```

4. Akses aplikasi melalui browser:
   ```
   http://localhost/path-to-app/
   ```

## Pengaturan Cron Job untuk Auto-Check Reminder

Untuk mengaktifkan pengecekan reminder otomatis, tambahkan cron job berikut:

```
*/5 * * * * php /path/to/check_reminder.php
```

Ini akan menjalankan script pengecekan reminder setiap 5 menit.

## Struktur File

- `index.php` - Halaman utama aplikasi
- `config.php` - Konfigurasi koneksi database
- `tambah_ekskul.php` - Form untuk menambahkan ekstrakurikuler
- `edit_ekskul.php` - Form untuk mengedit ekstrakurikuler
- `kelola_ekskul.php` - Halaman untuk mengelola ekstrakurikuler
- `tambah_jadwal.php` - Form untuk menambahkan jadwal
- `edit_jadwal.php` - Form untuk mengedit jadwal
- `hapus_jadwal.php` - Proses hapus jadwal
- `tambah_reminder.php` - Form untuk menambahkan reminder
- `edit_reminder.php` - Form untuk mengedit reminder
- `hapus_reminder.php` - Proses hapus reminder
- `selesaikan_reminder.php` - Proses menandai reminder selesai
- `aktifkan_reminder.php` - Proses mengaktifkan kembali reminder
- `riwayat_reminder.php` - Halaman untuk melihat riwayat reminder
- `check_reminder.php` - Script untuk auto-check reminder
- `reminder_log.txt` - Log file untuk reminder yang diproses

## Cara Penggunaan

1. Pertama kali, tambahkan data ekstrakurikuler melalui menu "Tambah Ekstrakurikuler"
2. Tambahkan jadwal untuk ekstrakurikuler tersebut melalui menu "Tambah Jadwal"
3. Buat reminder untuk jadwal yang sudah ada melalui menu "Tambah Reminder"
4. Pantau status reminder di halaman utama
5. Reminder dapat ditandai selesai secara manual atau otomatis oleh sistem
6. Lihat riwayat reminder yang sudah selesai melalui menu "Riwayat Reminder"
