<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT gambar FROM menu WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if ($data && $data['gambar'] && file_exists("../../uploads/menu/" . $data['gambar'])) {
    unlink("../../uploads/menu/" . $data['gambar']);
}

$delete = mysqli_query($conn, "DELETE FROM menu WHERE id = $id");

$_SESSION[$delete ? 'success' : 'error'] = $delete
    ? "Menu berhasil dihapus."
    : "Menu gagal dihapus.";

header("Location: menu.php");
exit;
?>
