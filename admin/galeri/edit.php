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

$id = (int) ($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM galeri WHERE id_galeri = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    $_SESSION['flash_galeri'] = ['tipe' => 'error', 'pesan' => 'Data galeri tidak ditemukan.'];
    header("Location: galeri.php");
    exit;
}

$flash = $_SESSION['flash_galeri'] ?? null;
unset($_SESSION['flash_galeri']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto - Admin Monstera Eatery</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body class="form-page">

    <div class="form-card">

        <a href="galeri.php" class="form-back">&larr; Kembali ke Kelola Galeri</a>

        <h1>Edit Foto</h1>
        <p class="form-sub">Ubah informasi foto yang ada di galeri.</p>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']); ?>">
                <?= htmlspecialchars($flash['pesan']); ?>
            </div>
        <?php endif; ?>

        <form action="proses_galeri.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf']; ?>">
            <input type="hidden" name="aksi" value="edit">
            <input type="hidden" name="id_galeri" value="<?= (int) $data['id_galeri']; ?>">

            <label for="judul_foto">Judul Foto</label>
            <input type="text" id="judul_foto" name="judul_foto" maxlength="150" required
                   value="<?= htmlspecialchars($data['judul_foto']); ?>">

            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" required>
                <?php foreach ($kategoriGaleri as $kunci => $info): ?>
                    <option value="<?= $kunci; ?>" <?= $data['kategori'] === $kunci ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($info['judul'] . ($info['keterangan'] ? ' (' . $info['keterangan'] . ')' : '')); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="deskripsi">Deskripsi (opsional)</label>
            <textarea id="deskripsi" name="deskripsi" rows="3"><?= htmlspecialchars($data['deskripsi'] ?? ''); ?></textarea>

            <label>Gambar saat ini</label>
            <?php if (!empty($data['gambar'])): ?>
                <img src="../../uploads/galeri/<?= htmlspecialchars($data['gambar']); ?>" class="form-preview" style="margin-top: 0; max-width: 260px;" alt="">
            <?php else: ?>
                <p class="muted">Belum ada gambar.</p>
            <?php endif; ?>

            <label for="gambar">Ganti gambar (kosongkan jika tidak diganti)</label>
            <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp">

            <img id="preview" class="form-preview" alt="" style="display: none;">

            <div class="form-actions">
                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="galeri.php" class="btn-text">Batal</a>
            </div>

        </form>

    </div>

    <script>
        // pratinjau gambar baru sebelum disimpan
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