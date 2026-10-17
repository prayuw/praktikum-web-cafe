<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($conn, "SELECT * FROM tentang_kami WHERE id_karyawan = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: tentang_kami.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tentang Kami - Admin Monstera Eatery</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        body.form-page-body {
            background: #f4f6f5;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .form-card-wrapper {
            background: #ffffff;
            border: 1px solid #e0e8e4;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="form-page-body">

    <div class="form-card-wrapper">
        <div style="margin-bottom: 25px;">
            <a href="tentang_kami.php" style="color: #6d7e77; text-decoration: none; font-weight: 600; font-size: 0.9rem;">&larr; Kembali ke Kelola Tentang Kami</a>
            <h2 style="color: #1d3b2f; margin: 10px 0 5px 0; font-size: 1.8rem; font-weight: 800;">Edit Karyawan</h2>
            <p style="color: #6d7e77; margin: 0; font-size: 0.9rem;">Perbarui informasi atau ganti foto karyawan.</p>
        </div>

        <form action="proses_tentang.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="id_karyawan" value="<?= $data['id_karyawan']; ?>">
            <input type="hidden" name="gambar_lama" value="<?= $data['gambar']; ?>">

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama'] ?? ''); ?>" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Jabatan</label>
                <input type="text" name="jabatan" value="<?= htmlspecialchars($data['jabatan'] ?? ''); ?>" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Foto Saat Ini</label>
                <?php if (!empty($data['gambar'])): ?>
                    <img src="../../uploads/tentang_kami/<?= htmlspecialchars($data['gambar']); ?>" width="80" height="80" style="object-fit:cover; border-radius:50%; margin-bottom: 10px; display:block;">
                <?php else: ?>
                    <p style="color: #777; font-size: 0.85rem; margin-bottom: 10px;">Tidak ada foto.</p>
                <?php endif; ?>
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Ganti Foto (Opsional)</label>
                <input type="file" name="gambar" accept="image/*">
            </div>

            <div style="display: flex; gap: 12px; align-items: center; margin-top: 25px;">
                <button type="submit" name="update" class="btn" style="background: #1d3b2f; color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Perbarui Data</button>
                <a href="tentang_kami.php" style="color: #666; text-decoration: none; font-weight: 600; padding: 10px 16px;">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>