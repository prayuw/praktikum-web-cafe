<?php
require_once "config/database.php";

$query = mysqli_query(
    $conn,
    "SELECT * FROM menu ORDER BY id DESC LIMIT 6"
);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monstera Cafe</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <a class="brand" href="index.php">Monstera Cafe</a>
            <a href="index.php">Beranda</a>
            <a href="menu.php">Menu</a>
            <a href="galeri.php">Galeri</a>
            <a href="tentang.php">Tentang Kami</a>
        </div>
    </nav>

    <section class="hero">
        <div class="container hero-content">
            <span class="eyebrow">Cafe • Cozy • Green</span>
            <h1>Tempat nyaman untuk menikmati kopi dan cerita.</h1>
            <p>Monstera Cafe menghadirkan suasana hangat, menu pilihan, dan ruang yang nyaman untuk berkumpul, belajar, maupun bersantai.</p>
            <a href="menu.php" class="btn">Lihat Menu</a>
            <a href="galeri.php" class="btn btn-secondary">Lihat Galeri</a>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Pilihan kami</span>
                    <h2>Menu Favorit</h2>
                </div>
                <a href="menu.php" class="btn btn-light">Semua Menu</a>
            </div>

            <div class="grid">
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <article class="card product-card">
                        <?php if (!empty($row['gambar'])): ?>
                            <img
                                class="product-image"
                                src="<?= 'uploads/menu/' . htmlspecialchars($row['gambar']); ?>"
                                alt="<?= htmlspecialchars($row['nama']); ?>">
                        <?php else: ?>
                            <div class="product-image no-image">
                                Tidak ada gambar
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <p class="muted"><?= htmlspecialchars($row['kategori']); ?></p>
                            <h3><?= htmlspecialchars($row['nama']); ?></h3>
                            <p><?= htmlspecialchars($row['deskripsi']); ?></p>
                            <div class="actions">
                                <strong class="price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></strong>
                                <a href="detail.php?id=<?= $row['id']; ?>" class="btn">Detail</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>