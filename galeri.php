<?php 
require_once "config/database.php";

// Mendapatkan nama file yang sedang aktif saat ini untuk navbar
$currentPage = basename($_SERVER['PHP_SELF']); 

// Mengambil data galeri tanpa memanggil kolom ID yang error
$query = mysqli_query($conn, "SELECT * FROM galeri");?>
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
            <div class="gallery-category-group">
                <h2 class="gallery-category-title">Suasana Cafe & Area</h2>
                <div class="grid gallery-grid">
                    <?php 
                    if (mysqli_num_rows($query) > 0):
                        while ($row = mysqli_fetch_assoc($query)):
                    ?>
                            <article class="card gallery-card-item">
                                <?php if (!empty($row['gambar'])): ?>
                                    <img class="gallery-image" 
                                         src="<?= 'uploads/galeri/' . htmlspecialchars($row['gambar']); ?>" 
                                         alt="<?= htmlspecialchars($row['judul_foto']); ?>">
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
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <div class="alert alert-error" style="grid-column: 1 / -1; text-align: center;">
                            Belum ada foto galeri yang ditambahkan oleh admin.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include "includes/footer.php"; ?>
</body>

</html>