<?php
// includes/navbar.php
$currentPage = basename($_SERVER['PHP_SELF']);

$navLinks = [
    'index.php'     => 'Beranda',
    'tentang_kami.php'   => 'Tentang Kami',
    'menu.php'      => 'Menu',
    'reservasi.php' => 'Reservasi',
    'galeri.php'    => 'Galeri',
    'faq.php'       => 'FAQ',
];
?>
<nav class="navbar">
    <div class="container navbar-container">

        <a class="brand" href="index.php">
            <img src="assets/logo.png" alt="" class="brand-logo">
            Monstera Cafe
        </a>

        <button class="nav-toggle" id="navToggle" type="button"
                aria-label="Buka menu navigasi"
                aria-expanded="false"
                aria-controls="navMenu">
            <span></span><span></span><span></span>
        </button>

        <div class="nav-menu" id="navMenu">
            <div class="nav-links">
                <?php foreach ($navLinks as $file => $label): ?>
                    <a href="<?= $file; ?>" class="<?= $currentPage === $file ? 'active' : ''; ?>">
                        <?= $label; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <a href="reservasi.php" class="btn btn-accent nav-cta">Reservasi Sekarang</a>
        </div>

    </div>
</nav>

<script>
(function () {
    var toggle = document.getElementById('navToggle');
    var menu   = document.getElementById('navMenu');

    toggle.addEventListener('click', function () {
        var terbuka = menu.classList.toggle('open');
        toggle.setAttribute('aria-expanded', terbuka);
    });
})();
</script>