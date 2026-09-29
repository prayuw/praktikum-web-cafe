<?php
require_once "../../includes/auth.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Galeri</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<div class="container" style="max-width:760px; padding:45px 0;">
    <div class="card">
        <span class="eyebrow">Manajemen Galeri</span>
        <h1>Tambah Foto Galeri</h1>
        <?php include "../../includes/flash.php"; ?>
        <form action="proses_galeri.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="tambah">
            <label>Judul</label>
            <input type="text" name="judul" required>
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="5" required></textarea>
            <label>Foto</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" required>
            <div class="form-actions">
                <button type="submit" class="btn">Simpan Foto</button>
                <a href="galeri.php" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
