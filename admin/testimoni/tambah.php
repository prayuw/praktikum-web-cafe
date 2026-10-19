<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once "../../config/database.php";

$error = "";

if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama']);
    $profesi = trim($_POST['profesi']);
    $isi_testimoni = trim($_POST['isi_testimoni']);
    $status = $_POST['status'];

    $foto_baru = "";

    // Validasi foto jika diunggah
    if (
        isset($_FILES['foto']) &&
        $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $error = "Foto gagal diunggah.";
        } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $error = "Ukuran foto maksimal 2 MB.";
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['foto']['tmp_name']);

            $jenis_foto = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (!isset($jenis_foto[$mime])) {
                $error = "Foto harus berformat JPG, PNG, atau WEBP.";
            } else {
                $folder = __DIR__ . "/../../uploads/testimoni/";

                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }

                $foto_baru = uniqid('testimoni_', true)
                    . '.' . $jenis_foto[$mime];

                if (!move_uploaded_file(
                    $_FILES['foto']['tmp_name'],
                    $folder . $foto_baru
                )) {
                    $error = "Foto gagal disimpan.";
                }
            }
        }
    }

    if ($error === "") {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO testimoni
            (nama, profesi, isi_testimoni, foto_profil, status)
            VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $nama,
            $profesi,
            $isi_testimoni,
            $foto_baru,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: testimoni.php");
            exit;
        } else {
            $error = "Data gagal disimpan: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Testimoni - Admin Monstera Eatery</title>

    <link rel="stylesheet" href="../../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">

    <style>
        body.form-page-body {
            background: #f4f6f5;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        .form-card-wrapper {
            background: #fff;
            border: 1px solid #e0e8e4;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
            box-sizing: border-box;
        }

        .testimoni-form-group {
            margin-bottom: 20px;
        }

        .testimoni-form-group label {
            display: block;
            margin-bottom: 6px;
            color: #1d3b2f;
            font-weight: 600;
        }

        .testimoni-form-group input:not([type="file"]),
        .testimoni-form-group textarea,
        .testimoni-form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font: inherit;
        }

        .testimoni-form-group input[type="file"] {
            width: 100%;
            max-width: 100%;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .btn-simpan-testimoni {
            background: #1d3b2f;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-batal-testimoni {
            color: #666;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 16px;
        }

        .form-error {
            padding: 12px 15px;
            border-radius: 8px;
            background: #fff0ee;
            color: #a52e25;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            body.form-page-body {
                padding: 20px 12px;
                align-items: flex-start;
            }

            .form-card-wrapper {
                padding: 24px 18px;
            }
        }
    </style>
</head>

<body class="form-page-body">
    <div class="form-card-wrapper">

        <div style="margin-bottom: 25px;">
            <a href="testimoni.php"
               style="color:#6d7e77;text-decoration:none;font-weight:600;font-size:.9rem;">
                &larr; Kembali ke Kelola Testimoni
            </a>

            <h2 style="color:#1d3b2f;margin:10px 0 5px;font-size:1.8rem;font-weight:800;">
                Tambah Testimoni
            </h2>

            <p style="color:#6d7e77;margin:0;font-size:.9rem;">
                Masukkan ulasan pelanggan yang akan ditampilkan di beranda.
            </p>
        </div>

        <?php if ($error !== ""): ?>
            <div class="form-error">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <div class="testimoni-form-group">
                <label for="nama">Nama Pelanggan</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Contoh: Samantha K."
                    value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="testimoni-form-group">
                <label for="profesi">Profesi / Keterangan</label>
                <input
                    type="text"
                    id="profesi"
                    name="profesi"
                    placeholder="Contoh: Food Critic"
                    value="<?= htmlspecialchars($_POST['profesi'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="testimoni-form-group">
                <label for="isi_testimoni">Isi Testimoni</label>
                <textarea
                    id="isi_testimoni"
                    name="isi_testimoni"
                    rows="5"
                    placeholder="Tuliskan ulasan pelanggan..."
                    required
                ><?= htmlspecialchars($_POST['isi_testimoni'] ?? ''); ?></textarea>
            </div>

            <div class="testimoni-form-group">
                <label for="foto">Foto Profil (Opsional)</label>
                <input
                    type="file"
                    id="foto"
                    name="foto"
                    accept=".jpg,.jpeg,.png,.webp"
                >
                <small style="display:block;color:#6d7e77;margin-top:6px;">
                    Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                </small>
            </div>

            <div class="testimoni-form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="aktif"
                        <?= ($_POST['status'] ?? 'aktif') === 'aktif' ? 'selected' : ''; ?>>
                        Aktif — tampil di beranda
                    </option>
                    <option value="nonaktif"
                        <?= ($_POST['status'] ?? '') === 'nonaktif' ? 'selected' : ''; ?>>
                        Nonaktif — tidak tampil di beranda
                    </option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" name="simpan" class="btn-simpan-testimoni">
                    Simpan Testimoni
                </button>

                <a href="testimoni.php" class="btn-batal-testimoni">
                    Batal
                </a>
            </div>

        </form>
    </div>
</body>
</html>