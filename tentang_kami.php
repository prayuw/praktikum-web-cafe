<?php
// Mendapatkan nama file yang sedang aktif saat ini
$currentPage = basename($_SERVER['PHP_SELF']);

require_once "config/database.php"; // Pastikan path-nya benar

// Ambil semua data karyawan dari database
$query = mysqli_query($conn, "SELECT * FROM tentang_kami ORDER BY id_karyawan ASC");

// Masukkan data ke dalam array agar mudah dipetakan ke layout atas dan bawah
$karyawan = [];
while ($row = mysqli_fetch_assoc($query)) {
    $karyawan[] = $row;
} 


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Monstera Cafe</title>
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
            }
        });
    </script>

    <!-- KONTEN UTAMA (BELUM ADA ISI) -->
    <main style="min-height: 60vh; padding: 40  px 0;">
        <<!-- HERO SECTION TENTANG KAMI -->
            <section class="about-hero-section">
                <div class="container about-hero-container">
                    <!-- Sisi Kiri: Gambar Kafe dengan sudut melengkung dan gradasi pudar -->
                    <div class="about-hero-img-wrap">
                        <img src="assets/tentang.jpeg" alt="Suasana Cafe Monstera" class="about-hero-img">
                    </div>

                    <!-- Sisi Kanan: Teks Judul & Deskripsi -->
                    <div class="about-hero-text">
                        <span class="about-eyebrow">— About Monstera</span>
                        <h1>Tentang Kami</h1>
                        <p>Kenali tim kami lebih dekat, dari tempat usaha, suasana sekitarnya, sampai orang-orang di balik layar yang bekerja bersama kami!</p>
                    </div>
                </div>
            </section>

            <!-- SEJARAH / AWAL MULA BERDIRINYA MONSTERA EATERY -->
    <section class="history-section">
        <div class="container history-container">
            <!-- Sisi Kiri: Subjudul & Judul Besar -->
            <div class="history-title-wrap">
                <span class="eyebrow">SEJARAH CAFE MONSTERA.EATERY</span>
                <h2>AWAL MULA BERDIRINYA MONSTERA.EATERY</h2>
            </div>

            <!-- Sisi Kanan: Teks Cerita Sejarah -->
            <div class="history-text-wrap">
                <p>
                    Cafe Monstera bermula dari minat Pak Heri pada desain dan advertising, yang berkembang menjadi usaha percetakan bernama The Print. Ia membeli sebuah rumah yang bentuknya serupa dengan rumah di sebelahnya: bagian belakang dipakai untuk percetakan, sedangkan bagian depan yang belum terpakai dijadikan kafe. Di lokasi itu sudah berdiri dua kafe sebelumnya, yaitu Dierka dan Kisma Rasa, sebelum akhirnya Cafe Monstera hadir sebagai kafe ketiga.
                </p>
                <p>
                    Perjalanan panjang ini menunjukkan bahwa Cafe Monstera tidak hanya tempat menikmati makanan dan minuman, tetapi juga bagian dari usaha yang tumbuh dari kreativitas, desain, dan pemanfaatan ruang secara berkelanjutan.
                </p>
            </div>
        </div>
    </section>

    <!-- BAGIAN: OUR EXPERTS / PESAN PENDIRI -->
    <section class="expert-section">
        <div class="container">
            <div class="expert-card-box">
                <!-- Sisi Kiri: Foto Profil & Badge Nama -->
                <div class="expert-profile-area">
                    <div class="expert-img-wrapper">
                        <!-- Ganti path gambar dengan foto Pak Heri atau penanggung jawab kafe -->
                        <img src="assets/pp.jpg" alt="Pak Heri - Founder Cafe Monstera" class="expert-img">
                    </div>
                    <div class="expert-badge">
                        <h3>Pak Heri</h3>
                        <p>Founder & Owner Cafe Monstera</p>
                    </div>
                </div>

                <!-- Sisi Kanan: Judul & Kutipan Teks -->
                <div class="expert-content-area">
                    <h2>Pesan Pendiri</h2>
                    <p>
                        Cafe Monstera bermula dari kecintaan mendalam pada dunia desain dan estetika ruang, yang kini kami wujudkan ke dalam pengalaman bersantap yang nyaman. Visi kami tidak hanya sekadar menyajikan kopi dan hidangan berkualitas, tetapi juga menghadirkan ruang sosial yang hangat dan inspiratif bagi setiap pengunjung.
                    </p>
                    <p>
                        Keberhasilan perjalanan kami didasarkan pada kreativitas tanpa batas, ketekunan, serta kolaborasi yang erat antar tim untuk terus memberikan inovasi terbaik bagi masyarakat.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- BAGIAN: TIM MANAJEMEN / BOARD (LAYOUT 2 ATAS, 4 BAWAH) -->
    <section class="team-section-custom">
        <div class="container">
            <div class="team-header">
                <h2>Struktur Karyawan</h2>
            </div>

            <!-- Baris Atas: 2 Orang Pertama (Ukuran Besar) -->
            <div class="team-row-top">
                <?php 
                // Ambil 2 data pertama untuk baris atas
                for ($i = 0; $i < 2; $i++): 
                    if (isset($karyawan[$i])):
                        $row = $karyawan[$i];
                ?>
                    <div class="team-card large">
                        <div class="team-img-wrap">
                            <?php if (!empty($row['gambar'])): ?>
                                <img src="uploads/tentang_kami/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>" class="team-img">
                            <?php else: ?>
                                <img src="assets/pp.jpg" alt="Default" class="team-img">
                            <?php endif; ?>
                        </div>
                        <div class="team-badge">
                            <h3><?= htmlspecialchars($row['nama']); ?></h3>
                            <p><?= htmlspecialchars($row['jabatan']); ?></p>
                        </div>
                    </div>
                <?php 
                    endif;
                endfor; 
                ?>
            </div>

            <!-- Baris Bawah: Sisa Orang berikutnya (Ukuran Lebih Kecil) -->
            <div class="team-row-bottom">
                <?php 
                // Ambil data mulai dari indeks ke-2 (orang ke-3 dan seterusnya) untuk baris bawah
                for ($i = 2; $i < count($karyawan); $i++): 
                    $row = $karyawan[$i];
                ?>
                    <div class="team-card small">
                        <div class="team-img-wrap">
                            <?php if (!empty($row['gambar'])): ?>
                                <img src="uploads/tentang_kami/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>" class="team-img">
                            <?php else: ?>
                                <img src="assets/pp.jpg" alt="Default" class="team-img">
                            <?php endif; ?>
                        </div>
                        <div class="team-badge">
                            <h3><?= htmlspecialchars($row['nama']); ?></h3>
                            <p><?= htmlspecialchars($row['jabatan']); ?></p>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    </main>

    <?php include "includes/footer.php"; ?>
</body>

</html>