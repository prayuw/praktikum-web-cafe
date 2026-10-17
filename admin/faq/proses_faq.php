<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../config/database.php";

if (isset($_POST['update'])) {
    $id_faq = intval($_POST['id_faq']);
    $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
    $jawaban = mysqli_real_escape_string($conn, $_POST['jawaban']);

    mysqli_query($conn, "UPDATE faq SET pertanyaan = '$pertanyaan', jawaban = '$jawaban' WHERE id_faq = $id_faq");
    header("Location: faq.php");
    exit;
} else {
    header("Location: faq.php");
    exit;
}
?>