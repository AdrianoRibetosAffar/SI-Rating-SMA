<?php
include '../config/conn.php';

if (isset($_POST['tambah'])) {
    $nama_sekolah = $_POST['nama_sekolah'];
    $alamat = $_POST['alamat'];
    $akreditasi = $_POST['akreditasi'];
    $prestasi = $_POST['prestasi'];

    $lambang_sekolah = $_FILES['lambang_sekolah']['name'];
    $tmp_name = $_FILES['lambang_sekolah']['tmp_name'];
    $folder = "../uploads/";

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($tmp_name, $folder . $lambang_sekolah)) {
        // Query insert ke database
        $query = "INSERT INTO sekolah (nama_sekolah, alamat, akreditasi, prestasi, lambang_sekolah) 
                  VALUES ('$nama_sekolah', '$alamat', '$akreditasi', '$prestasi', '$lambang_sekolah')";
        
        if (mysqli_query($conn, $query)) {
            // Redirect ke halaman sekolah setelah berhasil tambah
            header("Location: sekolah.php");
            exit();
        } else {
            echo "Gagal menambahkan data: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-white text-center py-3">Admin Kelompok VII</h4>
    <a href="dashboard.php">Dashboard</a>    
    <a href="sekolah.php">Kelola Sekolah</a>
    <a href="komentar.php">Kelola Komentar</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h2 class="mb-4 text-center">Tambah Data SMA</h2>
    
    <!-- Form Tambah -->
     <form method="POST" action="" enctype="multipart/form-data" class="mb-5 border p-3 bg-white rounded">
        <div class="mb-3">
            <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" required>
        </div>
        <div class="mb-3">
            <label for="akreditasi" class="form-label">Akreditasi</label>
            <select class="form-select" id="akreditasi" name="akreditasi" required>
                <option value="" disabled selected>Pilih Akreditasi</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>        
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Prestasi</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" name="prestasi" rows="5"></textarea>
        </div>
        <div class="mb-3">
            <label for="lambang_sekolah" class="form-label">Lambang Sekolah</label>
            <input type="file" class="form-control" id="lambang_sekolah" name="lambang_sekolah" accept="image/*" required>
        </div>
        <button type="submit" name="tambah" class="btn btn-primary">Add Sekolah</button>
        <a href="sekolah.php" class="btn btn-outline-danger">Batal</a>
     </form>
</div>



<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
        AOS.init();
</script>
</body>
</html>
