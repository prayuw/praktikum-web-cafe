<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT gambar FROM galeri WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if ($data && $data['gambar'] && file_exists("../../uploads/galeri/" . $data['gambar'])) {
    unlink("../../uploads/galeri/" . $data['gambar']);
}

$delete = mysqli_query($conn, "DELETE FROM galeri WHERE id = $id");

$_SESSION[$delete ? 'success' : 'error'] = $delete
    ? "Foto galeri berhasil dihapus."
    : "Foto galeri gagal dihapus.";

header("Location: galeri.php");
exit;
?>
