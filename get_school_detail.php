<?php
try {
    session_start();
    include 'config/conn.php';
    
    // Validasi input
    if (!isset($_GET['sekolah_id']) || empty($_GET['sekolah_id'])) {
        throw new Exception('ID sekolah tidak ditemukan');
    }
    
    $school_id = intval($_GET['sekolah_id']);
    
    if ($school_id <= 0) {
        throw new Exception('ID sekolah tidak valid');
    }
    
    // Cek koneksi database
    if (!$conn) {
        throw new Exception('Koneksi database gagal');
    }
    
    // Query untuk mengambil detail sekolah dengan statistik rating
    $sql = "SELECT s.sekolah_id, s.nama_sekolah, s.alamat, s.akreditasi, s.prestasi, s.lambang_sekolah,
            COUNT(r.rating) as total_rating,
            COALESCE(AVG(r.rating), 0) as avg_rating,
            SUM(CASE WHEN r.rating = 5 THEN 1 ELSE 0 END) as rating_5,
            SUM(CASE WHEN r.rating = 4 THEN 1 ELSE 0 END) as rating_4,
            SUM(CASE WHEN r.rating = 3 THEN 1 ELSE 0 END) as rating_3,
            SUM(CASE WHEN r.rating = 2 THEN 1 ELSE 0 END) as rating_2,
            SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as rating_1
            FROM sekolah AS s
            LEFT JOIN rating AS r ON s.sekolah_id = r.sekolah_id
            WHERE s.sekolah_id = ?
            GROUP BY s.sekolah_id, s.nama_sekolah, s.alamat, s.akreditasi, s.prestasi, s.lambang_sekolah";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        throw new Exception('Gagal menyiapkan query: ' . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $school_id);
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        throw new Exception('Gagal mengeksekusi query: ' . mysqli_stmt_error($stmt));
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $school = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if (!$school) {
        throw new Exception('Data sekolah tidak ditemukan');
    }
    
    // Output HTML untuk modal
    ?>
    <div class="row">
        <div class="col-md-4 text-center mb-3">
            <img src="uploads/<?= htmlspecialchars($school['lambang_sekolah'] ?: 'default-school.png') ?>" 
                 class="img-fluid rounded shadow" 
                 alt="<?= htmlspecialchars($school['nama_sekolah']) ?>"
                 style="max-height: 200px; object-fit: cover;"
                 onerror="this.src='uploads/default-school.png'">
        </div>
        <div class="col-md-8">
            <h4 class="fw-bold text-primary mb-3">
                <?= htmlspecialchars($school['nama_sekolah']) ?>
            </h4>
            
            <div class="row mb-3">
                <div class="col-sm-3 fw-bold">Alamat:</div>
                <div class="col-sm-9"><?= htmlspecialchars($school['alamat'] ?: 'Tidak tersedia') ?></div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-3 fw-bold">Akreditasi:</div>
                <div class="col-sm-9">
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-certificate me-1"></i>
                        <?= htmlspecialchars($school['akreditasi'] ?: 'Belum tersedia') ?>
                    </span>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-3 fw-bold">Prestasi:</div>
                <div class="col-sm-9">
                    <?php if (!empty($school['prestasi'])): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            <?= nl2br(htmlspecialchars($school['prestasi'])) ?>
                        </div>
                    <?php else: ?>
                        <span class="text-muted">Belum ada data prestasi</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <hr class="my-4">
    
    <!-- Rating Statistics -->
    <div class="row">
        <div class="col-md-6">
            <h5 class="mb-3">
                <i class="fas fa-star text-warning me-2"></i>Statistik Rating
            </h5>
            
            <div class="d-flex align-items-center mb-3">
                <div class="me-3">
                    <h2 class="mb-0 text-primary fw-bold">
                        <?= number_format($school['avg_rating'], 1) ?>
                    </h2>
                    <div class="stars-display">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= round($school['avg_rating']) ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                    </div>
                </div>
                <div>
                    <p class="mb-0 text-muted">
                        <strong><?= $school['total_rating'] ?></strong> ulasan
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <h5 class="mb-3">
                <i class="fas fa-chart-bar me-2"></i>Distribusi Rating
            </h5>
            
            <?php if ($school['total_rating'] > 0): ?>
                <?php for($i = 5; $i >= 1; $i--): ?>
                    <?php 
                    $count = $school["rating_$i"];
                    $percentage = ($school['total_rating'] > 0) ? ($count / $school['total_rating']) * 100 : 0;
                    ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2" style="min-width: 60px;">
                            <?= $i ?> <i class="fas fa-star text-warning"></i>
                        </span>
                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: <?= $percentage ?>%"></div>
                        </div>
                        <span class="text-muted" style="min-width: 40px;">
                            <?= $count ?>
                        </span>
                    </div>
                <?php endfor; ?>
            <?php else: ?>
                <p class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Belum ada rating untuk sekolah ini
                </p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
    
} catch (Exception $e) {
    // Tampilkan error dalam format HTML
    ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Error:</strong> <?= htmlspecialchars($e->getMessage()) ?>
    </div>
    <?php
}
?>