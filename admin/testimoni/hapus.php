
<?php
require_once "../../config/database.php";

// Samakan pemeriksaan login dengan modul admin yang sudah ada.

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: testimoni.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: testimoni.php");
    exit;
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT foto_profil FROM testimoni WHERE id_testimoni = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if ($data) {
    $hapus = mysqli_prepare(
        $conn,
        "DELETE FROM testimoni WHERE id_testimoni = ?"
    );
    mysqli_stmt_bind_param($hapus, "i", $id);

    if (mysqli_stmt_execute($hapus) && !empty($data["foto_profil"])) {
        $path = "../../uploads/testimoni/"
            . basename($data["foto_profil"]);

        if (is_file($path)) {
            unlink($path);
        }
    }
}

header("Location: testimoni.php");
exit;
