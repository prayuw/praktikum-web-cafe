<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT * FROM menu WHERE id = $id");
$menu = mysqli_fetch_assoc($query);

if (!$menu) {
    die("Menu tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Menu</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<div class="container" style="max-width:760px; padding:45px 0;">
    <div class="card">
        <span class="eyebrow">Manajemen Menu</span>
        <h1>Edit Menu</h1>
        <?php include "../../includes/flash.php"; ?>
        <form action="proses_menu.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="edit">
            <input type="hidden" name="id" value="<?= (int)$menu['id']; ?>">
            <label>Nama Menu</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($menu['nama']); ?>" required>
            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= htmlspecialchars($menu['kategori']); ?>" required>
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($menu['deskripsi']); ?></textarea>
            <label>Harga</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($menu['harga']); ?>" min="0" required>
            <label>Stok</label>
            <input type="number" name="stok" value="<?= htmlspecialchars($menu['stok']); ?>" min="0" required>
            <label>Ganti Gambar</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp">
            <?php if ($menu['gambar']): ?>
                <p class="muted">Gambar saat ini:</p>
                <img class="thumb" style="width:180px;height:130px;" src="../../uploads/menu/<?= htmlspecialchars($menu['gambar']); ?>" alt="">
            <?php endif; ?>
            <div class="form-actions">
                <button type="submit" class="btn">Update Menu</button>
                <a href="menu.php" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
