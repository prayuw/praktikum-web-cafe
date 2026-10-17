<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Hapus file gambar lama jika ada
    $q_old = mysqli_query($conn, "SELECT gambar FROM menu WHERE id_menu = $id");
    $d_old = mysqli_fetch_assoc($q_old);
    if (!empty($d_old['gambar']) && file_exists("../../uploads/menu/" . $d_old['gambar'])) {
        unlink("../../uploads/menu/" . $d_old['gambar']);
    }

    mysqli_query($conn, "DELETE FROM menu WHERE id_menu = $id");
}

header("Location: menu.php");
exit;
?>