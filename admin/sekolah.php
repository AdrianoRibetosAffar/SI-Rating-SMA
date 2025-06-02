<?php
session_start();
include '../config/conn.php';
$no = 1;
$sql = "SELECT * FROM sekolah ORDER BY nama_sekolah ASC";
$sekolah = mysqli_query($conn, $sql);

// Hitung total sekolah
$sql_count = "SELECT COUNT(*) as total FROM sekolah";
$result_count = mysqli_query($conn, $sql_count);
$total_sekolah = mysqli_fetch_assoc($result_count)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Kelola Sekolah</title>
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
            <h2><i class="fas fa-school text-primary"></i> Kelola Data SMA</h2>
            <p class="text-muted mb-0">Mengelola data sekolah menengah atas di Jayapura</p>
        </div>
        <div class="badge bg-primary fs-6 p-3">
            <i class="fas fa-building"></i> Total: <?= $total_sekolah ?> Sekolah
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down" data-aos-duration="800">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down" data-aos-duration="800">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Action Button -->
    <div class="mb-4" data-aos="fade-up" data-aos-duration="1000">
        <a href="tambah-sekolah.php" class="btn btn-primary btn-lg shadow">
            <i class="fas fa-plus"></i> Tambah Data Sekolah
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow-lg border-0" data-aos="fade-up" data-aos-duration="1200">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-table"></i> Daftar Sekolah Menengah Atas
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (mysqli_num_rows($sekolah) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th><i class="fas fa-school"></i> Nama Sekolah</th>
                            <th><i class="fas fa-map-marker-alt"></i> Alamat</th>
                            <th class="text-center"><i class="fas fa-medal"></i> Akreditasi</th>
                            <th><i class="fas fa-trophy"></i> Prestasi</th>
                            <th class="text-center"><i class="fas fa-image"></i> Lambang</th>
                            <th class="text-center" style="width: 180px;"><i class="fas fa-cogs"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = mysqli_fetch_assoc($sekolah)) : ?>
                        <tr>
                            <td class="text-center align-middle">
                                <span class="badge bg-secondary"><?= $no++ ?></span>
                            </td>
                            <td class="align-middle">
                                <strong><?= htmlspecialchars($data['nama_sekolah']) ?></strong>
                            </td>
                            <td class="align-middle">
                                <small class="text-muted">
                                    <i class="fas fa-map-pin"></i> 
                                    <?= htmlspecialchars($data['alamat']) ?>
                                </small>
                            </td>
                            <td class="text-center align-middle">
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
                                <span class="badge bg-<?= $badge_color ?> fs-6">
                                    <?= $data['akreditasi'] ?>
                                </span>
                            </td>
                            <td class="align-middle">
                                <?php if (!empty($data['prestasi'])): ?>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                          title="<?= htmlspecialchars($data['prestasi']) ?>">
                                        <?= htmlspecialchars($data['prestasi']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">
                                        <i class="fas fa-minus"></i> Tidak ada data
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle">
                                <?php if (!empty($data['lambang_sekolah']) && file_exists("../uploads/" . $data['lambang_sekolah'])): ?>
                                    <img src="../uploads/<?= $data['lambang_sekolah'] ?>" 
                                         class="img-thumbnail shadow-sm" 
                                         width="80" height="80"
                                         style="object-fit: cover;"
                                         alt="Lambang <?= htmlspecialchars($data['nama_sekolah']) ?>">
                                <?php else: ?>
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="edit-sekolah.php?sekolah_id=<?= $data['sekolah_id'] ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="hapus-sekolah.php?sekolah_id=<?= $data['sekolah_id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Hapus Data"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= htmlspecialchars($data['nama_sekolah']) ?>?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
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
                    <i class="fas fa-school fa-5x text-muted"></i>
                </div>
                <h4 class="text-muted">Belum Ada Data Sekolah</h4>
                <p class="text-muted mb-4">
                    Silakan tambahkan data sekolah untuk memulai pengelolaan data
                </p>
                <a href="tambah-sekolah.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Tambah Data Sekolah
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (mysqli_num_rows($sekolah) > 0): ?>
        <div class="card-footer bg-light">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Menampilkan <?= mysqli_num_rows($sekolah) ?> dari <?= $total_sekolah ?> total sekolah
            </small>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick Stats -->
    <?php if (mysqli_num_rows($sekolah) > 0): ?>
    <div class="row mt-4">
        <?php
        $akreditasi_stats = ['A', 'B', 'C', 'D'];
        $colors = ['success', 'primary', 'warning', 'danger'];
        $icons = ['medal', 'certificate', 'award', 'ribbon'];
        
        for ($i = 0; $i < count($akreditasi_stats); $i++) {
            $akreditasi = $akreditasi_stats[$i];
            $color = $colors[$i];
            $icon = $icons[$i];
            
            $sql_akreditasi = "SELECT COUNT(*) as total FROM sekolah WHERE akreditasi = '$akreditasi'";
            $result_akreditasi = mysqli_query($conn, $sql_akreditasi);
            $total_akreditasi = mysqli_fetch_assoc($result_akreditasi)['total'];
        ?>
        <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-duration="<?= 1000 + ($i * 200) ?>">
            <div class="card border-<?= $color ?> h-100">
                <div class="card-body text-center">
                    <i class="fas fa-<?= $icon ?> fa-2x text-<?= $color ?> mb-2"></i>
                    <h5 class="text-<?= $color ?>">Akreditasi <?= $akreditasi ?></h5>
                    <h3 class="fw-bold"><?= $total_akreditasi ?></h3>
                    <small class="text-muted">Sekolah</small>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php endif; ?>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-group .btn {
    margin: 0 2px;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 2px 0;
    }
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