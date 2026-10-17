<?php
session_start();
require_once "../config/database.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_input = $_POST['password'];

    // Cek data admin berdasarkan username terlebih dahulu
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        $db_password = $row['password'];

        // Pengecekan fleksibel: Mendukung MD5 atau Plain Text (Teks Biasa)
        if ($db_password === $password_input || $db_password === md5($password_input)) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            
            // Arahkan ke dashboard admin utama
            header("Location: ../admin/index.php");
            exit;
        }
    }

    // Jika gagal
    header("Location: login.php?pesan=gagal");
    exit;
}
?>