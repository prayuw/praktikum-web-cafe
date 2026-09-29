<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu.";
    $login_path = (strpos($_SERVER['SCRIPT_NAME'], '/admin/menu/') !== false ||
                   strpos($_SERVER['SCRIPT_NAME'], '/admin/galeri/') !== false)
                  ? "../../auth/login.php"
                  : "../auth/login.php";
    header("Location: " . $login_path);
    exit;
}
?>
