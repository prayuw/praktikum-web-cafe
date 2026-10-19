    <?php
    require_once "config/database.php";

    $currentPage = basename($_SERVER['PHP_SELF']);
    // Diperbaiki menggunakan id_menu sesuai kolom database Anda
    $query = mysqli_query(
        $conn,
        "SELECT * FROM menu ORDER BY id_menu DESC LIMIT 6"
    );

    
$query_testimoni = mysqli_query(
    $conn,
    "SELECT * FROM testimoni
     WHERE status = 'aktif'
     ORDER BY id_testimoni DESC
     LIMIT 3"
);


    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Monstera Cafe</title>
        <link rel="stylesheet" href="assets/style.css">
    </head>

    <body>

        <?php include "includes/navbar.php"; ?>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggle = document.getElementById("navToggle");
                const menu = document.getElementById("navMenu");

                toggle.addEventListener("click", function() {
                    const isOpen = menu.classList.toggle("is-open");

                    toggle.classList.toggle("is-active", isOpen);
                    toggle.setAttribute("aria-expanded", isOpen);
                    toggle.setAttribute(
                        "aria-label",
                        isOpen ? "Tutup menu navigasi" : "Buka menu navigasi"
                    );
                });

                menu.querySelectorAll("a").forEach(function(link) {
                    link.addEventListener("click", function() {
                        menu.classList.remove("is-open");
                        toggle.classList.remove("is-active");
                        toggle.setAttribute("aria-expanded", "false");
                        toggle.setAttribute("aria-label", "Buka menu navigasi");
                    });
                });
            });
        </script>


        <section class="hero-slider-wrapper">
            <!-- Slide 1 (Default) -->
            <div class="hero-slide active" data-slide="0">
                <div class="container hero-content">
                    <span class="eyebrow">Cafe • Cozy • Green</span>
                    <h1>Welcome to Monstera Eatery</h1>
                    <p>Where nature meets flavor - dine amidst a botanical oasis.</p>
                    <div class="hero-actions">
                        <a href="#menu-section" class="btn btn-accent">Explore Menu</a>
                    </div>
                </div>
                <img src="assets/hero.png" alt="Suasana Monstera Eatery" class="hero-img">
            </div>

            <!-- Slide 2 (Uplifting from Taste) -->
            <div class="hero-slide" data-slide="1">
                <div class="container hero-content">
                    <span class="eyebrow">Cafe • Cozy • Green</span>
                    <h1>UPLIFTING FROM TASTE</h1>
                    <p>“Rasakan harmoni rasa dalam setiap sajian”</p>
                    <div class="hero-actions">
                        <a href="#reservasi-section" class="btn btn-accent">Reservation Ready</a>
                    </div>
                </div>
                <img src="assets/reservasi.jpeg" alt="Uplifting from Taste Monstera" class="hero-img hero-img-landscape">
            </div>

            <!-- Tombol Panah Geser Kiri / Kanan -->
            <button class="hero-nav-btn prev-btn" id="prevHero" aria-label="Slide Sebelumnya">&#10094;</button>
            <button class="hero-nav-btn next-btn" id="nextHero" aria-label="Slide Berikutnya">&#10095;</button>
        </section>

        <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const slides = document.querySelectorAll(".hero-slide");
                    const nextBtn = document.getElementById("nextHero");
                    const prevBtn = document.getElementById("prevHero");
                    let currentSlide = 0;

                    function showSlide(index) {
                        slides.forEach((slide, i) => {
                            slide.classList.toggle("active", i === index);
                        });
                    }

                    nextBtn.addEventListener("click", function() {
                        currentSlide = (currentSlide + 1) % slides.length;
                        showSlide(currentSlide);
                    });

                    prevBtn.addEventListener("click", function() {
                        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                        showSlide(currentSlide);
                });
            });
        </script>

        <section class="about">
            <div class="container about-inner">

                <img
                    src="assets/tentang.jpeg"
                    alt="Interior Monstera Eatery"
                    class="about-img">

                <div class="about-text" data-watermark="Cerita Kita">
                    <h2>Cerita Kita</h2>
                    <p>
                        Cafe Monstera berawal dari ketertarikan Pak Heri terhadap dunia desain
                        dan advertising. Ketertarikan tersebut kemudian berkembang menjadi usaha
                        percetakan dan advertising yang dikenal dengan nama The Print. Lokasi
                        yang saat ini menjadi Cafe Monstera sebelumnya merupakan sebuah rumah
                        yang kemudian dimanfaatkan sebagai bagian dari pengembangan usaha, dan
                        area depannya dikembangkan menjadi sebuah kafe.
                    </p>

                    <a href="tentang_kami.php" class="btn">Selengkapnya</a>
                </div>

            </div>
        </section>

        <section class="section" id="menu-section">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Rekomendasi</span>
                        <h2>Menu Pilihan Monstera</h2>
                    </div>
                    <a href="menu.php" class="btn">Semua Menu</a>
                </div>

                <div class="grid">
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <article class="card product-card">
                            <?php if (!empty($row['gambar'])): ?>
                                <img
                                    class="product-image"
                                    src="<?= 'uploads/menu/' . htmlspecialchars($row['gambar']); ?>"
                                    alt="<?= htmlspecialchars($row['nama_menu']); ?>">
                            <?php else: ?>
                                <div class="product-image no-image">
                                    Tidak ada gambar
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <!-- Menampilkan kategori menu jika diperlukan -->
                                <h3><?= htmlspecialchars($row['nama_menu']); ?></h3>
                                <p><?= htmlspecialchars($row['deskripsi'] ?? ''); ?></p>
                                <div class="actions">
                                    <strong class="price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></strong>
                                    <!-- Diperbaiki menggunakan id_menu -->
                                    <a href="detail.php?id=<?= $row['id_menu']; ?>" class="btn">Detail</a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <section class="reserve" id="reservasi-section">
            <div class="container reserve-inner">

                <div class="reserve-text">
                    <h2>Pesan Tempat Lebih Mudah</h2>

                    <p>
                        Monstera Cafe menyediakan fitur reservasi tempat. Pastikan moment
                        bersama kerabat dan teman menjadi lebih solid dengan memesan tempat.
                    </p>

                    <a href="reservasi.php" class="btn btn-accent">Reserve Now</a>
                </div>

                <img
                    src="assets/reservasi.jpeg"
                    alt="Area outdoor Monstera Cafe"
                    class="reserve-img">

            </div>
        </section>


        <!-- TESTIMONI -->
        
<section class="testimoni-section">
    <div class="container">
        <div class="testimoni-heading">
            <span class="eyebrow">Pengalaman pelanggan</span>
            <h2>TESTIMONI</h2>
        </div>

        <div class="testimoni-grid">
            <?php while ($row = mysqli_fetch_assoc($query_testimoni)): ?>
                <article class="testimoni-card">

                    <?php if (!empty($row['foto_profil'])): ?>
                        <img
                            src="uploads/testimoni/<?= htmlspecialchars($row['foto_profil']); ?>"
                            alt="Foto <?= htmlspecialchars($row['nama']); ?>"
                            class="testimoni-avatar">
                    <?php else: ?>
                        <div class="testimoni-avatar testimoni-avatar-placeholder"
                             aria-label="Foto profil belum tersedia">
                            <span>👤</span>
                        </div>
                    <?php endif; ?>

                    <blockquote>
                        “<?= htmlspecialchars($row['isi_testimoni']); ?>”
                    </blockquote>

                    <p class="testimoni-author">
                        <?= htmlspecialchars($row['nama']); ?>
                        — <?= htmlspecialchars($row['profesi']); ?>
                    </p>

                </article>
            <?php endwhile; ?>
        </div>

        <?php if (mysqli_num_rows($query_testimoni) === 0): ?>
            <p class="testimoni-empty">
                Testimoni pelanggan akan segera hadir.
            </p>
        <?php endif; ?>
    </div>
</section>



        <!-- INSTAGRAM -->
        <section class="instagram-section">
            <div class="container">

                <div class="instagram-heading">
                    <span>Follow kami!</span>
                    <h2>@monstera.eatery</h2>

                    <a
                        href="https://www.instagram.com/monstera.eatery"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="instagram-follow">
                        <img
                            src="assets/instagram.png"
                            alt="Instagram"
                            class="instagram-icon">
                        Follow
                    </a>
                </div>

                <div class="instagram-grid">
                    <a href="https://www.instagram.com/reel/DeWm8Y2BQbA/?mdxt=aTNxd3h4dDkwcjA4" target="_blank" rel="noopener noreferrer" class="instagram-item">
                        <img src="assets/konten1.png" alt="Konten Instagram Cafe Monstera">
                    </a>
                    <a href="https://www.instagram.com/p/DeUHURXTnaV/?mdxt=MWc1NWJsZnQyamZlcA==" target="_blank" rel="noopener noreferrer" class="instagram-item">
                        <img src="assets/konten2.png" alt="Menu minuman Cafe Monstera">
                    </a>
                    <a href="https://www.instagram.com/reel/Deg6LEcBMUe/?mdxt=MWhkMjU2N2FiNG9jbg==" target="_blank" rel="noopener noreferrer" class="instagram-item">
                        <img src="assets/konten3.png" alt="Konten barista Cafe Monstera">
                    </a>
                    <a href="https://www.instagram.com/reel/DeElX2jB718/?stkn=MTFndmw0NXlybTNidA==" target="_blank" rel="noopener noreferrer" class="instagram-item">
                        <img src="assets/konten4.png" alt="Suasana Cafe Monstera">
                    </a>
                    <a href="https://www.instagram.com/p/Denk3pqzzoU/?utm_source=ig_web_copy_link&vrfl=MzRlODBiNWFlZA==&srtk=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer" class="instagram-item">
                        <img src="assets/konten5.jpeg" alt="Menu makanan Cafe Monstera">
                    </a>
                    <a href="https://www.instagram.com/reel/Dd83CyVhdvB/?stkn=ZnI0eHZsMW1hZmZq" target="_blank" rel="noopener noreferrer" class="instagram-item">
                        <img src="assets/konten6.png" alt="Aktivitas Cafe Monstera">
                    </a>
                </div>
            </div>
        </section>

        <?php include "includes/footer.php"; ?>
    </body>

    </html>