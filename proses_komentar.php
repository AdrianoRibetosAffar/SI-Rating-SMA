<?php
include 'config/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $komentar = trim($_POST['komentar'] ?? '');

    // Validasi input
    if (empty($komentar)) {
        $_SESSION['error'] = 'Komentar tidak boleh kosong!';
        header('Location: school.php');
        exit;
    }

    if (strlen($komentar) < 10) {
        $_SESSION['error'] = 'Komentar minimal 10 karakter!';
        header('Location: school.php');
        exit;
    }

    if (strlen($komentar) > 500) {
        $_SESSION['error'] = 'Komentar maksimal 500 karakter!';
        header('Location: school.php');
        exit;
    }

    // Insert komentar ke database
    $stmt = mysqli_prepare($conn, "INSERT INTO komentar (komentar) VALUES (?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $komentar);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($result) {
            $_SESSION['success'] = 'Komentar berhasil ditambahkan! Terima kasih atas partisipasinya.';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan komentar. Silakan coba lagi.';
        }
    } else {
        $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    }

    header('Location: school.php');
    exit;

} else {
    header('Location: index.php');
    exit;
}
?>