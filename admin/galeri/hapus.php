<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $q_old = mysqli_query($conn, "SELECT gambar FROM galeri WHERE id_galeri = $id");
    $d_old = mysqli_fetch_assoc($q_old);
    if (!empty($d_old['gambar']) && file_exists("../../uploads/galeri/" . $d_old['gambar'])) {
        unlink("../../uploads/galeri/" . $d_old['gambar']);
    }

    mysqli_query($conn, "DELETE FROM galeri WHERE id_galeri = $id");
}

header("Location: galeri.php");
exit;
?>