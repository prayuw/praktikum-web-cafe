<?php
require_once "../config/database.php";

$nama = "Administrator";
$username = "admin";
$password = "admin123";
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$nama_safe = mysqli_real_escape_string($conn, $nama);
$username_safe = mysqli_real_escape_string($conn, $username);

$query = mysqli_query(
    $conn,
    "INSERT INTO admin (nama, username, password)
     VALUES ('$nama_safe', '$username_safe', '$password_hash')"
);

echo $query ? "Admin berhasil dibuat." : "Gagal membuat admin: " . mysqli_error($conn);
?>
