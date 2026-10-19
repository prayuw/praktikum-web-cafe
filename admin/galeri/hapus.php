<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once "../../config/database.php";

// hanya menerima kiriman form (bukan link biasa)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: galeri.php");
    exit;
}

if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    die("Token tidak valid.");
}

$id     = (int) ($_POST['id'] ?? 0);
$folder = __DIR__ . "/../../uploads/galeri/";

$stmt = mysqli_prepare($conn, "SELECT gambar FROM galeri WHERE id_galeri = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    $_SESSION['flash_galeri'] = ['tipe' => 'error', 'pesan' => 'Data galeri tidak ditemukan.'];
    header("Location: galeri.php");
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM galeri WHERE id_galeri = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// hapus file gambarnya juga
if (!empty($data['gambar']) && is_file($folder . $data['gambar'])) {
    unlink($folder . $data['gambar']);
}

$_SESSION['flash_galeri'] = ['tipe' => 'success', 'pesan' => 'Foto berhasil dihapus.'];
header("Location: galeri.php");
exit;