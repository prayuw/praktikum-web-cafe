<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$keyword = trim($_GET['keyword'] ?? '');
$where = '';

if ($keyword !== '') {
    $safe = mysqli_real_escape_string($conn, $keyword);
    $where = "WHERE judul LIKE '%$safe%'";
}

$query = mysqli_query($conn, "SELECT * FROM galeri $where ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Galeri - Admin</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<nav class="navbar"><div class="container">
    <a class="brand" href="../index.php">Admin Monstera</a>
    <a href="../index.php">Dashboard</a>
    <a href="../menu/menu.php">Menu</a>
    <a href="galeri.php">Galeri</a>
    <a href="../../index.php">Website</a>
    <a href="../logout.php">Logout</a>
</div></nav>

<div class="container">
    <div class="page-header">
        <span class="eyebrow">Manajemen Konten</span>
        <h1 class="page-title">Kelola Galeri</h1>
    </div>

    <?php include "../../includes/flash.php"; ?>

    <div class="card">
        <div class="section-heading">
            <h2>Daftar Galeri</h2>
            <a href="tambah.php" class="btn">+ Tambah Foto</a>
        </div>
        <form method="GET" class="search-bar">
            <div>
                <label>Cari Judul</label>
                <input type="text" name="keyword" placeholder="Masukkan judul foto" value="<?= htmlspecialchars($keyword); ?>">
            </div>
            <div></div>
            <div class="form-actions">
                <button type="submit" class="btn">Cari</button>
                <a href="galeri.php" class="btn btn-light">Reset</a>
            </div>
        </form>
    </div>

    <div class="card table-wrap">
        <table>
            <thead><tr><th>No</th><th>Gambar</th><th>Judul</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <?php if ($row['gambar']): ?>
                            <img class="thumb" src="../../uploads/galeri/<?= htmlspecialchars($row['gambar']); ?>" alt="">
                        <?php else: ?>Tidak ada<?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                    <td>
                        <div class="actions">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus foto ini?')">Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
