<?php
// filepath: c:\xampp\htdocs\SI-Rating-SMA\index.php

// Data sekolah sementara
session_start();
if (!isset($_SESSION['sekolah'])) {
    $_SESSION['sekolah'] = [];
}
$sekolah = $_SESSION['sekolah'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .fixed-size {
            width: 100px;
            height: 100px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Data Sekolah</h1>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Sekolah</th>
                    <th>Alamat</th>
                    <th>Akreditasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($sekolah) > 0): ?>
                    <?php foreach ($sekolah as $index => $data): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <?php if (!empty($data['gambar'])): ?>
                                    <img src="<?= $data['gambar'] ?>" alt="Gambar Sekolah" class="fixed-size">
                                <?php else: ?>
                                    Tidak ada gambar
                                <?php endif; ?>
                            <td><?= $data['nama'] ?></td>
                            <td><?= $data['alamat'] ?? '-' ?></td>
                            
                            </td>
                            <td><?= $data['akreditasi'] ?? '-' ?></td>
                            <td>
                                <a href="proses.php?action=edit&id=<?= $index ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="proses.php?action=delete&id=<?= $index ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2 class="mt-5">Tambah/Edit Data</h2>
        <form action="proses.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Sekolah</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" name="alamat" id="alamat" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Sekolah</label>
                <input type="file" name="gambar" id="gambar" class="form-control">
            </div>
            <div class="mb-3">
                <label for="akreditasi" class="form-label">Akreditasi</label>
                <select name="akreditasi" id="akreditasi" class="form-select">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>