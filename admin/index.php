<?php
require_once "../includes/auth.php";
require_once "../config/database.php";

$total_menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM menu"))['total'];
$total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) AS total FROM menu"))['total'];
$total_galeri = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM galeri"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - Monstera Cafe</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav class="navbar"><div class="container">
    <a class="brand" href="index.php">Admin Monstera</a>
    <a href="index.php">Dashboard</a>
    <a href="menu/menu.php">Menu</a>
    <a href="galeri/galeri.php">Galeri</a>
    <a href="../index.php">Website</a>
    <a href="logout.php">Logout</a>
</div></nav>

<div class="container">
    <div class="page-header">
        <span class="eyebrow">Dashboard</span>
        <h1 class="page-title">Halo, <?= htmlspecialchars($_SESSION['admin_nama']); ?> 👋</h1>
        <p class="muted">Kelola konten Monstera Cafe dari satu tempat.</p>
    </div>

    <?php include "../includes/flash.php"; ?>

    <div class="stats">
        <div class="stat"><small>Total Menu</small><strong><?= $total_menu; ?></strong></div>
        <div class="stat"><small>Kategori Menu</small><strong><?= $total_kategori; ?></strong></div>
        <div class="stat"><small>Total Galeri</small><strong><?= $total_galeri; ?></strong></div>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Kelola Menu</h2>
            <p class="muted">Tambah, edit, hapus, cari, dan kelola stok menu cafe.</p>
            <a href="menu/menu.php" class="btn">Kelola Menu</a>
        </div>
        <div class="card">
            <h2>Kelola Galeri</h2>
            <p class="muted">Kelola foto suasana dan kegiatan Monstera Cafe.</p>
            <a href="galeri/galeri.php" class="btn">Kelola Galeri</a>
        </div>
        <div class="card">
            <h2>Lihat Website</h2>
            <p class="muted">Buka halaman yang dilihat pengunjung.</p>
            <a href="../index.php" class="btn btn-light">Buka Website</a>
        </div>
    </div>
</div>
</body>
</html>
