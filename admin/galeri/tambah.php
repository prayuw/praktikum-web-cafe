<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

if (isset($_POST['simpan'])) {
    $judul_foto = mysqli_real_escape_string($conn, $_POST['judul_foto']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    
    if (!empty($gambar)) {
        $gambar_baru = time() . '_' . basename($gambar);
        $target_dir = "../../uploads/galeri/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (move_uploaded_file($tmp_name, $target_dir . $gambar_baru)) {
            mysqli_query($conn, "INSERT INTO galeri (judul_foto, deskripsi, gambar) VALUES ('$judul_foto', '$deskripsi', '$gambar_baru')");
        }
    }
    header("Location: galeri.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Galeri - Admin Monstera Eatery</title>
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
            <h2 style="color: #1d3b2f; margin: 10px 0 5px 0; font-size: 1.8rem; font-weight: 800;">Tambah Foto Galeri</h2>
            <p style="color: #6d7e77; margin: 0; font-size: 0.9rem;">Unggah foto suasana atau kegiatan kafe baru.</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Judul Foto</label>
                <input type="text" name="judul_foto" placeholder="Contoh: Suasana Malam di Cafe" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi foto..." style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="font-weight: 600; display: block; margin-bottom: 6px; color: #1d3b2f;">File Foto</label>
                <input type="file" name="gambar" accept="image/*" style="padding: 6px 0;" required>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" name="simpan" class="btn" style="background: #1d3b2f; color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan Foto</button>
                <a href="galeri.php" style="color: #666; text-decoration: none; font-weight: 600; padding: 10px 16px;">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>