<?php
session_start();
include '../config/conn.php';
$no = 1;
$sql = "SELECT * FROM komentar ORDER BY tanggal DESC";
$komentar = mysqli_query($conn, $sql);

// Hitung total komentar
$sql_count = "SELECT COUNT(*) as total FROM komentar";
$result_count = mysqli_query($conn, $sql_count);
$total_komentar = mysqli_fetch_assoc($result_count)['total'];

// Hitung komentar hari ini
$sql_today = "SELECT COUNT(*) as today FROM komentar WHERE DATE(tanggal) = CURDATE()";
$result_today = mysqli_query($conn, $sql_today);
$komentar_today = mysqli_fetch_assoc($result_today)['today'];

// Hitung komentar minggu ini
$sql_week = "SELECT COUNT(*) as week FROM komentar WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)";
$result_week = mysqli_query($conn, $sql_week);
$komentar_week = mysqli_fetch_assoc($result_week)['week'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Kelola Komentar</title>
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
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right" data-aos-duration="1000">
        <div>
            <h2><i class="fas fa-comments text-success"></i> Kelola Komentar</h2>
            <p class="text-muted mb-0">Mengelola komentar dan feedback dari pengunjung</p>
        </div>
        <div class="badge bg-success fs-6 p-3">
            <i class="fas fa-comment-dots"></i> Total: <?= $total_komentar ?> Komentar
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000">
            <div class="card bg-gradient-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Total Komentar</h6>
                            <h3 class="fw-bold mb-0"><?= $total_komentar ?></h3>
                        </div>
                        <div>
                            <i class="fas fa-comments fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="1200">
            <div class="card bg-gradient-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Hari Ini</h6>
                            <h3 class="fw-bold mb-0"><?= $komentar_today ?></h3>
                        </div>
                        <div>
                            <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="1400">
            <div class="card bg-gradient-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Minggu Ini</h6>
                            <h3 class="fw-bold mb-0"><?= $komentar_week ?></h3>
                        </div>
                        <div>
                            <i class="fas fa-calendar-week fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="800">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="800">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Comments Table Card -->
    <div class="card shadow-lg border-0" data-aos="fade-up" data-aos-duration="1200">
        <div class="card-header bg-gradient-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Daftar Komentar Pengunjung
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (mysqli_num_rows($komentar) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">
                                <i class="fas fa-hashtag"></i> No
                            </th>
                            <th style="width: 50%;">
                                <i class="fas fa-comment"></i> Komentar
                            </th>
                            <th class="text-center" style="width: 200px;">
                                <i class="fas fa-clock"></i> Tanggal & Waktu
                            </th>
                            <th class="text-center" style="width: 120px;">
                                <i class="fas fa-cogs"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = mysqli_fetch_assoc($komentar)) : ?>
                        <tr>
                            <td class="text-center align-middle">
                                <span class="badge bg-secondary"><?= $no++ ?></span>
                            </td>
                            <td class="align-middle">
                                <div class="comment-content">
                                    <div class="bg-light p-3 rounded border-start border-success border-3">
                                        <i class="fas fa-quote-left text-success me-2"></i>
                                        <span class="fw-normal"><?= htmlspecialchars($data['komentar']) ?></span>
                                        <i class="fas fa-quote-right text-success ms-2"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="date-info">
                                    <div class="fw-bold text-primary">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?= date('d M Y', strtotime($data['tanggal'])) ?>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i>
                                        <?= date('H:i', strtotime($data['tanggal'])) ?> WIT
                                    </small>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <a href="hapus-komentar.php?id=<?= $data['id'] ?>" 
                                   class="btn btn-danger btn-sm shadow-sm"
                                   title="Hapus Komentar"
                                   onclick="return confirm('⚠️ Apakah Anda yakin ingin menghapus komentar ini?\n\nKomentar: <?= addslashes(htmlspecialchars($data['komentar'])) ?>')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-comment-slash fa-5x text-muted"></i>
                </div>
                <h4 class="text-muted">Belum Ada Komentar</h4>
                <p class="text-muted mb-4">
                    Komentar dari pengunjung akan ditampilkan di sini
                </p>
                <div class="alert alert-info d-inline-block">
                    <i class="fas fa-info-circle"></i>
                    Tunggu pengunjung memberikan komentar pada website Anda
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (mysqli_num_rows($komentar) > 0): ?>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Menampilkan <?= mysqli_num_rows($komentar) ?> komentar (diurutkan dari yang terbaru)
                </small>
                <small class="text-muted">
                    <i class="fas fa-trash-alt"></i> 
                    Klik tombol merah untuk menghapus komentar
                </small>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Recent Activity -->
    <?php if (mysqli_num_rows($komentar) > 0): ?>
    <div class="row mt-4">
        <div class="col-md-12" data-aos="fade-up" data-aos-duration="1000">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line"></i> Aktivitas Komentar Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                                <h5><?= $komentar_today ?></h5>
                                <small class="text-muted">Hari Ini</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="fas fa-calendar-week fa-2x text-success mb-2"></i>
                                <h5><?= $komentar_week ?></h5>
                                <small class="text-muted">Minggu Ini</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="fas fa-comments fa-2x text-info mb-2"></i>
                                <h5><?= $total_komentar ?></h5>
                                <small class="text-muted">Total Semua</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="fas fa-chart-bar fa-2x text-warning mb-2"></i>
                                <h5><?= $total_komentar > 0 ? round(($komentar_week / $total_komentar) * 100, 1) : 0 ?>%</h5>
                                <small class="text-muted">Aktivitas Minggu Ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.comment-content {
    max-width: 100%;
}

.comment-content .bg-light {
    transition: all 0.3s ease;
}

.comment-content .bg-light:hover {
    background-color: #e8f5e8 !important;
    transform: translateX(5px);
}

.date-info {
    min-width: 120px;
}

.btn-danger:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

@media (max-width: 768px) {
    .comment-content .bg-light {
        margin-bottom: 10px;
    }
    
    .date-info {
        min-width: auto;
    }
}

.opacity-75 {
    opacity: 0.75;
}

.border-3 {
    border-width: 3px !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });
</script>
</body>
</html>