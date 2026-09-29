<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - Monstera Cafe</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container" style="max-width:480px; padding-top:80px;">
    <div class="card">
        <span class="eyebrow">Monstera Cafe</span>
        <h1>Login Admin</h1>
        <p class="muted">Masuk untuk mengelola menu dan galeri cafe.</p>
        <?php include "../includes/flash.php"; ?>
        <form action="proses_login.php" method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <div class="form-actions">
                <button type="submit" class="btn">Login</button>
                <a href="../index.php" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
