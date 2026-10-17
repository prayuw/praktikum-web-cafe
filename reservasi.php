<?php 
// Mendapatkan nama file yang sedang aktif saat ini
$currentPage = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi - Monstera Cafe</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    
<?php include "includes/navbar.php"; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("navToggle");
    const menu = document.getElementById("navMenu");

    if (toggle && menu) {
        toggle.addEventListener("click", function () {
            const isOpen = menu.classList.toggle("is-open");
            toggle.classList.toggle("is-active", isOpen);
            toggle.setAttribute("aria-expanded", isOpen);
        });
    }
});
</script>

    <!-- BANNER HEADER RESERVASI -->
    <header class="page-banner">
        <div class="page-banner-overlay"></div>
        <img src="assets/reservasi.jpeg" alt="Banner Reservasi Cafe Monstera" class="page-banner-img">
        <div class="page-banner-content">
            <h1>RESERVASI</h1>
        </div>
    </header>

    <!-- FORMULIR RESERVASI & PESAN MENU -->
    <main class="reservation-section">
        <div class="container reservation-container">
            <div class="reservation-heading">
                <h2>RESERVASI & PESAN MENU</h2>
                <div class="reservation-divider"></div>
                <p class="reservation-desc">
                    Silahkan pesan tempat dan menu di Monstera Eatery Cafe.<br>
                    Dimohon untuk melakukan reservasi sehari (@1×24jam) sebelumnya.<br>
                    <span class="text-danger">*Khusus untuk reservasi sehari sebelumnya</span>
                </p>
            </div>

            <form action="" method="POST" class="reservation-form">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="nama" placeholder="Nama*" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="telp" placeholder="No. Telp/WhatsApp*" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="tempat_duduk" placeholder="Tempat Duduk*" required>
                    </div>
                    <div class="form-group">
                        <input type="datetime-local" name="tanggal_waktu" placeholder="Tanggal dan Waktu Reservasi*" required>
                    </div>
                </div>

                <div class="form-group-full">
                    <textarea name="pesan_menu" rows="4" placeholder="Pesan Menu*" required></textarea>
                </div>

                <div class="form-group-full">
                    <textarea name="catatan" rows="3" placeholder="Catatan Tambahan (Opsional)"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-pesan">PESAN</button>
                </div>
            </form>
        </div>
    </main>

    <?php include "includes/footer.php"; ?>
</body>

</html>