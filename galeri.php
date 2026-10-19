<?php
require_once "config/database.php";

$currentPage = basename($_SERVER['PHP_SELF']);

// Kelompokkan foto berdasarkan kategori
$perKategori = [];

$query = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id_galeri DESC");

while ($row = mysqli_fetch_assoc($query)) {
    $kunci = isset($kategoriGaleri[$row['kategori']]) ? $row['kategori'] : 'suasana';
    $perKategori[$kunci][] = $row;
}
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

<?php include "includes/navbar.php"; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("navToggle");
    const menu = document.getElementById("navMenu");

    if (toggle && menu) {
        toggle.addEventListener("click", function () {
            const isOpen = menu.classList.toggle("is-open");
            toggle.classList.toggle("is-active", isOpen);
            toggle.setAttribute("aria-expanded", isOpen);
        });
    }
});
</script>

    <!-- BANNER HEADER GALERI -->
    <header class="gallery-banner">
        <div class="gallery-banner-overlay"></div>
        <img src="assets/reservasi.jpeg" alt="Suasana Cafe Monstera" class="gallery-banner-img">
        <div class="gallery-banner-content">
            <h1>TEMUKAN SUASANA FAVORITMU</h1>
            <p>Pilih sudut favoritmu dan nikmati suasana Cafe Monstera Eatery, dari area outdoor yang sejuk hingga ruang indoor yang nyaman.</p>
        </div>
    </header>

    <!-- KONTEN GALERI DINAMIS DARI DATABASE -->
    <main class="gallery-main-section">
        <div class="container">

            <?php if (empty($perKategori)): ?>

                <div class="alert alert-error" style="text-align: center;">
                    Belum ada foto galeri yang ditambahkan oleh admin.
                </div>

            <?php else: ?>

                <!-- Tombol lompat ke bagian tertentu -->
                <nav class="gallery-jump" aria-label="Kategori galeri">
                    <?php foreach ($kategoriGaleri as $kunci => $info): ?>
                        <?php if (!empty($perKategori[$kunci])): ?>
                            <a href="#galeri-<?= $kunci; ?>"><?= htmlspecialchars($info['judul']); ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>

                <!-- Satu bagian per kategori, urutan mengikuti config -->
                <?php foreach ($kategoriGaleri as $kunci => $info): ?>
                    <?php if (empty($perKategori[$kunci])) continue; ?>

                    <section class="gallery-category-group" id="galeri-<?= $kunci; ?>">

                        <h2 class="gallery-category-title"><?= htmlspecialchars($info['judul']); ?></h2>

                        <?php if ($info['keterangan'] !== ''): ?>
                            <p class="gallery-category-sub"><?= htmlspecialchars($info['keterangan']); ?></p>
                        <?php endif; ?>

                        <div class="grid gallery-grid">
                            <?php foreach ($perKategori[$kunci] as $row): ?>

                                <article class="card gallery-card-item">

                                    <?php if (!empty($row['gambar'])): ?>
                                        <img class="gallery-image"
                                             src="uploads/galeri/<?= htmlspecialchars($row['gambar']); ?>"
                                             alt="<?= htmlspecialchars($row['judul_foto']); ?>"
                                             loading="lazy">
                                    <?php else: ?>
                                        <div class="gallery-image no-image" style="display:flex; align-items:center; justify-content:center; color:#6d7e77;">
                                            Tidak ada gambar
                                        </div>
                                    <?php endif; ?>

                                    <div class="card-body">
                                        <h3><?= htmlspecialchars($row['judul_foto']); ?></h3>
                                        <?php if (!empty($row['deskripsi'])): ?>
                                            <p class="muted"><?= htmlspecialchars($row['deskripsi']); ?></p>
                                        <?php endif; ?>
                                    </div>

                                </article>

                            <?php endforeach; ?>
                        </div>

                    </section>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </main>

    <?php include "includes/footer.php"; ?>
</body>

</html>