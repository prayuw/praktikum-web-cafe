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
        document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById("navToggle");
            const menu = document.getElementById("navMenu");

            if (toggle && menu) {
                toggle.addEventListener("click", function() {
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
                        <!-- Logo SVG Asli WhatsApp -->
                        <svg class="wa-icon" viewBox="0 0 24 24" width="36" height="36" fill="#25D366" xmlns="http://www.w3.org/2000/svg">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
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
        document.addEventListener("DOMContentLoaded", function() {
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