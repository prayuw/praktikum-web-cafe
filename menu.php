<?php
require_once "config/database.php";

$currentPage = basename($_SERVER['PHP_SELF']);

// Ambil data kategori utama untuk tombol filter di atas
$query_kategori = mysqli_query($conn, "SELECT * FROM kategori_menu");

// Periksa apakah user sedang membuka kategori tertentu (misal: ?kategori=1 atau melihat semua)
$id_kategori_aktif = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;

if ($id_kategori_aktif > 0) {
    // Jika kategori dipilih (misal klik "Lihat Selengkapnya" pada Pizza), tampilkan sub-menu di dalamnya
    $query_menu = mysqli_query($conn, "SELECT * FROM menu WHERE id_kategori = $id_kategori_aktif");

    // Ambil nama kategori yang sedang aktif untuk judul
    $q_kat_aktif = mysqli_query($conn, "SELECT * FROM kategori_menu WHERE id_kategori = $id_kategori_aktif");
    $data_kat = mysqli_fetch_assoc($q_kat_aktif);
    $nama_kategori_aktif = $data_kat ? $data_kat['nama_kategori'] : "Menu";
} else {
    // Jika di halaman utama menu, tampilkan daftar kategori sebagai kartu pilihan (seperti gambar pertama)
    // Atau Anda bisa menampilkan semua menu. Berdasarkan gambar 1, ini menampilkan ringkasan kategori/menu unggulan.
    $query_menu = mysqli_query($conn, "SELECT * FROM menu");
}
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

    <?php include "includes/navbar.php"; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById("navToggle");
            const menu = document.getElementById("navMenu");

            if (toggle && menu) {
                toggle.addEventListener("click", function() {
                    const isOpen = menu.classList.toggle("is-open");
                    toggle.classList.toggle("is-active", isOpen);
                    toggle.setAttribute("aria-expanded", isOpen);
                });
            }
        });
    </script>

    <!-- BANNER OUR MENU -->
    <header class="menu-banner">
        <div class="menu-banner-overlay"></div>
        <img src="assets/reservasi.jpeg" alt="Our Menu Monstera" class="menu-banner-img">
        <div class="menu-banner-content">
            <h1>OUR MENU</h1>
        </div>
    </header>

    <!-- FILTER KATEGORI DI ATAS (PILL BUTTONS) -->
    <div class="category-filter-bar">
        <div class="container filter-scroll">
            <a href="menu.php" class="filter-btn <?= ($id_kategori_aktif == 0) ? 'active' : ''; ?>">Semua Menu</a>
            <?php while ($kat = mysqli_fetch_assoc($query_kategori)): ?>
                <a href="menu.php?kategori=<?= $kat['id_kategori']; ?>"
                    class="filter-btn <?= ($id_kategori_aktif == $kat['id_kategori']) ? 'active' : ''; ?>">
                    <?= htmlspecialchars($kat['nama_kategori']); ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- KONTEN UTAMA MENU -->
    <main class="menu-main-section">
        <div class="container">

            <?php if ($id_kategori_aktif == 0): ?>
                <!-- TAMPILAN KARTU UTAMA (SEPERTI GAMBAR 1: Main Dishes, Pizza, Burger, dll) -->
                <div class="section-heading-menu">
                    <h2>Main Dishes & Special Selections</h2>
                    <div class="menu-divider"></div>
                </div>

                <div class="grid menu-grid-cards">
                    <?php
                    // Mengambil data unik atau daftar menu perwakilan kategori
                    $query_list_menu = mysqli_query($conn, "SELECT kategori_menu.*, (SELECT gambar FROM menu WHERE menu.id_kategori = kategori_menu.id_kategori LIMIT 1) as gambar FROM kategori_menu");
                    while ($m = mysqli_fetch_assoc($query_list_menu)):
                    ?>
                        <article class="card menu-category-card">
                            <?php if (!empty($m['gambar'])): ?>
                                <img src="uploads/menu/<?= htmlspecialchars($m['gambar']); ?>" alt="<?= htmlspecialchars($m['nama_kategori']); ?>" class="menu-card-img">
                            <?php else: ?>
                                <div class="menu-card-img no-image" style="display:flex; align-items:center; justify-content:center; color:#6d7e77;">Tidak ada gambar</div>
                            <?php endif; ?>
                            <div class="menu-card-body">
                                <h3><?= htmlspecialchars($m['nama_kategori']); ?></h3>
                                <a href="menu.php?kategori=<?= $m['id_kategori']; ?>" class="link-selengkapnya">Lihat Selengkapnya &rsaquo;</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

            <?php else: ?>
                <!-- TAMPILAN DETAIL SUB-MENU / LIST HARGA (SEPERTI GAMBAR 2: Pizza Mini / Daftar Menu) -->
                <div class="menu-sub-section">
                    <h2 class="sub-category-title"><?= htmlspecialchars($nama_kategori_aktif); ?></h2>
                    <div class="menu-divider"></div>

                    <div class="menu-list-vertical">
                        <?php if (mysqli_num_rows($query_menu) > 0): ?>
                            <?php while ($item = mysqli_fetch_assoc($query_menu)): ?>
                                <div class="menu-item-row">
                                    <div class="menu-item-info">
                                        <h4><?= htmlspecialchars($item['nama_menu']); ?></h4>
                                        <p><?= htmlspecialchars($item['deskripsi'] ?? ''); ?></p>
                                    </div>
                                    <div class="menu-item-price">
                                        <span>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="color: var(--muted); padding: 20px 0;">Belum ada menu tersedia untuk kategori ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php include "includes/footer.php"; ?>
</body>

</html>