<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once "../../config/database.php";

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

$flash = $_SESSION['flash_galeri'] ?? null;
unset($_SESSION['flash_galeri']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Foto - Admin Monstera Eatery</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body class="form-page">

    <div class="form-card">

        <a href="galeri.php" class="form-back">&larr; Kembali ke Kelola Galeri</a>

        <h1>Tambah Foto Baru</h1>
        <p class="form-sub">Tambahkan foto suasana atau meja ke galeri kafe.</p>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']); ?>">
                <?= htmlspecialchars($flash['pesan']); ?>
            </div>
        <?php endif; ?>

        <form action="proses_galeri.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf']; ?>">
            <input type="hidden" name="aksi" value="tambah">

            <label for="judul_foto">Judul Foto</label>
            <input type="text" id="judul_foto" name="judul_foto" maxlength="150" required
                   placeholder="Contoh: Area Outdoor Sore Hari">

            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" required>
                <?php foreach ($kategoriGaleri as $kunci => $info): ?>
                    <option value="<?= $kunci; ?>">
                        <?= htmlspecialchars($info['judul'] . ($info['keterangan'] ? ' (' . $info['keterangan'] . ')' : '')); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="deskripsi">Deskripsi (opsional)</label>
            <textarea id="deskripsi" name="deskripsi" rows="3"
                      placeholder="Contoh: Meja nomor 3, cocok untuk berempat"></textarea>

            <label for="gambar">Gambar (JPG, PNG, WEBP, maks. 2 MB)</label>
            <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp" required>

            <img id="preview" class="form-preview" alt="" style="display: none;">

            <div class="form-actions">
                <button type="submit" class="btn">Simpan Data</button>
                <a href="galeri.php" class="btn-text">Batal</a>
            </div>

        </form>

    </div>

    <script>
        // pratinjau gambar sebelum disimpan
        document.getElementById("gambar").addEventListener("change", function () {
            const preview = document.getElementById("preview");
            const file = this.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = "block";
            } else {
                preview.style.display = "none";
            }
        });
    </script>

</body>
</html>