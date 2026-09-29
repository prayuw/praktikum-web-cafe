<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$keyword = trim($_GET['keyword'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');
$where = [];

if ($keyword !== '') {
    $safe = mysqli_real_escape_string($conn, $keyword);
    $where[] = "nama LIKE '%$safe%'";
}
if ($kategori !== '') {
    $safe = mysqli_real_escape_string($conn, $kategori);
    $where[] = "kategori = '$safe'";
}

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";
$per_page = 6;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

$count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM menu $where_sql"));
$total_data = (int)$count['total'];
$total_page = max(1, (int)ceil($total_data / $per_page));

$query = mysqli_query(
    $conn,
    "SELECT * FROM menu $where_sql ORDER BY id DESC LIMIT $per_page OFFSET $offset"
);
$categories = mysqli_query($conn, "SELECT DISTINCT kategori FROM menu ORDER BY kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Menu - Admin</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<nav class="navbar"><div class="container">
    <a class="brand" href="../index.php">Admin Monstera</a>
    <a href="../index.php">Dashboard</a>
    <a href="menu.php">Menu</a>
    <a href="../galeri/galeri.php">Galeri</a>
    <a href="../../index.php">Website</a>
    <a href="../logout.php">Logout</a>
</div></nav>

<div class="container">
    <div class="page-header">
        <span class="eyebrow">Manajemen Konten</span>
        <h1 class="page-title">Kelola Menu</h1>
    </div>

    <?php include "../../includes/flash.php"; ?>

    <div class="card">
        <div class="section-heading">
            <h2>Daftar Menu</h2>
            <a href="tambah.php" class="btn">+ Tambah Menu</a>
        </div>
        <form method="GET" class="search-bar">
            <div>
                <label>Cari Menu</label>
                <input type="text" name="keyword" placeholder="Masukkan nama menu" value="<?= htmlspecialchars($keyword); ?>">
            </div>
            <div>
                <label>Kategori</label>
                <select name="kategori">
                    <option value="">Semua Kategori</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= htmlspecialchars($cat['kategori']); ?>" <?= $kategori === $cat['kategori'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat['kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn">Cari</button>
                <a href="menu.php" class="btn btn-light">Reset</a>
            </div>
        </form>
    </div>

    <div class="card table-wrap">
        <table>
            <thead><tr>
                <th>No</th><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = $offset + 1; while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <?php if ($row['gambar']): ?>
                            <img class="thumb" src="../../uploads/menu/<?= htmlspecialchars($row['gambar']); ?>" alt="">
                        <?php else: ?>Tidak ada<?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['kategori']); ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= (int)$row['stok']; ?></td>
                    <td>
                        <div class="actions">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_page > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_page; $i++): ?>
            <a class="<?= $i === $page ? 'active' : ''; ?>" href="?page=<?= $i; ?>&keyword=<?= urlencode($keyword); ?>&kategori=<?= urlencode($kategori); ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
