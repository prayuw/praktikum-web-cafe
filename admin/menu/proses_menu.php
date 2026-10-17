<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

if (isset($_POST['update'])) {
    $id_menu = intval($_POST['id_menu']);
    $nama_menu = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $id_kategori = intval($_POST['id_kategori']);
    $harga = intval($_POST['harga']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah ada file gambar baru yang diunggah
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];

    if (!empty($gambar)) {
        // Buat nama unik untuk file baru
        $gambar_baru = time() . '_' . basename($gambar);
        $target_dir = "../../uploads/menu/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Pindahkan file baru
        if (move_uploaded_file($tmp_name, $target_dir . $gambar_baru)) {
            // Hapus gambar lama jika ada
            if (!empty($gambar_lama) && file_exists($target_dir . $gambar_lama)) {
                unlink($target_dir . $gambar_lama);
            }
            $nama_file_foto = $gambar_baru;
        } else {
            $nama_file_foto = $gambar_lama;
        }
    } else {
        // Jika tidak diunggah, tetap gunakan gambar lama
        $nama_file_foto = $gambar_lama;
    }

    // Jalankan kueri UPDATE ke database
    $query = "UPDATE menu SET 
                id_kategori = $id_kategori, 
                nama_menu = '$nama_menu', 
                harga = $harga, 
                deskripsi = '$deskripsi', 
                gambar = '$nama_file_foto' 
              WHERE id_menu = $id_menu";

    if (mysqli_query($conn, $query)) {
        header("Location: menu.php?pesan=sukses");
        exit;
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
} else {
    header("Location: menu.php");
    exit;
}
?>