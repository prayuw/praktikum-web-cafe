<?php
$currentFile = basename($_SERVER['PHP_SELF']);
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <img src="/praktikum_web_cafe/assets/logo.png" alt="Logo Monstera" style="width: 36px; height: 36px; object-fit: contain; border-radius: 8px;">
        <span class="brand-text">Monstera Eatery</span>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="/praktikum_web_cafe/admin/index.php" class="<?= ($currentFile == 'index.php' && $currentDir == 'admin') ? 'active' : ''; ?>">
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/praktikum_web_cafe/admin/menu/menu.php" class="<?= ($currentDir == 'menu') ? 'active' : ''; ?>">
                <span>Kelola Menu</span>
            </a>
        </li>
        <li>
            <a href="/praktikum_web_cafe/admin/galeri/galeri.php" class="<?= ($currentDir == 'galeri') ? 'active' : ''; ?>">
                <span>Kelola Galeri</span>
            </a>
        </li>
        <li>
            <a href="/praktikum_web_cafe/admin/tentang_kami/tentang_kami.php" class="<?= ($currentDir == 'tentang_kami') ? 'active' : ''; ?>">
                <span>Kelola Tentang Kami</span>
            </a>
        </li>
        <li>
            <a href="/praktikum_web_cafe/admin/faq/faq.php" class="<?= ($currentFile == 'faq.php') ? 'active' : ''; ?>">
                <span>Kelola FAQ</span>
            </a>
        </li>
         <li>
            <a
                href="/praktikum_web_cafe/admin/testimoni/testimoni.php"
                class="<?= ($currentDir == 'testimoni') ? 'active' : ''; ?>">
                <span>Kelola Testimoni</span>
            </a>
        </li>
        
        
    </ul>
</aside>