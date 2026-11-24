<?php
include 'koneksi.php';

// Proses Tambah Pasien
if (isset($_POST['tambah'])) {
    $no_rm  = $_POST['no_rm'];
    $nama   = $_POST['nama_pasien'];
    $jk     = $_POST['jenis_kelamin'];
    $tgl    = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];

    mysqli_query($koneksi, "INSERT INTO pasien (no_rm, nama_pasien, jenis_kelamin, tanggal_lahir, alamat) VALUES ('$no_rm', '$nama', '$jk', '$tgl', '$alamat')");
    header("Location: pasien.php");
    exit();
}

// Proses Edit/Update Pasien
if (isset($_POST['update'])) {
    $id     = $_POST['id_pasien'];
    $no_rm  = $_POST['no_rm'];
    $nama   = $_POST['nama_pasien'];
    $jk     = $_POST['jenis_kelamin'];
    $tgl    = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];

    mysqli_query($koneksi, "UPDATE pasien SET no_rm='$no_rm', nama_pasien='$nama', jenis_kelamin='$jk', tanggal_lahir='$tgl', alamat='$alamat' WHERE id_pasien='$id'");
    header("Location: pasien.php");
    exit();
}

// Proses Hapus Pasien
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pasien WHERE id_pasien='$id'");
    header("Location: pasien.php");
    exit();
}

// Ambil data untuk mode edit jika parameter ?edit=... ada
$edit_Data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $res_edit = mysqli_query($koneksi, "SELECT * FROM pasien WHERE id_pasien='$id_edit'");
    $edit_Data = mysqli_fetch_assoc($res_edit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien - Rekam Medis</title>
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
        textarea { resize: vertical; height: 90px; }
        button, .btn-batal { background: #d63384; color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: background 0.3s; grid-column: 1 / -1; width: max-content; text-decoration: none; display: inline-block; text-align: center; }
        button:hover { background: #c22575; }
        .btn-batal { background: #6c757d; margin-left: 10px; }
        .btn-batal:hover { background: #5a6268; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { padding: 12px 15px; text-align: left; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
        table th { background-color: #fdf2f4; color: #d63384; font-weight: 600; }
        table tr:hover { background-color: #faf6f8; }
        .btn-action-group { display: flex; gap: 6px; }
        .btn-edit { color: #0d6efd; background: #e7f1ff; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: background 0.3s; }
        .btn-edit:hover { background: #0d6efd; color: white; }
        .btn-delete { color: #dc3545; background: #ffe6e6; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: background 0.3s; border: none; cursor: pointer; }
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
            <li><a href="pasien.php" class="active"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a></li>
            <li><a href="dokter.php"><i class="fa-solid fa-user-doctor"></i> Data Dokter</a></li>
            <li><a href="obat.php"><i class="fa-solid fa-pills"></i> Data Obat</a></li>
            <li><a href="rekam_medis.php"><i class="fa-solid fa-notes-medical"></i> Rekam Medis</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <div>
                <h1>Kelola Data Pasien</h1>
                <p style="font-size: 13px; color: #888;">Manajemen data pasien klinik</p>
            </div>
        </header>

        <!-- Form Tambah / Edit Pasien -->
        <div class="content-card">
            <h3>
                <i class="fa-solid <?= $edit_Data ? 'fa-user-pen' : 'fa-user-plus'; ?>" style="color: #d63384;"></i> 
                <?= $edit_Data ? 'Edit Data Pasien' : 'Tambah Pasien Baru'; ?>
            </h3>
            <form method="POST">
                <!-- Hidden input untuk id jika mode edit -->
                <?php if ($edit_Data): ?>
                    <input type="hidden" name="id_pasien" value="<?= $edit_Data['id_pasien']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>No. RM</label>
                    <input type="text" name="no_rm" placeholder="Contoh: RM001" value="<?= $edit_Data['no_rm'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" name="nama_pasien" placeholder="Masukkan nama lengkap" value="<?= $edit_Data['nama_pasien'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="L" <?= (isset($edit_Data['jenis_kelamin']) && $edit_Data['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="P" <?= (isset($edit_Data['jenis_kelamin']) && $edit_Data['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= $edit_Data['tanggal_lahir'] ?? ''; ?>" required>
                </div>
                <div class="form-group full">
                    <label>Alamat</label>
                    <textarea name="alamat" placeholder="Masukkan alamat lengkap" required><?= $edit_Data['alamat'] ?? ''; ?></textarea>
                </div>
                
                <div style="display: flex; grid-column: 1 / -1;">
                    <?php if ($edit_Data): ?>
                        <button type="submit" name="update"><i class="fa-solid fa-pen-to-square"></i> Update Data</button>
                        <a href="pasien.php" class="btn-batal">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah"><i class="fa-solid fa-floppy-disk"></i> Simpan Pasien</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Pasien -->
        <div class="content-card">
            <h3><i class="fa-solid fa-users" style="color: #d63384;"></i> Daftar Pasien</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>L/P</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $data = mysqli_query($koneksi, "SELECT * FROM pasien");
                    while ($d = mysqli_fetch_assoc($data)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['no_rm']; ?></td>
                        <td><b><?= $d['nama_pasien']; ?></b></td>
                        <td><?= $d['jenis_kelamin']; ?></td>
                        <td><?= $d['tanggal_lahir']; ?></td>
                        <td><?= $d['alamat']; ?></td>
                        <td>
                            <div class="btn-action-group">
                                <a href="pasien.php?edit=<?= $d['id_pasien']; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                                <button onclick="konfirmasiHapus(<?= $d['id_pasien']; ?>)" class="btn-delete"><i class="fa-solid fa-trash"></i> Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data pasien yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63384',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'pasien.php?hapus=' + id;
            }
        })
    }
    </script>
</body>
</html>