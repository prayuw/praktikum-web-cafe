<?php
require_once "config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT * FROM menu WHERE id = $id");
$menu = mysqli_fetch_assoc($query);

if (!$menu) {
    http_response_code(404);
    die("Menu tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($menu['nama']); ?> - Monstera Cafe</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>



<div class="container">
    <div class="page-header">
        <span class="eyebrow">Detail Menu</span>
        <h1 class="page-title"><?= htmlspecialchars($menu['nama']); ?></h1>
    </div>

    <div class="detail-layout">
        <div>
            <?php if ($menu['gambar']): ?>
                <img class="detail-image" src="uploads/menu/<?= htmlspecialchars($menu['gambar']); ?>" alt="<?= htmlspecialchars($menu['nama']); ?>">
            <?php endif; ?>
        </div>
        <div class="card">
            <p class="muted"><?= htmlspecialchars($menu['kategori']); ?></p>
            <h2><?= htmlspecialchars($menu['nama']); ?></h2>
            <p><?= nl2br(htmlspecialchars($menu['deskripsi'])); ?></p>
            <p class="price">Rp <?= number_format($menu['harga'], 0, ',', '.'); ?></p>
            <p>Stok tersedia: <strong><?= (int)$menu['stok']; ?></strong></p>
            <a href="menu.php" class="btn btn-light">Kembali ke Menu</a>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
</body>
</html>
