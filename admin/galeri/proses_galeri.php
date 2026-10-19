<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once "../../config/database.php";

// hanya menerima kiriman form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: galeri.php");
    exit;
}

// cek token keamanan
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    die("Token tidak valid.");
}

$folder = __DIR__ . "/../../uploads/galeri/";

/* ---------- Fungsi bantu ---------- */

function kembali($tipe, $pesan, $tujuan = "galeri.php")
{
    $_SESSION['flash_galeri'] = ['tipe' => $tipe, 'pesan' => $pesan];
    header("Location: " . $tujuan);
    exit;
}

// Mengembalikan nama file baru, null jika tidak ada file, atau string "ERROR:..." jika gagal
function uploadGambar($file, $folder)
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "ERROR:Upload gambar gagal.";
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return "ERROR:Ukuran gambar maksimal 2 MB.";
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

    $izin = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($izin[$mime])) {
        return "ERROR:Format gambar harus JPG, PNG, atau WEBP.";
    }

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $nama = "galeri_" . bin2hex(random_bytes(8)) . "." . $izin[$mime];

    if (!move_uploaded_file($file['tmp_name'], $folder . $nama)) {
        return "ERROR:Gagal menyimpan gambar.";
    }

    return $nama;
}

/* ---------- Data dari form ---------- */

$aksi       = $_POST['aksi'] ?? '';
$judul_foto = trim($_POST['judul_foto'] ?? '');
$deskripsi  = trim($_POST['deskripsi'] ?? '');
$kategori   = $_POST['kategori'] ?? 'suasana';

if (!isset($kategoriGaleri[$kategori])) {
    $kategori = 'suasana';
}

/* ---------- TAMBAH ---------- */
if ($aksi === 'tambah') {

    if ($judul_foto === '') {
        kembali('error', 'Judul foto wajib diisi.', 'tambah.php');
    }

    $gambar = uploadGambar($_FILES['gambar'], $folder);

    if ($gambar === null) {
        kembali('error', 'Pilih gambar terlebih dahulu.', 'tambah.php');
    }

    if (strpos($gambar, 'ERROR:') === 0) {
        kembali('error', substr($gambar, 6), 'tambah.php');
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO galeri (judul_foto, deskripsi, kategori, gambar)
         VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ssss", $judul_foto, $deskripsi, $kategori, $gambar);
    mysqli_stmt_execute($stmt);

    kembali('success', 'Foto berhasil ditambahkan.');
}

/* ---------- EDIT ---------- */
if ($aksi === 'edit') {

    $id = (int) ($_POST['id_galeri'] ?? 0);

    // ambil data lama (untuk gambar lama)
    $stmt = mysqli_prepare($conn, "SELECT gambar FROM galeri WHERE id_galeri = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $lama = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$lama) {
        kembali('error', 'Data galeri tidak ditemukan.');
    }

    $kembaliKe = "edit.php?id=" . $id;

    if ($judul_foto === '') {
        kembali('error', 'Judul foto wajib diisi.', $kembaliKe);
    }

    $gambarBaru = uploadGambar($_FILES['gambar'], $folder);

    if ($gambarBaru !== null && strpos($gambarBaru, 'ERROR:') === 0) {
        kembali('error', substr($gambarBaru, 6), $kembaliKe);
    }

    $gambar = $gambarBaru ?? $lama['gambar'];

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE galeri
         SET judul_foto = ?, deskripsi = ?, kategori = ?, gambar = ?
         WHERE id_galeri = ?"
    );
    mysqli_stmt_bind_param($stmt, "ssssi", $judul_foto, $deskripsi, $kategori, $gambar, $id);
    mysqli_stmt_execute($stmt);

    // hapus file gambar lama jika diganti
    if ($gambarBaru !== null && !empty($lama['gambar']) && is_file($folder . $lama['gambar'])) {
        unlink($folder . $lama['gambar']);
    }

    kembali('success', 'Foto berhasil diperbarui.');
}

// aksi tidak dikenal
header("Location: galeri.php");
exit;