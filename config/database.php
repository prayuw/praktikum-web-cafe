<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_cafe_monstera";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Kategori galeri (urutan di sini = urutan tampil di website)
$kategoriGaleri = [
    'suasana'   => ['judul' => 'Suasana Cafe', 'keterangan' => ''],
    'outdoor'   => ['judul' => 'Outdoor',      'keterangan' => 'Meja nomor 1-9'],
    'indoor'    => ['judul' => 'Indoor',       'keterangan' => 'Meja nomor 11-16'],
    'indoor_ac' => ['judul' => 'Indoor AC',    'keterangan' => 'Meja nomor 17-23'],
    'lantai_2'  => ['judul' => 'Lantai 2',     'keterangan' => 'Meja nomor 23-28'],
];
?>
