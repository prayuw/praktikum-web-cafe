<?php
require_once "../../includes/auth.php";
require_once "../../config/database.php";

$aksi = $_POST['aksi'] ?? '';

function uploadGambarGaleri($redirect) {
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Foto wajib dipilih.";
        header("Location: $redirect");
        exit;
    }

    $file = $_FILES['gambar'];
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

    $nama_file = uniqid('galeri_', true) . "." . $extension;
    if (!move_uploaded_file($file['tmp_name'], "../../uploads/galeri/" . $nama_file)) {
        $_SESSION['error'] = "Foto gagal diupload.";
        header("Location: $redirect");
        exit;
    }

    return $nama_file;
}

if ($aksi === 'tambah') {
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($judul === '' || $deskripsi === '') {
        $_SESSION['error'] = "Judul dan deskripsi wajib diisi.";
        header("Location: tambah.php");
        exit;
    }

    $gambar = uploadGambarGaleri('tambah.php');
    $judul = mysqli_real_escape_string($conn, $judul);
    $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $gambar = mysqli_real_escape_string($conn, $gambar);

    $query = mysqli_query($conn,
        "INSERT INTO galeri (judul, deskripsi, gambar)
         VALUES ('$judul', '$deskripsi', '$gambar')"
    );

    $_SESSION[$query ? 'success' : 'error'] = $query
        ? "Foto galeri berhasil ditambahkan."
        : "Foto galeri gagal ditambahkan.";

    header("Location: galeri.php");
    exit;
}

if ($aksi === 'edit') {
    $id = (int)($_POST['id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($id <= 0 || $judul === '' || $deskripsi === '') {
        $_SESSION['error'] = "Data edit belum lengkap.";
        header("Location: edit.php?id=$id");
        exit;
    }

    $old_query = mysqli_query($conn, "SELECT gambar FROM galeri WHERE id = $id");
    $old_data = mysqli_fetch_assoc($old_query);

    if (!$old_data) {
        $_SESSION['error'] = "Data galeri tidak ditemukan.";
        header("Location: galeri.php");
        exit;
    }

    $gambar = $old_data['gambar'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar_baru = uploadGambarGaleri("edit.php?id=$id");
        if ($gambar && file_exists("../../uploads/galeri/" . $gambar)) {
            unlink("../../uploads/galeri/" . $gambar);
        }
        $gambar = $gambar_baru;
    }

    $judul = mysqli_real_escape_string($conn, $judul);
    $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $gambar = mysqli_real_escape_string($conn, $gambar);

    $query = mysqli_query($conn,
        "UPDATE galeri SET
         judul='$judul',
         deskripsi='$deskripsi',
         gambar='$gambar'
         WHERE id=$id"
    );

    $_SESSION[$query ? 'success' : 'error'] = $query
        ? "Foto galeri berhasil diperbarui."
        : "Foto galeri gagal diperbarui.";

    header("Location: galeri.php");
    exit;
}

$_SESSION['error'] = "Aksi tidak dikenali.";
header("Location: galeri.php");
exit;
?>
