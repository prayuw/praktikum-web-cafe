<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

if (isset($_POST['update'])) {
    $id_karyawan = intval($_POST['id_karyawan']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $gambar_lama = $_POST['gambar_lama'];

    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $target_dir = "../../uploads/tentang_kami/";

    if (!empty($gambar)) {
        $gambar_baru = time() . '_' . basename($gambar);
        if (move_uploaded_file($tmp_name, $target_dir . $gambar_baru)) {
            if (!empty($gambar_lama) && file_exists($target_dir . $gambar_lama)) {
                unlink($target_dir . $gambar_lama);
            }
            $nama_file_foto = $gambar_baru;
        } else {
            $nama_file_foto = $gambar_lama;
        }
    } else {
        $nama_file_foto = $gambar_lama;
    }

    mysqli_query($conn, "UPDATE tentang_kami SET nama = '$nama', jabatan = '$jabatan', gambar = '$nama_file_foto' WHERE id_karyawan = $id_karyawan");
    header("Location: tentang_kami.php");
    exit;
} else {
    header("Location: tentang_kami.php");
    exit;
}
?>