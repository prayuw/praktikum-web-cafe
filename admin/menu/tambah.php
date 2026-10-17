<?php
require_once "../../includes/auth.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Menu</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<div class="container" style="max-width:760px; padding:45px 0;">
    <div class="card">
        <span class="eyebrow">Manajemen Menu</span>
        <h1>Tambah Menu</h1>
        <?php include "../../includes/flash.php"; ?>
        <form action="proses_menu.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="tambah">
            <label>Nama Menu</label>
            <input type="text" name="nama" required>
            <label>Kategori</label>
            <input type="text" name="kategori" placeholder="Contoh: Coffee, Non-Coffee, Food" required>
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="5" required></textarea>
            <label>Harga</label>
            <input type="number" name="harga" min="0" required>
            <label>Stok</label>
            <input type="number" name="stok" min="0" required>
            <label>Gambar Menu</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp">
            <div class="form-actions">
                <button type="submit" class="btn">Simpan Menu</button>
                <a href="menu.php" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>

    
</div>
</body>
</html>
