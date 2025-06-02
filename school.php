<?php
session_start();
include 'config/conn.php';

// Query untuk mengambil data sekolah dengan statistik rating dan ranking
$sql = "SELECT s.sekolah_id, s.nama_sekolah, s.alamat, s.akreditasi, s.prestasi, s.lambang_sekolah,
        COUNT(r.rating) as total_rating,
        COALESCE(AVG(r.rating), 0) as avg_rating,
        COALESCE((AVG(r.rating) * 2) + (LOG10(COUNT(r.rating) + 1) * 3), 0) as ranking_score,
        SUM(CASE WHEN r.rating = 5 THEN 1 ELSE 0 END) as rating_5,
        SUM(CASE WHEN r.rating = 4 THEN 1 ELSE 0 END) as rating_4,
        SUM(CASE WHEN r.rating = 3 THEN 1 ELSE 0 END) as rating_3,
        SUM(CASE WHEN r.rating = 2 THEN 1 ELSE 0 END) as rating_2,
        SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as rating_1
        FROM sekolah AS s
        LEFT JOIN rating AS r ON s.sekolah_id = r.sekolah_id
        GROUP BY s.sekolah_id, s.nama_sekolah, s.alamat, s.akreditasi, s.prestasi, s.lambang_sekolah
        ORDER BY ranking_score DESC, avg_rating DESC, total_rating DESC";

$sekolah = mysqli_query($conn, $sql);

// Cek apakah ada data yang ditemukan
if (!$sekolah) {
    die("Query gagal: " . mysqli_error($conn));
}

// Ambil data komentar dari database
// Ganti $sekolah_id dengan ID sekolah yang sedang ditampilkan
$sekolah_id = $_GET['sekolah_id'] ?? 1;

// Ambil semua komentar, urutkan dari yang terbaru
$query = "SELECT * FROM komentar ORDER BY tanggal DESC";

$komentar = mysqli_query($conn, $query);
$komentar_list = [];
if ($komentar) {
    while ($row = mysqli_fetch_assoc($komentar)) {
        $komentar_list[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sistem Informasi - Rating SMA</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/si-logo.png" rel="icon" id="icon-header">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Libraries Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Library link AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .filter-sidebar {
            background: white;
            border-radius: 12px;
            position: sticky;
            top: 20px;
            height: fit-content;
            box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-card {
            background: rgb(255, 255, 255);
            border-radius: 12px;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.445), inset 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 260px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        .category-badge {
            background: #e5e7eb;
            color: #006aff;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
        }

        .stars-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .star-input {
            display: none;
        }

        .star-label {
            cursor: pointer;
            font-size: 1.2rem;
            color: #ddd;
            transition: all 0.2s ease;
            margin-right: 2px;
            position: relative;
        }

        .star-label:hover {
            color: #ffc107;
            transform: scale(1.1);
        }

        .star-label:hover ~ .star-label {
            color: #ffc107;
        }

        /* Enhanced hover effect for star rating */
        .stars-input:hover .star-label {
            color: #ddd;
        }

        .stars-input .star-label:hover,
        .stars-input .star-label:hover ~ .star-label {
            color: #ffc107 !important;
        }

        /* Submitting state */
        .stars-input.submitting .star-label {
            pointer-events: none;
            opacity: 0.7;
            cursor: not-allowed;
        }

        .star-label i.text-warning {
            color: #ffc107 !important;
        }

        .star-label i.text-muted {
            color: #ddd !important;
        }

        .rating-stats .stars-display i {
            font-size: 0.9rem;
            margin-right: 1px;
        }

        .ranking-badge {
            font-weight: bold;
            font-size: 1rem;
        }

        .rating-progress {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }

        .rating-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ffc107, #ff8c00);
            transition: width 0.5s ease;
        }

        .school-detail-modal .modal-dialog {
            max-width: 800px;
        }

        .alert-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            margin-top: 10px;
            margin-bottom: 0;
        }

        .rating-form .alert-sm {
            margin-top: 10px;
            margin-bottom: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .product-image {
                height: 200px;
            }
            
            .star-label {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary d-flex align-items-center">
                <img src="img/si-logo.png" class="me-2" style="height: 40px;">
                Kelompok VII
            </h2>           
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="school.php" class="nav-item nav-link active">School</a>
                <a href="about.html" class="nav-item nav-link">About</a>
                <a href="team.html" class="nav-item nav-link">Our Team</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Daftar SMA Di Jayapura</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">School</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Sekolah Start -->
    <section id="shop" class="shop">
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center" data-aos="fade-up" data-aos-duration="800">
                    <h6 class="section-title bg-white text-center text-primary px-3">Sekolah</h6>
                    <h1 class="mb-5">Ranking Sekolah</h1>
                </div>
                
                <div class="col-lg-12" data-aos="fade-up" data-aos-duration="800">
                    <div class="row g-4">
                        <?php 
                        $rank = 1;
                        // Reset pointer untuk iterasi ulang
                        mysqli_data_seek($sekolah, 0);
                        ?>
                        <?php while ($row = mysqli_fetch_assoc($sekolah)): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product-card shadow-sm position-relative h-100">
                                <!-- Ranking Badge -->
                                <div class="position-absolute top-0 start-0 m-3 z-index-10">
                                    <span class="badge ranking-badge bg-<?= $rank <= 3 ? 'warning text-dark' : 'secondary' ?> fs-6">
                                        #<?= $rank ?>
                                        <?php if($rank == 1): ?>
                                            <i class="fas fa-crown ms-1"></i>
                                        <?php elseif($rank == 2): ?>
                                            <i class="fas fa-medal ms-1"></i>
                                        <?php elseif($rank == 3): ?>
                                            <i class="fas fa-award ms-1"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                
                                <!-- School Image -->
                                <div class="position-relative">
                                    <img src="uploads/<?= htmlspecialchars($row['lambang_sekolah']) ?>" 
                                         class="product-image w-100" 
                                         alt="<?= htmlspecialchars($row['nama_sekolah']) ?>"
                                         onerror="this.src='uploads/default-school.png'">
                                    
                                    <!-- Overlay untuk ranking score -->
                                    <div class="position-absolute bottom-0 end-0 m-2">
                                        <span class="badge bg-dark bg-opacity-75 text-white small">
                                            Score: <?= number_format($row['ranking_score'], 1) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <!-- School Name -->
                                    <h5 class="mb-2 fw-bold"><?= htmlspecialchars($row['nama_sekolah']) ?></h5>
                                    
                                    <!-- Accreditation Badge -->
                                    <span class="category-badge mb-3 d-inline-block">
                                        <i class="fas fa-certificate me-1"></i>
                                        Akreditasi <?= htmlspecialchars($row['akreditasi']) ?>
                                    </span>
                                    
                                    <!-- Rating Statistics -->
                                    <div class="rating-stats mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="stars-display me-2">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= round($row['avg_rating']) ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="fw-bold text-dark">
                                                    <?= number_format($row['avg_rating'], 1) ?>
                                                </span>
                                            </div>
                                            <small class="text-muted">
                                                <?= $row['total_rating'] ?> ulasan
                                            </small>
                                        </div>                        
                                    </div>

                                    <!-- Rating Form (Direct star click submission) -->
                                    <div class="rating-form mb-3" id="rating-form-<?= $row['sekolah_id'] ?>">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fas fa-star-half-alt me-1"></i>Klik bintang untuk memberi rating:
                                        </small>
                                        
                                        <div class="stars-input mb-2" data-school-id="<?= $row['sekolah_id'] ?>">
                                            <?php for($i = 5; $i >= 1; $i--): ?>
                                                <input class="star-input d-none" 
                                                       id="star-<?= $i ?>-<?= $row['sekolah_id'] ?>" 
                                                       type="radio" 
                                                       name="rating_<?= $row['sekolah_id'] ?>" 
                                                       value="<?= $i ?>"/>
                                                <label class="star-label" 
                                                       for="star-<?= $i ?>-<?= $row['sekolah_id'] ?>"
                                                       data-rating="<?= $i ?>"
                                                       title="Berikan <?= $i ?> bintang">
                                                    <i class="fas fa-star text-muted"></i>
                                                </label>
                                            <?php endfor; ?>
                                        </div>
                                        
                                        <!-- Message container for feedback -->
                                        <div class="rating-message text-center" id="message-<?= $row['sekolah_id'] ?>"></div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <button class="btn btn-outline-primary btn-sm" 
                                                onclick="showSchoolDetail(<?= $row['sekolah_id'] ?>, '<?= htmlspecialchars($row['nama_sekolah']) ?>')">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $rank++; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Sekolah End -->

    <!-- School Detail Modal -->
    <div class="modal fade school-detail-modal" id="schoolDetailModal" tabindex="-1" aria-labelledby="schoolDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="schoolDetailModalLabel">
                        <i class="fas fa-school me-2"></i>Detail Sekolah
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="schoolDetailBody">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Komentar sekolah -->
<section class="bg-light">
    <div class="container my-5 py-5 text-body">
        <div class="text-center" data-aos="fade-up" data-aos-duration="800">
            <h6 class="section-title bg-white text-center text-primary px-3">Komentar dan Rating</h6>
            <h1 class="mb-5">Komentar Sekolah</h1>
        </div>

        <!-- Tampilkan Komentar yang Ada -->
        <?php if (!empty($komentar_list)): ?>
<div class="row d-flex justify-content-center mb-4">
    <div class="col-md-10 col-lg-12"> 
        <h4 class="mb-4">
            <i class="fas fa-comments me-2"></i>
            Komentar Pengguna (<?= count($komentar_list) ?>)
        </h4>

        <div class="row"> <!-- Tambahkan row baru di sini -->
            <?php foreach ($komentar_list as $komentar): ?>
                <div class="col-md-6 col-lg-4 mb-4"> <!-- Setiap komentar berada dalam 1 kolom -->
                    <div class="card shadow h-100" data-aos="fade-up" data-aos-duration="600">
                        <div class="card-body p-4">
                            <div class="d-flex flex-start">
                                <img class="rounded-circle shadow-1-strong me-3"
                                     src="uploads/user.jpg" alt="avatar" width="50" height="50" />
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-user me-1"></i>Pengguna Anonim
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('d M Y, H:i', strtotime($komentar['tanggal'])) ?>
                                        </small>
                                    </div>
                                    <p class="mb-0"><?= htmlspecialchars($komentar['komentar']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

        <?php else: ?>
            <div class="row d-flex justify-content-center mb-4">
                <div class="col-md-10 col-lg-8 col-xl-6">
                    <div class="alert alert-info text-center" data-aos="fade-up" data-aos-duration="600">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada komentar untuk sekolah ini. Jadilah yang pertama memberikan komentar!
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Komentar -->
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="d-flex flex-start w-100">
                            <img class="rounded-circle shadow-1-strong me-3"
                                src="uploads/anonim.png" alt="avatar" width="65" height="65" />
                            <div class="w-100">
                                <h5><i class="fas fa-comment-alt me-2"></i>Tambahkan Komentar</h5>
                                <form action="proses_komentar.php" method="POST">
                                    <div class="form-outline">
                                        <textarea class="form-control" name="komentar" id="textAreaExample" rows="4"
                                                  placeholder="Bagikan pengalaman Anda tentang sekolah-sekolah di Jayapura..."
                                                  required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Komentar akan ditampilkan setelah moderasi
                                        </small>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-1"></i>Kirim
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Footer Start -->
    <footer class="bg-dark text-center text-white">
        <!-- Grid container -->
        <div class="container p-4 pb-0">
            <!-- Section: Social media -->
            <section class="mb-4">
                <h2 class="fw-bold text-light">Contact Me</h2>
                <!-- WhatsApp -->
                <a class="btn btn-outline-light btn-floating m-1" href="#" role="button">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <!-- Instagram -->
                <a class="btn btn-outline-light btn-floating m-1" href="#" role="button">
                    <i class="fab fa-instagram"></i>
                </a>
            </section>
            <!-- Section: Social media -->
        </div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2025 Copyright:
            <a class="text-white" href="#">Kelompok VII Manajemen Proyek</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
<script>
// Enhanced JavaScript untuk school.php dengan error handling yang lebih baik
$(document).ready(function() {
    // Initialize AOS animation
    AOS.init();
    
    // Hide spinner when page loads
    $(window).on('load', function() {
        $('#spinner').removeClass('show');
    });
    
    // Function to show more info (external link)
    window.showMoreInfo = function(schoolName) {
        alert('Menampilkan informasi lebih lanjut untuk: ' + schoolName);
    };
    
    // Function to show school detail in modal
    window.showSchoolDetail = function(schoolId, schoolName) {
        $('#schoolDetailModalLabel').html('<i class="fas fa-school me-2"></i>' + schoolName);
        $('#schoolDetailModal').modal('show');
        
        // Show loading spinner
        $('#schoolDetailBody').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat detail sekolah...</p>
            </div>
        `);
        
        // Load school detail via AJAX
        $.ajax({
            url: 'get_school_detail.php',
            method: 'GET',
            data: { sekolah_id: schoolId },
            timeout: 10000,
            success: function(response) {
                $('#schoolDetailBody').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading school detail:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                $('#schoolDetailBody').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Gagal memuat detail sekolah. 
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="showSchoolDetail(${schoolId}, '${schoolName}')">
                            <i class="fas fa-redo me-1"></i>Coba Lagi
                        </button>
                    </div>
                `);
            }
        });
    };
    
    // Handle star rating selection dengan improved error handling
    $('.star-label').on('click', function(e) {
        e.preventDefault();
        
        const schoolId = $(this).closest('.stars-input').data('school-id');
        const rating = $(this).data('rating');
        const container = $(this).closest('.stars-input');
        const messageDiv = $(`#message-${schoolId}`);
        
        // Prevent multiple clicks during submission
        if (container.hasClass('submitting')) {
            console.log('Already submitting, ignoring click');
            return false;
        }
        
        // Validasi input
        if (!schoolId || schoolId <= 0) {
            console.error('Invalid school ID:', schoolId);
            messageDiv.html(`
                <div class="alert alert-danger alert-sm">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    ID sekolah tidak valid
                </div>
            `);
            return false;
        }
        
        if (!rating || rating < 1 || rating > 5) {
            console.error('Invalid rating:', rating);
            messageDiv.html(`
                <div class="alert alert-danger alert-sm">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Rating tidak valid
                </div>
            `);
            return false;
        }
        
        console.log('Submitting rating:', { schoolId, rating });
        container.addClass('submitting');
        
        // Update visual stars immediately
        container.find('.star-label i').removeClass('text-warning').addClass('text-muted');
        for(let i = 1; i <= rating; i++) {
            container.find(`.star-label[data-rating="${i}"] i`).removeClass('text-muted').addClass('text-warning');
        }
        
        // Show loading message
        messageDiv.html(`
            <div class="alert alert-info alert-sm">
                <i class="fas fa-spinner fa-spin me-1"></i>
                Mengirim rating...
            </div>
        `);
        
        // Submit rating via AJAX dengan improved error handling
        $.ajax({
            url: 'simpan_rating.php',
            method: 'POST',
            data: {
                school_id: schoolId,
                rating: rating
            },
            timeout: 15000,
            dataType: 'json', // Expect JSON response
            success: function(response) {
                console.log('Rating submission response:', response);
                
                // Handle response berdasarkan struktur yang baru
                if (response && response.success === true) {
                    messageDiv.html(`
                        <div class="alert alert-success alert-sm">
                            <i class="fas fa-check me-1"></i>
                            ${response.message || 'Rating berhasil dikirim!'}
                        </div>
                    `);
                    
                    // Hide rating form after successful submission
                    setTimeout(() => {
                        $(`#rating-form-${schoolId}`).fadeOut(500);
                    }, 2000);
                    
                    // Reload page to show updated ratings
                    setTimeout(() => {
                        console.log('Reloading page to show updated ratings');
                        location.reload();
                    }, 3000);
                    
                } else {
                    // Handle error response from server
                    const errorMessage = response && response.message ? response.message : 'Gagal mengirim rating';
                    messageDiv.html(`
                        <div class="alert alert-danger alert-sm">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            ${errorMessage}
                        </div>
                    `);
                    container.removeClass('submitting');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error Details:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                
                let errorMessage = 'Terjadi kesalahan sistem!';
                let debugInfo = '';
                
                // Handle different error types
                if (status === 'timeout') {
                    errorMessage = 'Koneksi timeout. Silakan coba lagi.';
                } else if (xhr.status === 404) {
                    errorMessage = 'File simpan_rating.php tidak ditemukan.';
                    debugInfo = 'Pastikan file simpan_rating.php ada di folder yang sama.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Kesalahan server internal.';
                    debugInfo = 'Periksa log error server untuk detail lebih lanjut.';
                } else if (status === 'parsererror') {
                    errorMessage = 'Server mengembalikan response yang tidak valid.';
                    debugInfo = 'Response: ' + xhr.responseText.substring(0, 100) + '...';
                } else if (xhr.status === 0) {
                    errorMessage = 'Tidak dapat terhubung ke server.';
                    debugInfo = 'Periksa koneksi internet dan pastikan server berjalan.';
                }
                
                messageDiv.html(`
                    <div class="alert alert-danger alert-sm">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>${errorMessage}</strong>
                        ${debugInfo ? '<br><small class="text-muted">' + debugInfo + '</small>' : ''}
                        <br>
                        <small class="text-muted">Debug: ${status} - ${error}</small>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-danger" onclick="location.reload()">
                                <i class="fas fa-redo me-1"></i>Refresh Halaman
                            </button>
                        </div>
                    </div>
                `);
                container.removeClass('submitting');
            }
        });
        
        return false;
    });
    
    // Enhanced hover effects for stars
    $('.star-label').hover(
        function() {
            const rating = $(this).data('rating');
            const container = $(this).closest('.stars-input');
            
            if (!container.hasClass('submitting')) {
                container.find('.star-label').each(function() {
                    const starRating = $(this).data('rating');
                    if (starRating <= rating) {
                        $(this).find('i').removeClass('text-muted').addClass('text-warning');
                    } else {
                        $(this).find('i').removeClass('text-warning').addClass('text-muted');
                    }
                });
            }
        },
        function() {
            const container = $(this).closest('.stars-input');
            
            if (!container.hasClass('submitting')) {
                container.find('.star-label i').removeClass('text-warning').addClass('text-muted');
            }
        }
    );
    
    // Function untuk test koneksi file
    function testFileConnections() {
        console.log('Testing file connections...');
        
        // Test simpan_rating.php
        $.ajax({
            url: 'simpan_rating.php',
            method: 'POST',
            data: { test: 'connection' },
            timeout: 5000,
            success: function(response) {
                console.log('✓ simpan_rating.php accessible');
            },
            error: function(xhr, status, error) {
                if (xhr.status === 404) {
                    console.error('✗ simpan_rating.php not found (404)');
                } else {
                    console.log('✓ simpan_rating.php accessible (responded with error as expected)');
                }
            }
        });
        
        // Test get_school_detail.php
        $.ajax({
            url: 'get_school_detail.php',
            method: 'GET',
            data: { test: 'connection' },
            timeout: 5000,
            success: function(response) {
                console.log('✓ get_school_detail.php accessible');
            },
            error: function(xhr, status, error) {
                if (xhr.status === 404) {
                    console.error('✗ get_school_detail.php not found (404)');
                } else {
                    console.log('✓ get_school_detail.php accessible');
                }
            }
        });
    }
    
    // Run connection test in development
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        testFileConnections();
    }
    
    // Comment submission handler
    $('#textAreaExample').next().find('button').on('click', function() {
        const comment = $('#textAreaExample').val().trim();
        
        if (comment === '') {
            alert('Silakan tulis komentar terlebih dahulu.');
            return;
        }
        
        alert('Komentar akan ditambahkan: ' + comment);
        $('#textAreaExample').val('');
    });
    
    // Additional UI enhancements
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    
    $('.back-to-top').click(function() {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });
    
    // Global error handler untuk debugging
    window.onerror = function(msg, url, lineNo, columnNo, error) {
        console.error('Global Error:', {
            message: msg,
            source: url,
            lineno: lineNo,
            colno: columnNo,
            error: error
        });
        return false;
    };
    
    console.log('School.php JavaScript initialized successfully');
});
</script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="school.js"></script>
</body>

</html>