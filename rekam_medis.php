<?php
include 'koneksi.php';

// Tambah Rekam Medis
if (isset($_POST['tambah'])) {
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $id_obat   = $_POST['id_obat'];
    $tgl       = $_POST['tanggal_periksa'];
    $keluhan   = $_POST['keluhan'];
    $diagnosa  = $_POST['diagnosa'];
    $tindakan  = $_POST['tindakan'];
    $resep     = $_POST['resep'];

    mysqli_query($koneksi, "INSERT INTO rekam_medis (id_pasien, id_dokter, id_obat, tanggal_periksa, keluhan, diagnosa, tindakan, resep) 
                            VALUES ('$id_pasien', '$id_dokter', '$id_obat', '$tgl', '$keluhan', '$diagnosa', '$tindakan', '$resep')");
    header("Location: rekam_medis.php");
    exit();
}

// Update Rekam Medis
if (isset($_POST['update'])) {
    $id_rekam  = $_POST['id_rekam'];
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $id_obat   = $_POST['id_obat'];
    $tgl       = $_POST['tanggal_periksa'];
    $keluhan   = $_POST['keluhan'];
    $diagnosa  = $_POST['diagnosa'];
    $tindakan  = $_POST['tindakan'];
    $resep     = $_POST['resep'];

    mysqli_query($koneksi, "UPDATE rekam_medis SET 
                            id_pasien='$id_pasien', 
                            id_dokter='$id_dokter', 
                            id_obat='$id_obat', 
                            tanggal_periksa='$tgl', 
                            keluhan='$keluhan', 
                            diagnosa='$diagnosa', 
                            tindakan='$tindakan', 
                            resep='$resep' 
                            WHERE id_rekam='$id_rekam'");
    header("Location: rekam_medis.php");
    exit();
}

// Hapus Rekam Medis
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM rekam_medis WHERE id_rekam='$id'");
    header("Location: rekam_medis.php");
    exit();
}

// Ambil data untuk Edit
$edit_Data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $res_edit = mysqli_query($koneksi, "SELECT * FROM rekam_medis WHERE id_rekam='$id_edit'");
    $edit_Data = mysqli_fetch_assoc($res_edit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis & Riwayat - Rekam Medis</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .content-card { background: #ffffff; padding: 25px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .content-card h3 { font-size: 18px; color: #4a2e35; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        form { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 600; color: #666; }
        input, select, textarea { width: 100%; padding: 12px 15px; border: 1px solid #e0d0d8; border-radius: 10px; font-size: 14px; background: #fffcfd; outline: none; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { border-color: #d63384; }
        textarea { resize: vertical; height: 80px; }
        button, .btn-batal { background: #d63384; color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: background 0.3s; grid-column: 1 / -1; width: max-content; text-decoration: none; display: inline-block; text-align: center; }
        button:hover { background: #c22575; }
        .btn-batal { background: #6c757d; margin-left: 10px; }
        .btn-batal:hover { background: #5a6268; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { padding: 12px 15px; text-align: left; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
        table th { background-color: #fdf2f4; color: #d63384; font-weight: 600; }
        table tr:hover { background-color: #faf6f8; }
        .btn-action-group { display: flex; gap: 6px; }
        .btn-edit { color: #0d6efd; background: #e7f1ff; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: background 0.3s; }
        .btn-edit:hover { background: #0d6efd; color: white; }
        .btn-delete { color: #dc3545; background: #ffe6e6; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; transition: background 0.3s; border: none; cursor: pointer; }
        .btn-delete:hover { background: #dc3545; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-hospital-user"></i> Rekam Medis
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="pasien.php"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a></li>
            <li><a href="dokter.php"><i class="fa-solid fa-user-doctor"></i> Data Dokter</a></li>
            <li><a href="obat.php"><i class="fa-solid fa-pills"></i> Data Obat</a></li>
            <li><a href="rekam_medis.php" class="active"><i class="fa-solid fa-notes-medical"></i> Rekam Medis</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <div>
                <h1>Pencatatan Rekam Medis</h1>
                <p style="font-size: 13px; color: #888;">Manajemen pemeriksaan dan riwayat medis pasien</p>
            </div>
        </header>

        <div class="content-card">
            <h3>
                <i class="fa-solid <?= $edit_Data ? 'fa-pen-to-square' : 'fa-file-medical'; ?>" style="color: #d63384;"></i> 
                <?= $edit_Data ? 'Edit Data Rekam Medis' : 'Form Catat Rekam Medis Baru'; ?>
            </h3>
            <form method="POST">
                <?php if ($edit_Data): ?>
                    <input type="hidden" name="id_rekam" value="<?= $edit_Data['id_rekam']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Pilih Pasien</label>
                    <select name="id_pasien" required>
                        <option value="">-- Pilih Pasien --</option>
                        <?php
                        $p = mysqli_query($koneksi, "SELECT * FROM pasien");
                        while ($row = mysqli_fetch_assoc($p)) {
                            $selected = ($edit_Data && $edit_Data['id_pasien'] == $row['id_pasien']) ? 'selected' : '';
                            echo "<option value='{$row['id_pasien']}' $selected>{$row['no_rm']} - {$row['nama_pasien']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Dokter</label>
                    <select name="id_dokter" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php
                        $d = mysqli_query($koneksi, "SELECT * FROM dokter");
                        while ($row = mysqli_fetch_assoc($d)) {
                            $selected = ($edit_Data && $edit_Data['id_dokter'] == $row['id_dokter']) ? 'selected' : '';
                            echo "<option value='{$row['id_dokter']}' $selected>{$row['nama_dokter']} ({$row['spesialisasi']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Obat</label>
                    <select name="id_obat" required>
                        <option value="">-- Pilih Obat --</option>
                        <?php
                        $o = mysqli_query($koneksi, "SELECT * FROM obat");
                        while ($row = mysqli_fetch_assoc($o)) {
                            $selected = ($edit_Data && $edit_Data['id_obat'] == $row['id_obat']) ? 'selected' : '';
                            echo "<option value='{$row['id_obat']}' $selected>{$row['nama_obat']} (Stok: {$row['stok']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Periksa</label>
                    <input type="date" name="tanggal_periksa" value="<?= $edit_Data['tanggal_periksa'] ?? ''; ?>" required>
                </div>

                <div class="form-group full">
                    <label>Keluhan Pasien</label>
                    <textarea name="keluhan" placeholder="Tuliskan keluhan utama pasien..." required><?= $edit_Data['keluhan'] ?? ''; ?></textarea>
                </div>

                <div class="form-group full">
                    <label>Diagnosa Dokter</label>
                    <textarea name="diagnosa" placeholder="Tuliskan hasil diagnosa..." required><?= $edit_Data['diagnosa'] ?? ''; ?></textarea>
                </div>

                <div class="form-group full">
                    <label>Tindakan Medis</label>
                    <textarea name="tindakan" placeholder="Tuliskan tindakan yang diberikan..." required><?= $edit_Data['tindakan'] ?? ''; ?></textarea>
                </div>

                <div class="form-group full">
                    <label>Resep Obat & Aturan Pakai</label>
                    <input type="text" name="resep" placeholder="Contoh: 3x1 tablet sesudah makan" value="<?= $edit_Data['resep'] ?? ''; ?>" required>
                </div>

                <div style="display: flex; grid-column: 1 / -1;">
                    <?php if ($edit_Data): ?>
                        <button type="submit" name="update"><i class="fa-solid fa-pen-to-square"></i> Update Rekam Medis</button>
                        <a href="rekam_medis.php" class="btn-batal">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah"><i class="fa-solid fa-floppy-disk"></i> Simpan Rekam Medis</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="content-card">
            <h3><i class="fa-solid fa-table-list" style="color: #d63384;"></i> Riwayat Pemeriksaan Lengkap (SQL JOIN)</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pasien</th>
                            <th>Dokter Pemeriksa</th>
                            <th>Keluhan & Diagnosa</th>
                            <th>Tindakan</th>
                            <th>Obat & Resep</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = "SELECT rekam_medis.*, pasien.no_rm, pasien.nama_pasien, dokter.nama_dokter, obat.nama_obat 
                                  FROM rekam_medis 
                                  JOIN pasien ON rekam_medis.id_pasien = pasien.id_pasien 
                                  JOIN dokter ON rekam_medis.id_dokter = dokter.id_dokter 
                                  JOIN obat ON rekam_medis.id_obat = obat.id_obat";
                        $result = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['tanggal_periksa']; ?></td>
                            <td><b><?= $row['nama_pasien']; ?></b><br><small style="color:#888;">RM: <?= $row['no_rm']; ?></small></td>
                            <td><?= $row['nama_dokter']; ?></td>
                            <td><b>Keluhan:</b> <?= $row['keluhan']; ?><br><b>Diagnosa:</b> <?= $row['diagnosa']; ?></td>
                            <td><?= $row['tindakan']; ?></td>
                            <td><b><?= $row['nama_obat']; ?></b><br><small style="color:#888;"><i><?= $row['resep']; ?></i></small></td>
                            <td>
                                <div class="btn-action-group">
                                    <a href="rekam_medis.php?edit=<?= $row['id_rekam']; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                                    <button onclick="konfirmasiHapus(<?= $row['id_rekam']; ?>)" class="btn-delete"><i class="fa-solid fa-trash"></i> Hapus</button>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data rekam medis ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63384',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'rekam_medis.php?hapus=' + id;
            }
        })
    }
    </script>
</body>
</html>