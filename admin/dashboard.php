<?php
session_start();
include '../config/conn.php';

// Hitung jumlah sekolah
$sql_count_sekolah = "SELECT COUNT(*) as total_sekolah FROM sekolah";
$result_count_sekolah = mysqli_query($conn, $sql_count_sekolah);
$total_sekolah = mysqli_fetch_assoc($result_count_sekolah)['total_sekolah'];

// Hitung jumlah komentar
$sql_count_komentar = "SELECT COUNT(*) as total_komentar FROM komentar";
$result_count_komentar = mysqli_query($conn, $sql_count_komentar);
$total_komentar = mysqli_fetch_assoc($result_count_komentar)['total_komentar'];

// Data sekolah untuk tabel
$no = 1;
$sql = "SELECT * FROM sekolah ORDER BY nama_sekolah ASC";
$sekolah = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="img/si-logo.png" rel="icon" id="icon-header">
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

<!-- Main Content -->
<div class="content">
    <h2 data-aos="fade-right" data-aos-duration="1000">Selamat Datang di Dashboard Admin</h2>
    <h4 data-aos="fade-right" data-aos-duration="1000">Dashboard ini digunakan untuk mengelola data Sekolah</h4>
    
    <div class="row my-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total SMA</h5>
                            <p class="card-text" style="font-size: 30px; font-weight: bold;">
                                <?= $total_sekolah ?>
                            </p>
                        </div>
                        <div>
                            <i class="fas fa-school" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="1200">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Komentar</h5>
                            <p class="card-text" style="font-size: 30px; font-weight: bold;">
                                <?= $total_komentar ?>
                            </p>
                        </div>
                        <div>
                            <i class="fas fa-comments" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="1400">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Akreditasi A</h5>
                            <p class="card-text" style="font-size: 30px; font-weight: bold;">
                                <?php
                                $sql_akreditasi_a = "SELECT COUNT(*) as total_a FROM sekolah WHERE akreditasi = 'A'";
                                $result_akreditasi_a = mysqli_query($conn, $sql_akreditasi_a);
                                $total_akreditasi_a = mysqli_fetch_assoc($result_akreditasi_a)['total_a'];
                                echo $total_akreditasi_a;
                                ?>
                            </p>
                        </div>
                        <div>
                            <i class="fas fa-medal" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Akreditasi -->
    <div class="row my-4">
        <div class="col-md-12" data-aos="fade-up" data-aos-duration="1000">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5><i class="fas fa-chart-bar"></i> Statistik Akreditasi Sekolah</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <?php
                        $akreditasi_stats = ['A', 'B', 'C', 'D'];
                        $colors = ['success', 'primary', 'warning', 'danger'];
                        
                        for ($i = 0; $i < count($akreditasi_stats); $i++) {
                            $akreditasi = $akreditasi_stats[$i];
                            $color = $colors[$i];
                            
                            $sql_akreditasi = "SELECT COUNT(*) as total FROM sekolah WHERE akreditasi = '$akreditasi'";
                            $result_akreditasi = mysqli_query($conn, $sql_akreditasi);
                            $total_akreditasi = mysqli_fetch_assoc($result_akreditasi)['total'];
                        ?>
                        <div class="col-md-3">
                            <div class="badge bg-<?= $color ?> p-3 w-100">
                                <h4>Akreditasi <?= $akreditasi ?></h4>
                                <h2><?= $total_akreditasi ?> Sekolah</h2>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5" data-aos="fade-right" data-aos-duration="1000">
        <i class="fas fa-list"></i> Data SMA Di Jayapura
    </h4>
    
    <div class="card shadow" data-aos="fade-up" data-aos-duration="1000">
        <div class="card-body">
            <?php if (mysqli_num_rows($sekolah) > 0): ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Sekolah</th>
                        <th>Alamat</th>
                        <th>Akreditasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data = mysqli_fetch_assoc($sekolah)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($data['nama_sekolah']) ?></td>
                        <td><?= htmlspecialchars($data['alamat']) ?></td>
                        <td>
                            <?php
                            $badge_color = '';
                            switch($data['akreditasi']) {
                                case 'A': $badge_color = 'success'; break;
                                case 'B': $badge_color = 'primary'; break;
                                case 'C': $badge_color = 'warning'; break;
                                case 'D': $badge_color = 'danger'; break;
                                default: $badge_color = 'secondary';
                            }
                            ?>
                            <span class="badge bg-<?= $badge_color ?>"><?= $data['akreditasi'] ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada data sekolah</h5>
                <p class="text-muted">Silakan tambahkan data sekolah terlebih dahulu</p>
                <a href="sekolah.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data Sekolah
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>