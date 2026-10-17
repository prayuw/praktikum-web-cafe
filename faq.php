<?php 
require_once "config/database.php";

// Mendapatkan nama file yang sedang aktif saat ini untuk navbar
$currentPage = basename($_SERVER['PHP_SELF']); 

// Mengambil data pertanyaan dan jawaban dari tabel 'faq' di database
$query = mysqli_query($conn, "SELECT * FROM faq");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Monstera Cafe</title>
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

    <!-- KONTEN FAQ -->
    <main class="faq-section">
        <div class="container faq-container">
            <div class="faq-header">
                <h1>FAQ (Frequently Asked Question)</h1>
                <p class="faq-intro-text">
                    "Untuk Monstera Eatery yang lebih baik, kami sangat menghargai masukan dari kamu untuk perbaikan layanan kami kedepannya. Bagaimana cara mengajukan feedback/keluhan?"
                </p>
                <p class="faq-desc">
                    Untuk pengajuan feedback/keluhan terhadap pelayanan dan produk Monstera Eatery, kamu dapat mengakses chatbot yang tersedia di website kami atau dapat menghubungi Whatsapp Customer Care Monstera Eatery yang bisa kamu akses disini.
                </p>
                <p class="faq-subtext">
                    Untuk membantu beberapa pertanyaan umum pengunjung seputar Monstera Eatery, berikut beberapa pertanyaan dan jawabannya:
                </p>
            </div>

            <!-- Daftar Akordeon FAQ Dinamis dari Database -->
            <div class="faq-list">
                <?php 
                $no = 1;
                if (mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):
                ?>
                        <div class="faq-item">
                            <button class="faq-question" type="button">
                                <span class="faq-q-title"><b><?= $no++; ?>.</b> <?= htmlspecialchars($row['pertanyaan']); ?></span>
                                <span class="faq-icon">+</span>
                            </button>
                            <div class="faq-answer">
                                <p><?= nl2br(htmlspecialchars($row['jawaban'])); ?></p>
                            </div>
                        </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p style="text-align: center; color: var(--muted); padding: 20px;">Belum ada pertanyaan FAQ yang ditambahkan.</p>
                <?php endif; ?>
            </div>

            <!-- Bagian Dapatkan Layanan / WhatsApp -->
            <div class="service-box">
                <h2>DAPATKAN LAYANAN</h2>
                <div class="service-card">
                    <div class="service-info">
                        <svg class="wa-icon" viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        <div>
                            <strong>Ada pertanyaan?</strong>
                            <span>Chat kami di WhatsApp</span>
                        </div>
                    </div>
                    <a href="https://wa.me/628xxxxxxxxxx" target="_blank" class="service-btn">
                        Chat kami sekarang &rsaquo;
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- JavaScript untuk Interaktif Accordion FAQ -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const faqItems = document.querySelectorAll(".faq-item");

        faqItems.forEach(item => {
            const questionBtn = item.querySelector(".faq-question");
            
            questionBtn.addEventListener("click", () => {
                // Tutup item lain jika ingin 1 yang terbuka dalam satu waktu (opsional)
                // faqItems.forEach(otherItem => {
                //     if (otherItem !== item) otherItem.classList.remove("active");
                // });

                item.classList.toggle("active");
            });
        });
    });
    </script>

    <?php include "includes/footer.php"; ?>
</body>

</html>