<?php
include '../config/conn.php';

// Ambil ID dari URL
if (isset($_GET['sekolah_id'])) {
    $id = $_GET['sekolah_id'];
    
    // Query untuk mengambil data sekolah berdasarkan ID
    $query = "SELECT * FROM sekolah WHERE sekolah_id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        echo "Data tidak ditemukan!";
        exit();
    }
} else {
    echo "ID tidak valid!";
    exit();
}

// Proses update data
if (isset($_POST['update'])) {
    $nama_sekolah = $_POST['nama_sekolah'];
    $alamat = $_POST['alamat'];
    $akreditasi = $_POST['akreditasi'];
    $prestasi = $_POST['prestasi'];
    
    // Cek apakah ada file gambar baru yang diupload
    if ($_FILES['lambang_sekolah']['name'] != "") {
        $lambang_sekolah = $_FILES['lambang_sekolah']['name'];
        $tmp_name = $_FILES['lambang_sekolah']['tmp_name'];
        $folder = "../uploads/";
        
        // Hapus gambar lama jika ada
        if (file_exists($folder . $data['lambang_sekolah'])) {
            unlink($folder . $data['lambang_sekolah']);
        }
        
        // Upload gambar baru
        if (move_uploaded_file($tmp_name, $folder . $lambang_sekolah)) {
            $query = "UPDATE sekolah SET 
                      nama_sekolah = '$nama_sekolah', 
                      alamat = '$alamat', 
                      akreditasi = '$akreditasi', 
                      prestasi = '$prestasi', 
                      lambang_sekolah = '$lambang_sekolah' 
                      WHERE sekolah_id = '$id'";
        } else {
            echo "Gagal mengupload gambar baru.";
            exit();
        }
    } else {
        // Jika tidak ada gambar baru, gunakan gambar lama
        $query = "UPDATE sekolah SET 
                  nama_sekolah = '$nama_sekolah', 
                  alamat = '$alamat', 
                  akreditasi = '$akreditasi', 
                  prestasi = '$prestasi' 
                  WHERE sekolah_id = '$id'";
    }
    
    if (mysqli_query($conn, $query)) {
        // Redirect ke halaman sekolah setelah berhasil update
        header("Location: sekolah.php");
        exit();
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
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
    <h2 class="mb-4 text-center">Edit Data SMA</h2>
    
    <!-- Form Edit -->
    <form method="POST" action="" enctype="multipart/form-data" class="mb-5 border p-3 bg-white rounded">
        <div class="mb-3">
            <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" value="<?php echo htmlspecialchars($data['nama_sekolah']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="akreditasi" class="form-label">Akreditasi</label>
            <select class="form-select" id="akreditasi" name="akreditasi" required>
                <option value="" disabled>Pilih Akreditasi</option>
                <option value="A" <?php echo ($data['akreditasi'] == 'A') ? 'selected' : ''; ?>>A</option>
                <option value="B" <?php echo ($data['akreditasi'] == 'B') ? 'selected' : ''; ?>>B</option>
                <option value="C" <?php echo ($data['akreditasi'] == 'C') ? 'selected' : ''; ?>>C</option>
                <option value="D" <?php echo ($data['akreditasi'] == 'D') ? 'selected' : ''; ?>>D</option>
            </select>        
        </div>
        <div class="mb-3">
            <label for="prestasi" class="form-label">Prestasi</label>
            <textarea class="form-control" id="prestasi" name="prestasi" rows="5"><?php echo htmlspecialchars($data['prestasi']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="lambang_sekolah" class="form-label">Lambang Sekolah</label>
            <input type="file" class="form-control" id="lambang_sekolah" name="lambang_sekolah" accept="image/*">
            <?php if (!empty($data['lambang_sekolah'])): ?>
                <small class="form-text text-muted">Gambar saat ini: <?php echo htmlspecialchars($data['lambang_sekolah']); ?></small>
                <br>
                <img src="../uploads/<?php echo htmlspecialchars($data['lambang_sekolah']); ?>" alt="Lambang Sekolah" style="max-width: 100px; margin-top: 10px;">
            <?php endif; ?>
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>
        <button type="submit" name="update" class="btn btn-outline-success">Simpan</button>
        <a href="sekolah.php" class="btn btn-outline-danger">Batal</a>
    </form>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>