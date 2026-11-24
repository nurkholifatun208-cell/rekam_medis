# Sistem Informasi Rekam Medis

Aplikasi web sederhana untuk mengelola data pasien, dokter, obat, dan pencatatan rekam medis berbasis PHP dan MySQL.

## Fitur Utama
- **Data Pasien:** Mengelola informasi dan riwayat pasien (`pasien.php`).
- **Data Dokter:** Mengelola data dokter yang bertugas (`dokter.php`).
- **Data Obat:** Mengelola daftar dan stok obat (`obat.php`).
- **Rekam Medis:** Pencatatan hasil pemeriksaan dan resep pasien (`rekam_medis.php`).

## Cara Menjalankan
1. Buat database baru di phpMyAdmin dengan nama `db_rekam_medis`.
2. Import file `database.sql` (atau `db_rekam_medis.sql`) ke dalam database tersebut.
3. Sesuaikan pengaturan koneksi database pada file `koneksi.php`.
4. Jalankan aplikasi melalui browser web server Anda (`index.php`).
