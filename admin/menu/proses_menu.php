<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$aksi = $_POST['aksi'] ?? '';

function uploadGambarMenu($field, $redirect) {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $file = $_FILES[$field];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $allowed, true)) {
        $_SESSION['error'] = "Format gambar tidak diperbolehkan.";
        header("Location: $redirect");
        exit;
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $_SESSION['error'] = "Ukuran gambar maksimal 2 MB.";
        header("Location: $redirect");
        exit;
    }

    $nama_file = uniqid('menu_', true) . "." . $extension;
    if (!move_uploaded_file($file['tmp_name'], "../../uploads/menu/" . $nama_file)) {
        $_SESSION['error'] = "Gambar gagal diupload.";
        header("Location: $redirect");
        exit;
    }

    return $nama_file;
}

if ($aksi === 'tambah') {
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = $_POST['harga'] ?? '';
    $stok = $_POST['stok'] ?? '';

    if ($nama === '' || $kategori === '' || $deskripsi === '' || $harga === '' || $stok === '') {
        $_SESSION['error'] = "Semua data wajib diisi.";
        header("Location: tambah.php");
        exit;
    }

    if (!is_numeric($harga) || !is_numeric($stok) || $harga < 0 || $stok < 0) {
        $_SESSION['error'] = "Harga dan stok harus berupa angka yang valid.";
        header("Location: tambah.php");
        exit;
    }

    $gambar = uploadGambarMenu('gambar', 'tambah.php');

    $nama = mysqli_real_escape_string($conn, $nama);
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $gambar_sql = $gambar ? "'" . mysqli_real_escape_string($conn, $gambar) . "'" : "NULL";

    $query = mysqli_query($conn,
        "INSERT INTO menu (nama, kategori, deskripsi, harga, stok, gambar)
         VALUES ('$nama', '$kategori', '$deskripsi', '$harga', '$stok', $gambar_sql)"
    );

    $_SESSION[$query ? 'success' : 'error'] = $query
        ? "Menu berhasil ditambahkan."
        : "Menu gagal ditambahkan.";

    header("Location: menu.php");
    exit;
}

if ($aksi === 'edit') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = $_POST['harga'] ?? '';
    $stok = $_POST['stok'] ?? '';

    if ($id <= 0 || $nama === '' || $kategori === '' || $deskripsi === '' || $harga === '' || $stok === '') {
        $_SESSION['error'] = "Data edit belum lengkap.";
        header("Location: edit.php?id=$id");
        exit;
    }

    if (!is_numeric($harga) || !is_numeric($stok) || $harga < 0 || $stok < 0) {
        $_SESSION['error'] = "Harga dan stok harus berupa angka yang valid.";
        header("Location: edit.php?id=$id");
        exit;
    }

    $old_query = mysqli_query($conn, "SELECT gambar FROM menu WHERE id = $id");
    $old_data = mysqli_fetch_assoc($old_query);
    if (!$old_data) {
        $_SESSION['error'] = "Menu tidak ditemukan.";
        header("Location: menu.php");
        exit;
    }

    $gambar = $old_data['gambar'];
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar_baru = uploadGambarMenu('gambar', "edit.php?id=$id");
        if ($gambar_baru) {
            if ($gambar && file_exists("../../uploads/menu/" . $gambar)) {
                unlink("../../uploads/menu/" . $gambar);
            }
            $gambar = $gambar_baru;
        }
    }

    $nama = mysqli_real_escape_string($conn, $nama);
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $gambar_safe = mysqli_real_escape_string($conn, $gambar);

    $query = mysqli_query($conn,
        "UPDATE menu SET
         nama='$nama',
         kategori='$kategori',
         deskripsi='$deskripsi',
         harga='$harga',
         stok='$stok',
         gambar='$gambar_safe'
         WHERE id=$id"
    );

    $_SESSION[$query ? 'success' : 'error'] = $query
        ? "Menu berhasil diperbarui."
        : "Menu gagal diperbarui.";

    header("Location: menu.php");
    exit;
}

$_SESSION['error'] = "Aksi tidak dikenali.";
header("Location: menu.php");
exit;
?>
