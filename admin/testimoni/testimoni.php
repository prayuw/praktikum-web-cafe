
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once "../../config/database.php";

$query = mysqli_query(
    $conn,
    "SELECT * FROM testimoni ORDER BY id_testimoni DESC"
);

if (!$query) {
    die("Query testimoni gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Testimoni - Admin Monstera Eatery</title>

    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
</head>

<body class="admin-layout-body">

    <div class="admin-app-container">

        <?php include "../sidebar.php"; ?>

        <div class="admin-content-wrapper">

            <!-- Topbar -->
            <header class="admin-topbar">
                <div class="topbar-profile-dropdown" id="profileDropdown">

                    <div class="topbar-profile" id="profileToggle">
                        <div class="profile-avatar">
                            <img src="../../assets/pp.jpg" alt="Admin">
                        </div>

                        <span class="profile-name">Admin</span>
                        <span class="profile-arrow">▼</span>
                    </div>

                    <div class="dropdown-menu-box" id="dropdownMenu">
                        <a href="../../auth/logout.php"
                           class="dropdown-item logout-link">
                            Logout
                        </a>
                    </div>

                </div>
            </header>

            <!-- Konten utama -->
            <main class="admin-main-content">

                <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">

    <div>
        <span class="dash-subtitle">Manajemen Informasi</span>
        <h1>Kelola Testimoni</h1>
        <p>Kelola ulasan pelanggan yang ditampilkan pada halaman beranda.</p>
    </div>

    <a href="tambah.php"
       class="btn btn-primary"
       style="background: #1d3b2f; color: #fff; padding: 16px 22px; border-radius: 9px; text-decoration: none; font-weight: 600; white-space: nowrap;">
        + Tambah Testimoni
    </a>

</div>

                <div class="admin-card"
                     style="background:#fff;border:1px solid #e5e5e5;border-radius:12px;padding:20px;overflow-x:auto;">

                    <table class="admin-table" style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="border-bottom:2px solid #ddd;">
                                <th style="padding:12px;text-align:left;">No.</th>
                                <th style="padding:12px;text-align:left;">Foto</th>
                                <th style="padding:12px;text-align:left;">Nama</th>
                                <th style="padding:12px;text-align:left;">Profesi</th>
                                <th style="padding:12px;text-align:left;">Testimoni</th>
                                <th style="padding:12px;text-align:left;">Status</th>
                                <th style="padding:12px;text-align:center;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0): ?>
                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr style="border-bottom:1px solid #e0e0e0;">

                                        <td style="padding:12px;">
                                            <?= $no++; ?>
                                        </td>

                                        <td style="padding:12px;">
                                            <?php if (!empty($row['foto'])): ?>
                                                <img
                                                    src="../../uploads/testimoni/<?= htmlspecialchars($row['foto']); ?>"
                                                    alt="Foto <?= htmlspecialchars($row['nama']); ?>"
                                                    width="45"
                                                    height="45"
                                                    style="object-fit:cover;border-radius:50%;">
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td style="padding:12px;">
                                            <?= htmlspecialchars($row['nama']); ?>
                                        </td>

                                        <td style="padding:12px;">
                                            <?= htmlspecialchars($row['profesi']); ?>
                                        </td>

                                        <td style="padding:12px;min-width:220px;">
                                            <?= htmlspecialchars($row['isi_testimoni']); ?>
                                        </td>

                                        <td style="padding:12px;">
                                            <?= htmlspecialchars($row['status']); ?>
                                        </td>

                                        <td style="padding:12px;text-align:center;white-space:nowrap;">
                                            <a href="edit.php?id=<?= (int)$row['id_testimoni']; ?>"
                                               style="background:#f39c12;color:white;padding:6px 10px;border-radius:6px;text-decoration:none;">
                                                Edit
                                            </a>

                                            <a href="hapus.php?id=<?= (int)$row['id_testimoni']; ?>"
                                               onclick="return confirm('Yakin ingin menghapus testimoni ini?')"
                                               style="background:#e74c3c;color:white;padding:6px 10px;border-radius:6px;text-decoration:none;">
                                                Hapus
                                            </a>
                                        </td>

                                    </tr>
                                <?php endwhile; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="7"
                                        style="text-align:center;padding:30px;color:#777;">
                                        Belum ada data testimoni.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const profileToggle = document.getElementById("profileToggle");
            const dropdownMenu = document.getElementById("dropdownMenu");

            profileToggle.addEventListener("click", function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle("show");
            });

            window.addEventListener("click", function () {
                dropdownMenu.classList.remove("show");
            });
        });
    </script>

</body>
</html>
