<?php
session_start();
include('../config/conn.php');

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID komentar tidak valid.";
    header("Location: komentar.php");
    exit();
}

$id = intval($_GET['id']);

// Cek apakah komentar dengan ID tersebut ada
$check_query = mysqli_query($conn, "SELECT id FROM komentar WHERE id = $id");

if (mysqli_num_rows($check_query) > 0) {
    // Hapus komentar
    $delete_query = mysqli_query($conn, "DELETE FROM komentar WHERE id = $id");
    
    if ($delete_query) {
        $_SESSION['success'] = "Komentar berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus komentar. Error: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Komentar tidak ditemukan.";
}

// Redirect kembali ke halaman komentar
header("Location: komentar.php");
exit();
?>