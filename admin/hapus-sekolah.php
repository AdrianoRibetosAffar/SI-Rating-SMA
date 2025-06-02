<?php
session_start();
include('../config/conn.php');

if (!isset($_GET['sekolah_id']) || !is_numeric($_GET['sekolah_id'])) {
    $_SESSION['error'] = "ID sekolah tidak valid.";
    header("Location: tambah-sekolah.php");
    exit();
}

$id = intval($_GET['sekolah_id']);

// Mulai transaction untuk memastikan konsistensi data
mysqli_autocommit($conn, false);

try {
    // Ambil data sekolah
    $query = mysqli_query($conn, "SELECT lambang_sekolah FROM sekolah WHERE sekolah_id = $id");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        // Hapus semua data rating yang terkait dengan sekolah ini terlebih dahulu
        $delete_rating = mysqli_query($conn, "DELETE FROM rating WHERE sekolah_id = $id");
        
        if (!$delete_rating) {
            throw new Exception("Gagal menghapus data rating terkait.");
        }

        // Hapus file gambar jika ada
        if (!empty($row['lambang_sekolah'])) {
            $gambar_path = '../uploads/' . $row['lambang_sekolah'];
            if (file_exists($gambar_path)) {
                unlink($gambar_path);
            }
        }

        // Hapus data sekolah
        $delete_sekolah = mysqli_query($conn, "DELETE FROM sekolah WHERE sekolah_id = $id");
        
        if (!$delete_sekolah) {
            throw new Exception("Gagal menghapus data sekolah.");
        }

        // Commit transaction jika semua berhasil
        mysqli_commit($conn);
        $_SESSION['success'] = "Data sekolah dan semua data terkait berhasil dihapus.";
        
    } else {
        throw new Exception("Data sekolah tidak ditemukan.");
    }

} catch (Exception $e) {
    // Rollback transaction jika ada error
    mysqli_rollback($conn);
    $_SESSION['error'] = $e->getMessage();
}

// Kembalikan autocommit ke true
mysqli_autocommit($conn, true);

header("Location: sekolah.php");
exit();
?>