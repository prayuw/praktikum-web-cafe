<?php
require_once "config/database.php";

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
$query = mysqli_query($conn, "SELECT * FROM menu $where_sql ORDER BY id DESC");
$categories = mysqli_query($conn, "SELECT DISTINCT kategori FROM menu ORDER BY kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu - Monstera Cafe</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar"><div class="container">
    <a class="brand" href="index.php">Monstera Cafe</a>
    <a href="index.php">Beranda</a>
    <a href="menu.php">Menu</a>
    <a href="galeri.php">Galeri</a>
    <a href="tentang.php">Tentang Kami</a>
</div></nav>

<div class="container">
    <div class="page-header">
        <span class="eyebrow">Menu Monstera Cafe</span>
        <h1 class="page-title">Temukan menu favoritmu</h1>
    </div>

    <div class="card">
        <form method="GET" class="search-bar">
            <div>
                <label for="keyword">Cari menu</label>
                <input id="keyword" type="text" name="keyword" placeholder="Contoh: latte" value="<?= htmlspecialchars($keyword); ?>">
            </div>
            <div>
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori">
                    <option value="">Semua kategori</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= htmlspecialchars($cat['kategori']); ?>" <?= $kategori === $cat['kategori'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat['kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-actions">
                <button class="btn" type="submit">Cari</button>
                <a class="btn btn-light" href="menu.php">Reset</a>
            </div>
        </form>
    </div>

    <div class="grid">
        <?php if (mysqli_num_rows($query) === 0): ?>
            <div class="card"><p>Menu tidak ditemukan.</p></div>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <article class="card product-card">
            <?php if ($row['gambar']): ?>
                <img class="product-image" src="uploads/menu/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>">
            <?php endif; ?>
            <div class="card-body">
                <p class="muted"><?= htmlspecialchars($row['kategori']); ?></p>
                <h3><?= htmlspecialchars($row['nama']); ?></h3>
                <p><?= htmlspecialchars($row['deskripsi']); ?></p>
                <p class="price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                <a href="detail.php?id=<?= $row['id']; ?>" class="btn">Lihat Detail</a>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>
</body>
</html>
