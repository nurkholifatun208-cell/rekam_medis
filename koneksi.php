<?php
// Konfigurasi database MySQL/MariaDB
$host = "localhost";      // Alamat server database
$user = "nisa";           // Username database
$pass = "smkn3tbn";       // Password database
$db   = "db_rekam_medis";      // Nama database

// Melakukan koneksi ke database menggunakan mysqli
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>