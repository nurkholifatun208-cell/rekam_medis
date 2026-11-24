<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

$jml_pasien = 0; $jml_dokter = 0; $jml_obat = 0; $jml_rekam = 0;

if ($result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pasien")) {
    $jml_pasien = mysqli_fetch_assoc($result)['total'];
}
if ($result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM dokter")) {
    $jml_dokter = mysqli_fetch_assoc($result)['total'];
}
if ($result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM obat")) {
    $jml_obat = mysqli_fetch_assoc($result)['total'];
}
if ($result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM rekam_medis")) {
    $jml_rekam = mysqli_fetch_assoc($result)['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Informasi Rekam Medis</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #fcf4f7; color: #444; display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: linear-gradient(135deg, #ffb6c1, #e6e6fa); border-right: 1px solid #f3d1dc; display: flex; flex-direction: column; position: fixed; height: 100%; box-shadow: 4px 0 15px rgba(214, 51, 132, 0.05); }
        .sidebar-brand { padding: 25px 20px; text-align: center; font-size: 20px; font-weight: 700; color: #722f37; border-bottom: 1px solid rgba(255,255,255,0.4); }
        .sidebar-brand i { color: #d63384; margin-right: 8px; }
        .sidebar-menu { list-style: none; padding: 20px 15px; }
        .sidebar-menu li { margin-bottom: 10px; }
        .sidebar-menu a { display: flex; align-items: center; gap: 12px; padding: 12px 18px; color: #555; text-decoration: none; font-weight: 500; font-size: 14px; border-radius: 12px; transition: all 0.3s ease; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #ffffff; color: #d63384; box-shadow: 0 4px 12px rgba(214, 51, 132, 0.1); transform: translateX(4px); }
        .sidebar-menu a i { font-size: 16px; width: 20px; text-align: center; }
        .main-content { margin-left: 260px; flex: 1; padding: 30px; }
        header { display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 20px 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 30px; }
        header h1 { font-size: 24px; color: #4a2e35; font-weight: 600; }
        header p { font-size: 13px; color: #888; margin-top: 3px; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 42px; height: 42px; background: #ffd1dc; color: #d63384; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; }
        
        .card-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card-link { text-decoration: none; display: block; transition: transform 0.3s ease; }
        .card-link:hover { transform: translateY(-5px); }
        .card { background: #ffffff; padding: 25px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #d63384; height: 100%; }
        .card.dokter { border-left-color: #17a2b8; }
        .card.obat { border-left-color: #ffc107; }
        .card.rekam { border-left-color: #6f42c1; }
        .card-info h3 { font-size: 13px; color: #888; font-weight: 500; text-transform: uppercase; }
        .card-info p { font-size: 28px; font-weight: 700; color: #333; margin-top: 5px; }
        .card-icon { width: 50px; height: 50px; background: #fdf2f4; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #d63384; }
        .card.dokter .card-icon { background: #e0f7fa; color: #17a2b8; }
        .card.obat .card-icon { background: #fff9e6; color: #ffc107; }
        .card.rekam .card-icon { background: #f3e8ff; color: #6f42c1; }

        .recent-section { background: #ffffff; padding: 25px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .recent-section h2 { font-size: 18px; color: #4a2e35; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px 15px; text-align: left; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
        table th { background-color: #fdf2f4; color: #d63384; font-weight: 600; }
        table tr:hover { background-color: #faf6f8; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-hospital-user"></i> Rekam Medis
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="pasien.php"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a></li>
            <li><a href="dokter.php"><i class="fa-solid fa-user-doctor"></i> Data Dokter</a></li>
            <li><a href="obat.php"><i class="fa-solid fa-pills"></i> Data Obat</a></li>
            <li><a href="rekam_medis.php"><i class="fa-solid fa-notes-medical"></i> Rekam Medis</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <div>
                <h1>Dashboard Utama</h1>
                <p>Sistem Informasi Rekam Medis Klinik</p>
            </div>
            <div class="user-profile">
                <div class="user-avatar">N</div>
                <div>
                    <strong style="font-size: 14px; display: block; color: #333;">Nona Nisa</strong>
                    <span style="font-size: 12px; color: #888;">Administrator</span>
                </div>
            </div>
        </header>

        <!-- Kartu Statistik (Bisa Dipencet) -->
        <div class="card-container">
            <a href="pasien.php" class="card-link">
                <div class="card pasien">
                    <div class="card-info">
                        <h3>Total Pasien</h3>
                        <p><?= $jml_pasien; ?></p>
                    </div>
                    <div class="card-icon"><i class="fa-solid fa-users"></i></div>
                </div>
            </a>
            <a href="dokter.php" class="card-link">
                <div class="card dokter">
                    <div class="card-info">
                        <h3>Total Dokter</h3>
                        <p><?= $jml_dokter; ?></p>
                    </div>
                    <div class="card-icon"><i class="fa-solid fa-user-doctor"></i></div>
                </div>
            </a>
            <a href="obat.php" class="card-link">
                <div class="card obat">
                    <div class="card-info">
                        <h3>Total Obat</h3>
                        <p><?= $jml_obat; ?></p>
                    </div>
                    <div class="card-icon"><i class="fa-solid fa-pills"></i></div>
                </div>
            </a>
            <a href="rekam_medis.php" class="card-link">
                <div class="card rekam">
                    <div class="card-info">
                        <h3>Rekam Medis</h3>
                        <p><?= $jml_rekam; ?></p>
                    </div>
                    <div class="card-icon"><i class="fa-solid fa-file-medical"></i></div>
                </div>
            </a>
        </div>

        <div class="recent-section">
            <h2><i class="fa-solid fa-clock-rotate-left" style="color: #d63384;"></i> Riwayat Pemeriksaan Terakhir</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Diagnosa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = "SELECT rekam_medis.*, pasien.nama_pasien, dokter.nama_dokter 
                              FROM rekam_medis 
                              JOIN pasien ON rekam_medis.id_pasien = pasien.id_pasien 
                              JOIN dokter ON rekam_medis.id_dokter = dokter.id_dokter 
                              ORDER BY rekam_medis.tanggal_periksa DESC LIMIT 5";
                    $result = mysqli_query($koneksi, $query);
                    
                    if($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['tanggal_periksa']; ?></td>
                        <td><b><?= $row['nama_pasien']; ?></b></td>
                        <td><?= $row['nama_dokter']; ?></td>
                        <td><?= $row['diagnosa']; ?></td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center; color: #888; padding: 20px;'>Belum ada data rekam medis.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>