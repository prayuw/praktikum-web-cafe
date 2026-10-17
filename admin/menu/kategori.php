<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Samakan dengan pengecekan login di file admin lain
require_once __DIR__ . "/../../includes/auth.php";
require_once __DIR__ . "/../../config/database.php";

$folder = __DIR__ . "/../../uploads/menu/";

if (empty($_SESSION['csrf_kat'])) {
    $_SESSION['csrf_kat'] = bin2hex(random_bytes(16));
}

$error = "";
$pesan = $_SESSION['pesan_kat'] ?? "";
unset($_SESSION['pesan_kat']);

function simpanGambarKategori($file, $folder, &$error)
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;

    if ($file['error'] !== UPLOAD_ERR_OK) { $error = "Upload gambar gagal."; return null; }
    if ($file['size'] > 2 * 1024 * 1024)  { $error = "Ukuran gambar maksimal 2 MB."; return null; }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    if (!isset($izin[$mime])) { $error = "Format gambar harus JPG, PNG, atau WEBP."; return null; }

    $nama = "kat_" . bin2hex(random_bytes(8)) . "." . $izin[$mime];

    if (!move_uploaded_file($file['tmp_name'], $folder . $nama)) {
        $error = "Gagal menyimpan gambar.";
        return null;
    }

    return $nama;
}

function hitung($conn, $sql, $id)
{
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    return (int) mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0];
}

/* ---------- Proses form ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_kat'], $_POST['csrf'] ?? '')) {
        die("Token tidak valid.");
    }

    $aksi = $_POST['aksi'] ?? '';
    $id   = (int) ($_POST['id'] ?? 0);

    /* Simpan (tambah / ubah) */
    if ($aksi === 'simpan') {

        $nama   = trim($_POST['nama_kategori'] ?? '');
        $parent = (int) ($_POST['parent_id'] ?? 0);
        $parent = $parent > 0 ? $parent : null;

        if ($nama === '') {
            $error = "Nama kategori wajib diisi.";
        } elseif ($parent !== null && $parent === $id) {
            $error = "Kategori tidak bisa menjadi induk dirinya sendiri.";
        } elseif ($parent !== null && $id > 0
                  && hitung($conn, "SELECT COUNT(*) FROM kategori_menu WHERE parent_id = ?", $id) > 0) {
            $error = "Kategori ini punya sub kategori, jadi tidak bisa dijadikan sub kategori.";
        } else {
            $gambarBaru = simpanGambarKategori($_FILES['gambar'], $folder, $error);

            if ($error === '') {

                if ($id > 0) {
                    $stmt = mysqli_prepare($conn, "SELECT gambar FROM kategori_menu WHERE id_kategori = ?");
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    $lama = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

                    $gambar = $gambarBaru ?: ($lama['gambar'] ?? null);

                    $stmt = mysqli_prepare(
                        $conn,
                        "UPDATE kategori_menu
                         SET nama_kategori = ?, parent_id = ?, gambar = ?
                         WHERE id_kategori = ?"
                    );
                    mysqli_stmt_bind_param($stmt, "sisi", $nama, $parent, $gambar, $id);
                    mysqli_stmt_execute($stmt);

                    if ($gambarBaru && !empty($lama['gambar']) && is_file($folder . $lama['gambar'])) {
                        unlink($folder . $lama['gambar']);
                    }

                    $_SESSION['pesan_kat'] = "Kategori berhasil diperbarui.";

                } else {
                    $stmt = mysqli_prepare(
                        $conn,
                        "INSERT INTO kategori_menu (nama_kategori, parent_id, gambar) VALUES (?, ?, ?)"
                    );
                    mysqli_stmt_bind_param($stmt, "sis", $nama, $parent, $gambarBaru);
                    mysqli_stmt_execute($stmt);

                    $_SESSION['pesan_kat'] = "Kategori berhasil ditambahkan.";
                }

                header("Location: kategori.php");
                exit;
            }
        }
    }

    /* Hapus */
    if ($aksi === 'hapus' && $id > 0) {

        $jmlSub  = hitung($conn, "SELECT COUNT(*) FROM kategori_menu WHERE parent_id = ?", $id);
        $jmlMenu = hitung($conn, "SELECT COUNT(*) FROM menu WHERE id_kategori = ?", $id);

        if ($jmlSub > 0) {
            $error = "Tidak bisa dihapus: masih ada $jmlSub sub kategori di dalamnya.";
        } elseif ($jmlMenu > 0) {
            $error = "Tidak bisa dihapus: masih ada $jmlMenu menu di kategori ini. Pindahkan atau hapus menunya dulu.";
        } else {
            $stmt = mysqli_prepare($conn, "SELECT gambar FROM kategori_menu WHERE id_kategori = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            $stmt = mysqli_prepare($conn, "DELETE FROM kategori_menu WHERE id_kategori = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);

            if (!empty($data['gambar']) && is_file($folder . $data['gambar'])) {
                unlink($folder . $data['gambar']);
            }

            $_SESSION['pesan_kat'] = "Kategori dihapus.";
            header("Location: kategori.php");
            exit;
        }
    }
}

/* ---------- Data tampilan ---------- */
$edit = null;

if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM kategori_menu WHERE id_kategori = ?");
    mysqli_stmt_bind_param($stmt, "i", $idEdit);
    mysqli_stmt_execute($stmt);
    $edit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$pilihanInduk = mysqli_query(
    $conn,
    "SELECT id_kategori, nama_kategori
     FROM kategori_menu
     WHERE parent_id IS NULL
     ORDER BY nama_kategori"
);

$daftar = mysqli_query(
    $conn,
    "SELECT k.*, p.nama_kategori AS induk,
            (SELECT COUNT(*) FROM menu m WHERE m.id_kategori = k.id_kategori) AS jml_menu
     FROM kategori_menu k
     LEFT JOIN kategori_menu p ON k.parent_id = p.id_kategori
     ORDER BY COALESCE(k.parent_id, k.id_kategori), k.parent_id IS NOT NULL, k.id_kategori"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Monstera Cafe</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

<div class="container">

    <div class="page-header">
        <a href="menu.php" class="btn btn-light">&larr; Kembali ke Kelola Menu</a>
        <h1 class="page-title">Kelola Kategori</h1>
    </div>

    <?php if ($pesan): ?>
        <div class="alert alert-success"><?= htmlspecialchars($pesan); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">

        <h3><?= $edit ? "Ubah kategori" : "Tambah kategori"; ?></h3>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_kat']; ?>">
            <input type="hidden" name="aksi" value="simpan">
            <input type="hidden" name="id" value="<?= (int) ($edit['id_kategori'] ?? 0); ?>">

            <label for="nama_kategori">Nama kategori</label>
            <input type="text" id="nama_kategori" name="nama_kategori" required
                   placeholder="Contoh: Pizza"
                   value="<?= htmlspecialchars($edit['nama_kategori'] ?? ''); ?>">

            <label for="parent_id">Induk</label>
            <select id="parent_id" name="parent_id">
                <option value="">- Kategori utama (tombol di atas) -</option>
                <?php while ($p = mysqli_fetch_assoc($pilihanInduk)): ?>
                    <?php if ($edit && (int) $p['id_kategori'] === (int) $edit['id_kategori']) continue; ?>
                    <option value="<?= (int) $p['id_kategori']; ?>"
                        <?= (int) ($edit['parent_id'] ?? 0) === (int) $p['id_kategori'] ? 'selected' : ''; ?>>
                        Sub kategori dari: <?= htmlspecialchars($p['nama_kategori']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="gambar">
                Gambar untuk kartu sub kategori (JPG, PNG, WEBP, maks. 2 MB)<?= $edit ? " - kosongkan jika tidak diganti" : ""; ?>
            </label>

            <?php if (!empty($edit['gambar'])): ?>
                <img src="../../uploads/menu/<?= htmlspecialchars($edit['gambar']); ?>" class="thumb" alt="">
            <?php endif; ?>

            <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp">

            <div class="form-actions">
                <button type="submit" class="btn"><?= $edit ? "Simpan perubahan" : "Tambah"; ?></button>
                <?php if ($edit): ?>
                    <a href="kategori.php" class="btn btn-light">Batal</a>
                <?php endif; ?>
            </div>

        </form>

    </div>

    <h2>Daftar kategori</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Induk</th>
                    <th>Jumlah menu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                <?php while ($row = mysqli_fetch_assoc($daftar)): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['gambar'])): ?>
                                <img src="../../uploads/menu/<?= htmlspecialchars($row['gambar']); ?>" class="thumb" alt="">
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $row['induk'] ? '&nbsp;&nbsp;&nbsp;↳ ' : '<strong>'; ?>
                            <?= htmlspecialchars($row['nama_kategori']); ?>
                            <?= $row['induk'] ? '' : '</strong>'; ?>
                        </td>
                        <td><?= htmlspecialchars($row['induk'] ?? '-'); ?></td>
                        <td><?= (int) $row['jml_menu']; ?></td>
                        <td>
                            <div class="actions">
                                <a href="kategori.php?edit=<?= (int) $row['id_kategori']; ?>" class="btn btn-light">Ubah</a>

                                <form method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_kat']; ?>">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id" value="<?= (int) $row['id_kategori']; ?>">
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </div>

</div>

</body>
</html>