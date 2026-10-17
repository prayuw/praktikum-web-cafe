<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

// 1. Tangkap kata kunci pencarian dari URL (jika ada)
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

// 2. Sesuaikan kueri SQL dengan kondisi pencarian berdasarkan nama menu atau kategori
if (!empty($keyword)) {
    $query = mysqli_query($conn, "SELECT menu.*, kategori_menu.nama_kategori FROM menu LEFT JOIN kategori_menu ON menu.id_kategori = kategori_menu.id_kategori WHERE menu.nama_menu LIKE '%$keyword%' OR kategori_menu.nama_kategori LIKE '%$keyword%' ORDER BY menu.id_menu DESC");
} else {
    $query = mysqli_query($conn, "SELECT menu.*, kategori_menu.nama_kategori FROM menu LEFT JOIN kategori_menu ON menu.id_kategori = kategori_menu.id_kategori ORDER BY menu.id_menu DESC");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin Monstera Eatery</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
</head>

<body class="admin-layout-body">

    <div class="admin-app-container">
        <!-- Sidebar -->
        <?php include "../sidebar.php"; ?>

        <!-- Konten Utama -->
        <div class="admin-content-wrapper">
            <!-- Topbar Header -->
            <header class="admin-topbar">
                <div class="topbar-search">
                    <form action="" method="GET" style="display: flex; align-items: center; width: 100%; height: 100%;">
                        <input type="text" name="cari" placeholder="Cari Menu / Kategori..." value="<?= htmlspecialchars($_GET['cari'] ?? ''); ?>" style="border: none; background: transparent; outline: none; width: 100%; height: 100%;">
                    </form>
                </div>

                <!-- Dropdown Profil & Logout -->
                <div class="topbar-profile-dropdown" id="profileDropdown">
                    <div class="topbar-profile" id="profileToggle">
                        <div class="profile-avatar">
                            <img src="../../assets/pp.jpg" alt="Admin">
                        </div>
                        <span class="profile-name">Admin</span>
                        <span class="profile-arrow">▼</span>
                    </div>

                    <!-- Menu Dropdown yang akan muncul saat diklik -->
                    <div class="dropdown-menu-box" id="dropdownMenu">
                        <a href="../../auth/logout.php" class="dropdown-item logout-link">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" />
                            </svg>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </header>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const profileToggle = document.getElementById("profileToggle");
                    const dropdownMenu = document.getElementById("dropdownMenu");

                    profileToggle.addEventListener("click", function(e) {
                        e.stopPropagation();
                        dropdownMenu.classList.toggle("show");
                    });

                    // Menutup dropdown jika mengklik di luar area profil
                    window.addEventListener("click", function() {
                        if (dropdownMenu.classList.contains("show")) {
                            dropdownMenu.classList.remove("show");
                        }
                    });
                });
            </script>

            <main class="admin-main-content">
                <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span class="dash-subtitle">Manajemen Menu</span>
                        <h1>Kelola Menu Cafe</h1>
                    </div>
                    <a href="tambah.php" class="btn btn-primary" style="background: #1d3b2f; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">+ Tambah Menu Baru</a>
                </div>

                <!-- Tabel Data Menu -->
                <div class="admin-card" style="background: #f5f5f5; border: 1px solid #e5e5e5; border-radius: 12px; padding: 25px; margin-top: 20px;">
                    <table class="admin-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #ddd;">
                                <th style="padding: 12px; text-align: left;">No</th>
                                <th style="padding: 12px; text-align: left;">Gambar</th>
                                <th style="padding: 12px; text-align: left;">Nama Menu</th>
                                <th style="padding: 12px; text-align: left;">Kategori</th>
                                <th style="padding: 12px; text-align: left;">Harga</th>
                                <th style="padding: 12px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($query) > 0):
                                while ($row = mysqli_fetch_assoc($query)):
                            ?>
                                    <tr style="border-bottom: 1px solid #e0e0e0;">
                                        <td style="padding: 12px;"><?= $no++; ?></td>
                                        <td style="padding: 12px;">
                                            <?php if (!empty($row['gambar'])): ?>
                                                <img src="../../uploads/menu/<?= htmlspecialchars($row['gambar']); ?>" width="50" height="50" style="object-fit:cover; border-radius:8px;">
                                            <?php else: ?>
                                                <span style="color: #888; font-size: 0.85rem;">Tidak ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 12px; font-weight: 600; color: #1d3b2f;"><?= htmlspecialchars($row['nama_menu']); ?></td>
                                        <td style="padding: 12px;"><?= htmlspecialchars($row['nama_kategori'] ?? 'Tanpa Kategori'); ?></td>
                                        <td style="padding: 12px; font-weight: 700;">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                        <td style="padding: 12px; text-align: center;">
                                            <a href="edit.php?id=<?= $row['id_menu']; ?>" class="btn-action btn-edit" style="background: #f39c12; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; margin-right: 6px;">Edit</a>
                                            <a href="hapus.php?id=<?= $row['id_menu']; ?>" class="btn-action btn-delete" style="background: #e74c3c; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem;" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #777;">Data menu tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

</body>

</html>