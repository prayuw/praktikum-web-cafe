<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

$total_menu = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM menu"));
$total_kategori = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kategori_menu"));
$total_galeri = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM galeri"));
$total_tim = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tentang_kami"));
$total_faq = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM faq"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Monstera Eatery</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-layout-body">

    <div class="admin-app-container">
        <!-- Sidebar Kiri -->
        <?php include "sidebar.php"; ?>

        <!-- Konten Utama Kanan -->
        <div class="admin-content-wrapper">
            
            <!-- Topbar Header -->
            <header class="admin-topbar">
                <div class="topbar-search">
                    <!-- Form pencarian interaktif yang mengarah ke menu -->
                    <form action="menu/menu.php" method="GET" style="display: flex; align-items: center; width: 100%; height: 100%;">
                        <input type="text" name="cari" placeholder="Cari Menu..." value="<?= htmlspecialchars($_GET['cari'] ?? ''); ?>" style="border: none; background: transparent; outline: none; width: 100%; height: 100%;">
                    </form>
                </div>
                
                <!-- Dropdown Profil & Logout -->
                <div class="topbar-profile-dropdown" id="profileDropdown">
                    <div class="topbar-profile" id="profileToggle">
                        <div class="profile-avatar">
                            <img src="../assets/pp.jpg" alt="Admin">
                        </div>
                        <span class="profile-name">Admin</span>
                        <span class="profile-arrow">▼</span>
                    </div>

                    <!-- Menu Dropdown yang akan muncul saat diklik -->
                    <div class="dropdown-menu-box" id="dropdownMenu">
                        <a href="../auth/logout.php" class="dropdown-item logout-link">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </header>

            <script>
    document.addEventListener("DOMContentLoaded", function () {
        const profileToggle = document.getElementById("profileToggle");
        const dropdownMenu = document.getElementById("dropdownMenu");

        profileToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        // Menutup dropdown jika mengklik di luar area profil
        window.addEventListener("click", function () {
            if (dropdownMenu.classList.contains("show")) {
                dropdownMenu.classList.remove("show");
            }
        });
    });
</script>

            <!-- Main Area -->
            <main class="admin-main-content">
                <div class="dashboard-header">
                    <span class="dash-subtitle">Dashboard</span>
                    <h1>Halo, Administrator</h1>
                    <p>Kelola konten monstera cafe dari satu tempat</p>
                </div>

                <!-- Baris Statistik Atas -->
                <div class="stats-row">
                    <div class="stat-card-box">
                        <span class="stat-label">Total Menu</span>
                        <h2 class="stat-number"><?= $total_menu; ?></h2>
                    </div>
                    <div class="stat-card-box">
                        <span class="stat-label">Kategori Menu</span>
                        <h2 class="stat-number"><?= $total_kategori; ?></h2>
                    </div>
                    <div class="stat-card-box">
                        <span class="stat-label">Total Galeri</span>
                        <h2 class="stat-number"><?= $total_galeri; ?></h2>
                    </div>
                </div>

                <!-- Baris Kartu Menu Modul -->
                <div class="modules-grid-row">
                    <!-- Kartu 1 -->
                    <div class="module-card-box">
                        <h3>Kelola Menu</h3>
                        <p>Tambah, edit, hapus, cari, dan kelola stok menu cafe</p>
                        <a href="menu/menu.php" class="btn-module-action">Kelola Menu</a>
                    </div>

                    <!-- Kartu 2 -->
                    <div class="module-card-box">
                        <h3>Kelola Galeri</h3>
                        <p>Kelola foto suasana dan kegiatan Monstera Cafe.</p>
                        <a href="galeri/galeri.php" class="btn-module-action">Kelola Galeri</a>
                    </div>

                    <!-- Kartu 3 -->
                    <div class="module-card-box">
                        <h3>Kelola Tentang Kami</h3>
                        <p>Kelola struktur Monstera Cafe.</p>
                        <a href="tentang_kami/tentang_kami.php" class="btn-module-action">Kelola Tentang Kami</a>
                    </div>

                    <!-- Kartu 4 -->
                    <div class="module-card-box">
                        <h3>Kelola FAQ</h3>
                        <p>Kelola pertanyaan Monstera Cafe.</p>
                        <a href="faq/faq.php" class="btn-module-action">Kelola FAQ</a>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>