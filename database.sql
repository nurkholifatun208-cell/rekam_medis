CREATE DATABASE IF NOT EXISTS db_rekam_medis;
USE db_rekam_medis;

-- Tabel Pasien
CREATE TABLE pasien (
    id_pasien INT AUTO_INCREMENT PRIMARY KEY,
    no_rm VARCHAR(20) NOT NULL,
    nama_pasien VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tanggal_lahir DATE NOT NULL,
    alamat TEXT NOT NULL
);

-- Tabel Dokter
CREATE TABLE dokter (
    id_dokter INT AUTO_INCREMENT PRIMARY KEY,
    nama_dokter VARCHAR(100) NOT NULL,
    spesialisasi VARCHAR(50) NOT NULL
);

-- Tabel Obat
CREATE TABLE obat (
    id_obat INT AUTO_INCREMENT PRIMARY KEY,
    nama_obat VARCHAR(100) NOT NULL,
    satuan VARCHAR(30) NOT NULL,
    stok INT NOT NULL
);

-- Tabel Rekam Medis (dengan Foreign Key)
CREATE TABLE rekam_medis (
    id_rekam INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien INT NOT NULL,
    id_dokter INT NOT NULL,
    id_obat INT NOT NULL,
    tanggal_periksa DATE NOT NULL,
    keluhan TEXT NOT NULL,
    diagnosa TEXT NOT NULL,
    tindakan TEXT NOT NULL,
    resep VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_pasien) REFERENCES pasien(id_pasien) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_dokter) REFERENCES dokter(id_dokter) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES obat(id_obat) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Data Uji Coba (Dummy Data)
INSERT INTO pasien (no_rm, nama_pasien, jenis_kelamin, tanggal_lahir, alamat) VALUES
('RM001', 'Ahmad Fauzi', 'L', '2000-05-12', 'Jl. Sudirman No. 10 Tuban'),
('Nisa Amelia', 'P', '2005-01-23', 'Jl. Panglima Sudirman Tuban'), -- Contoh data fiktif
('RM002', 'Siti Rahma', 'P', '1998-10-05', 'Jl. Kartini No. 45 Tuban'),
('RM003', 'Budi Santoso', 'L', '1995-12-20', 'Jl. Gajah Mada Tuban');

INSERT INTO dokter (nama_dokter, spesialisasi) VALUES
('Dr. Anita Kusuma, Sp.A', 'Anak'),
('Dr. Hendra Wijaya, Sp.PD', 'Penyakit Dalam');

INSERT INTO obat (nama_obat, satuan, stok) VALUES
('Paracetamol 500mg', 'Tablet', 150),
('Amoxicillin 500mg', 'Kapsul', 80),
('Antasida Doen', 'Sirup', 40);