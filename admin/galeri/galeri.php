<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once "../../config/database.php";

// token keamanan untuk form hapus
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

// pesan sukses / error dari halaman proses
$flash = $_SESSION['flash_galeri'] ?? null;
unset($_SESSION['flash_galeri']);

// pencarian dan filter kategori
$keyword = trim($_GET['cari'] ?? '');
$filter  = $_GET['kategori'] ?? '';

if (!isset($kategoriGaleri[$filter])) {
    $filter = '';
}

$where = [];
$tipe  = '';
$param = [];

if ($keyword !== '') {
    $where[] = "(judul_foto LIKE ? OR deskripsi LIKE ?)";
    $like    = "%" . $keyword . "%";
    $tipe   .= "ss";
    $param[] = $like;
    $param[] = $like;
}

if ($filter !== '') {
    $where[] = "kategori = ?";
    $tipe   .= "s";
    $param[] = $filter;
}

$sql = "SELECT * FROM galeri"
     . ($where ? " WHERE " . implode(" AND ", $where) : "")
     . " ORDER BY id_galeri DESC";

$stmt = mysqli_prepare($conn, $sql);

if ($param) {
    mysqli_stmt_bind_param($stmt, $tipe, ...$param);
}

mysqli_stmt_execute($stmt);
$daftar = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri - Admin Monstera Eatery</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body class="admin-layout-body">

    <div class="admin-app-container">
        <!-- Sidebar -->
        <?php include "../sidebar.php"; ?>

        <!-- Konten Utama -->
        <div class="admin-content-wrapper">

            <!-- Topbar -->
            <header class="admin-topbar">
                <div class="topbar-search">
                    <form action="galeri.php" method="GET" style="display: flex; align-items: center; width: 100%; height: 100%;">
                        <?php if ($filter !== ''): ?>
                            <input type="hidden" name="kategori" value="<?= htmlspecialchars($filter); ?>">
                        <?php endif; ?>
                        <input type="text" name="cari" placeholder="Cari Galeri..." value="<?= htmlspecialchars($keyword); ?>" style="border: none; background: transparent; outline: none; width: 100%; height: 100%;">
                    </form>
                </div>

                <div class="topbar-profile-dropdown" id="profileDropdown">
                    <div class="topbar-profile" id="profileToggle">
                        <div class="profile-avatar">
                            <img src="../../assets/pp.jpg" alt="Admin">
                        </div>
                        <span class="profile-name">Admin</span>
                        <span class="profile-arrow">▼</span>
                    </div>

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

            <main class="admin-main-content">

                <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span class="dash-subtitle">Manajemen Galeri</span>
                        <h1>Kelola Galeri Foto</h1>
                    </div>
                    <a href="tambah.php" class="btn btn-primary" style="background: #1d3b2f; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">+ Tambah Foto Baru</a>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?= htmlspecialchars($flash['tipe']); ?>">
                        <?= htmlspecialchars($flash['pesan']); ?>
                    </div>
                <?php endif; ?>

                <!-- Filter kategori -->
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin: 20px 0 0;">
                    <?php
                    $tombol = ['' => 'Semua'];
                    foreach ($kategoriGaleri as $kunci => $info) {
                        $tombol[$kunci] = $info['judul'];
                    }
                    foreach ($tombol as $kunci => $label):
                        $aktif = ($kunci === $filter);
                        $url   = "galeri.php?" . http_build_query(array_filter([
                            'kategori' => $kunci,
                            'cari'     => $keyword,
                        ]));
                    ?>
                        <a href="<?= $url; ?>"
                           style="padding: 7px 16px; border-radius: 999px; font-size: .85rem; font-weight: 600; text-decoration: none;
                                  background: <?= $aktif ? '#1d3b2f' : '#e3efdf'; ?>;
                                  color: <?= $aktif ? '#fff' : '#1d3b2f'; ?>;">
                            <?= htmlspecialchars($label); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="admin-card" style="background: #f5f5f5; border: 1px solid #e5e5e5; border-radius: 12px; padding: 25px; margin-top: 20px; overflow-x: auto;">
                    <table class="admin-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #ddd;">
                                <th style="padding: 12px; text-align: left;">No</th>
                                <th style="padding: 12px; text-align: left;">Foto</th>
                                <th style="padding: 12px; text-align: left;">Judul Foto</th>
                                <th style="padding: 12px; text-align: left;">Kategori</th>
                                <th style="padding: 12px; text-align: left;">Deskripsi</th>
                                <th style="padding: 12px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (empty($daftar)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #777;">Data galeri tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($daftar as $no => $row): ?>
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="padding: 12px;"><?= $no + 1; ?></td>

                                    <td style="padding: 12px;">
                                        <?php if (!empty($row['gambar'])): ?>
                                            <img src="../../uploads/galeri/<?= htmlspecialchars($row['gambar']); ?>" width="70" height="50" style="object-fit:cover; border-radius:6px;" alt="">
                                        <?php else: ?>
                                            <span style="color: #888; font-size: 0.85rem;">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="padding: 12px; font-weight: 600; color: #1d3b2f;">
                                        <?= htmlspecialchars($row['judul_foto'] ?? '-'); ?>
                                    </td>

                                    <td style="padding: 12px;">
                                        <span style="background:#e3efdf; color:#1d3b2f; padding:4px 12px; border-radius:999px; font-size:.8rem; font-weight:600; white-space:nowrap;">
                                            <?= htmlspecialchars($kategoriGaleri[$row['kategori']]['judul'] ?? 'Suasana Cafe'); ?>
                                        </span>
                                    </td>

                                    <td style="padding: 12px; color: #666;">
                                        <?= htmlspecialchars($row['deskripsi'] ?? '-'); ?>
                                    </td>

                                    <td style="padding: 12px; text-align: center; white-space: nowrap;">
                                        <a href="edit.php?id=<?= (int) $row['id_galeri']; ?>" class="btn-action btn-edit" style="background: #f39c12; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; margin-right: 6px;">Edit</a>

                                        <form method="POST" action="hapus.php" style="display: inline;"
                                              onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf']; ?>">
                                            <input type="hidden" name="id" value="<?= (int) $row['id_galeri']; ?>">
                                            <button type="submit" class="btn-action btn-delete" style="background: #e74c3c; color: #fff; padding: 6px 12px; border: 0; border-radius: 6px; font-size: 0.85rem; cursor: pointer;">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

</body>
</html>