<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($conn, "SELECT * FROM galeri WHERE id_galeri = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: galeri.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galeri - Admin Monstera Eatery</title>
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
            <a href="galeri.php" style="color: #6d7e77; text-decoration: none; font-weight: 600; font-size: 0.9rem;">&larr; Kembali ke Kelola Galeri</a>
            <h2 style="color: #1d3b2f; margin: 10px 0 5px 0; font-size: 1.8rem; font-weight: 800;">Edit Foto Galeri</h2>
            <p style="color: #6d7e77; margin: 0; font-size: 0.9rem;">Perbarui informasi atau ganti foto galeri.</p>
        </div>

        <form action="proses_galeri.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="id_galeri" value="<?= $data['id_galeri']; ?>">
            <input type="hidden" name="gambar_lama" value="<?= $data['gambar']; ?>">

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Judul Foto</label>
                <input type="text" name="judul_foto" value="<?= htmlspecialchars($data['judul_foto'] ?? ''); ?>" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Deskripsi</label>
                <textarea name="deskripsi" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box;"><?= htmlspecialchars($data['deskripsi'] ?? ''); ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Foto Saat Ini</label>
                <?php if (!empty($data['gambar'])): ?>
                    <img src="../../uploads/galeri/<?= htmlspecialchars($data['gambar']); ?>" width="120" height="80" style="object-fit:cover; border-radius:8px; margin-bottom: 10px; display:block;">
                <?php else: ?>
                    <p style="color: #777; font-size: 0.85rem; margin-bottom: 10px;">Tidak ada foto.</p>
                <?php endif; ?>
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Ganti Foto (Opsional)</label>
                <input type="file" name="gambar" accept="image/*">
            </div>

            <div style="display: flex; gap: 12px; align-items: center; margin-top: 25px;">
                <button type="submit" name="update" class="btn" style="background: #1d3b2f; color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Perbarui Foto</button>
                <a href="galeri.php" style="color: #666; text-decoration: none; font-weight: 600; padding: 10px 16px;">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>