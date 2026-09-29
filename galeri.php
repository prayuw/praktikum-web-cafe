<?php
require_once "config/database.php";

$query = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Galeri - Monstera Cafe</title>
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
        <span class="eyebrow">Suasana Monstera Cafe</span>
        <h1 class="page-title">Galeri</h1>
        <p class="muted">Lihat suasana, kegiatan, dan momen di Monstera Cafe.</p>
    </div>

    <div class="grid">
        <?php if (mysqli_num_rows($query) === 0): ?>
            <div class="card"><p>Belum ada foto galeri.</p></div>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <article class="card gallery-card">
            <?php if ($row['gambar']): ?>
                <img class="gallery-image" src="uploads/galeri/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
            <?php endif; ?>
            <div class="card-body">
                <h3><?= htmlspecialchars($row['judul']); ?></h3>
                <p class="muted"><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>
</body>
</html>
