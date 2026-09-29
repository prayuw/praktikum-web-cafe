<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT * FROM galeri WHERE id = $id");
$galeri = mysqli_fetch_assoc($query);

if (!$galeri) {
    die("Data galeri tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Galeri</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<div class="container" style="max-width:760px; padding:45px 0;">
    <div class="card">
        <span class="eyebrow">Manajemen Galeri</span>
        <h1>Edit Foto Galeri</h1>
        <?php include "../../includes/flash.php"; ?>
        <form action="proses_galeri.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="edit">
            <input type="hidden" name="id" value="<?= (int)$galeri['id']; ?>">
            <label>Judul</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($galeri['judul']); ?>" required>
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($galeri['deskripsi']); ?></textarea>
            <label>Ganti Foto</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp">
            <?php if ($galeri['gambar']): ?>
                <p class="muted">Foto saat ini:</p>
                <img class="gallery-image" style="max-width:280px;height:190px;border-radius:12px;" src="../../uploads/galeri/<?= htmlspecialchars($galeri['gambar']); ?>" alt="">
            <?php endif; ?>
            <div class="form-actions">
                <button type="submit" class="btn">Update Foto</button>
                <a href="galeri.php" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
