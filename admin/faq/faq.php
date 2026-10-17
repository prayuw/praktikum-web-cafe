<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

// 1. Tangkap kata kunci pencarian dari URL (jika ada)
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

// 2. Sesuaikan kueri SQL dengan kondisi pencarian
if (!empty($keyword)) {
    $query = mysqli_query($conn, "SELECT * FROM faq WHERE pertanyaan LIKE '%$keyword%' OR jawaban LIKE '%$keyword%' ORDER BY id_faq DESC");
} else {
    $query = mysqli_query($conn, "SELECT * FROM faq ORDER BY id_faq DESC");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola FAQ - Admin Monstera Eatery</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
</head>

<body class="admin-layout-body">

    <div class="admin-app-container">
        <!-- Sidebar -->
        <?php include "../sidebar.php"; ?>

        <!-- Konten Utama -->
        <div class="admin-content-wrapper">
            <header class="admin-topbar">
                <div class="topbar-search">
                    <form action="" method="GET" style="display: flex; align-items: center; width: 100%; height: 100%;">
                        <input type="text" name="cari" placeholder="Cari FAQ..." value="<?= htmlspecialchars($_GET['cari'] ?? ''); ?>" style="border: none; background: transparent; outline: none; width: 100%; height: 100%;">
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
                        <span class="dash-subtitle">Manajemen Informasi</span>
                        <h1>Kelola FAQ (Pertanyaan Umum)</h1>
                    </div>
                    <a href="tambah.php" class="btn btn-primary" style="background: #1d3b2f; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">+ Tambah FAQ Baru</a>
                </div>

                <div class="admin-card" style="background: #f5f5f5; border: 1px solid #e5e5e5; border-radius: 12px; padding: 25px; margin-top: 20px;">
                    <table class="admin-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #ddd;">
                                <th style="padding: 12px; text-align: left; width: 5%;">No</th>
                                <th style="padding: 12px; text-align: left; width: 35%;">Pertanyaan</th>
                                <th style="padding: 12px; text-align: left; width: 45%;">Jawaban</th>
                                <th style="padding: 12px; text-align: center; width: 15%;">Aksi</th>
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
                                        <td style="padding: 12px; font-weight: 600; color: #1d3b2f;"><?= htmlspecialchars($row['pertanyaan']); ?></td>
                                        <td style="padding: 12px; color: #555;"><?= htmlspecialchars($row['jawaban']); ?></td>
                                        <td style="padding: 12px; text-align: center;">
                                            <a href="edit.php?id=<?= $row['id_faq']; ?>" class="btn-action btn-edit" style="background: #f39c12; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; margin-right: 6px;">Edit</a>
                                            <a href="hapus.php?id=<?= $row['id_faq']; ?>" class="btn-action btn-delete" style="background: #e74c3c; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem;" onclick="return confirm('Yakin ingin menghapus FAQ ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 30px; color: #777;">Data FAQ tidak ditemukan.</td>
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