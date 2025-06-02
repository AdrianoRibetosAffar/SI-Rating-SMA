<?php
session_start();
include 'config/conn.php';
$sekolah = mysqli_query($conn, "SELECT * FROM sekolah ORDER BY sekolah_id DESC LIMIT 6");
if (!$sekolah) {
    die("Query gagal: " . mysqli_error($conn));
}
$rating = mysqli_query($conn, "SELECT * FROM rating ORDER BY tanggal_rating DESC LIMIT 6");
if (!$rating) {
    die("Query gagal: " . mysqli_error($conn));
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
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
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Library link AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        filter-sidebar {
            background: white;
            border-radius: 12px;
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .product-card {
            background: rgb(255, 255, 255);
            border-radius: 12px;
            transition: all 0.3s ease;
            height:100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.445);
        }

        .product-image {
            height: 260px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        .rating-stars {
            color: #fbbf24;
        }

        .category-badge {
            background: #e5e7eb;
            color: #006aff;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
        }
    </style>
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span> <!--Mengatur loading halaman-->
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
                <a href="school.php" class="nav-item nav-link">School</a>
                <a href="about.html" class="nav-item nav-link">About</a>
                <a href="team.html" class="nav-item nav-link">Our Team</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Beranda Start -->
    <section id="beranda" class="beranda">
        <div class="container-fluid p-0 mb-5">
            <div class="owl-carousel header-carousel position-relative">
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="img/bg-japrax.png" alt=""> <!--slider foto-->
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(24, 29, 56, .7);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-sm-10 col-lg-8">
                                    <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Rating SMA Terbaik </h5>
                                    <h1 class="display-5 text-white animated slideInDown">Temukan SMA terbaik di Jayapura berdasarkan data objektif dan terpercaya.</h1>
                                    <a href="about.html" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Tentang</a>
                                    <a href="team.html" class="btn btn-light py-md-3 px-md-5 animated slideInRight">Team Kami</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </section>
    <!-- Beranda End -->


    <!-- Sekolah Start -->
    <section id="shop" class="shop">
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="text-center" data-aos="fade-up" data-aos-duration="800">
                        <h6 class="section-title bg-white text-center text-primary px-3">Sekolah</h6>
                        <h1 class="mb-5">Ranking Sekolah</h1>
                    </div>
                    <div class="col-lg-12" data-aos="fade-up" data-aos-duration="800">
                        <div class="row g-4 "  >
                            <!-- Sekolah 1 -->
                            <?php while ($row = mysqli_fetch_assoc($sekolah)) : ?>
                                <div class="col-md-4">
                                    <div class="product-card shadow-sm">
                                        <div class="position-relative">
                                            <img src="uploads/<?= htmlspecialchars($row['lambang_sekolah']) ?>" class="product-image w-100" alt="Product">
                                        </div>
                                        <div class="p-3">
                                            <h5 class="mb-1"><?= htmlspecialchars($row['nama_sekolah']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($row['alamat']) ?></p>
                                            <span class="category-badge mb-2 d-inline-block"><?= htmlspecialchars($row['akreditasi']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>    
                            <div class="text-end"> <!-- align kanan -->
                                <a href="school.php" class="btn btn-primary">
                                    <i class="fa-solid fa-angles-right"></i> More...
                                </a>
                            </div>                      
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Sekolah End -->

    <!-- Footer Start -->
    <footer class="bg-dark text-center text-white">
        <!-- Grid container -->
        <div class="container p-4 pb-0">
          <!-- Section: Social media -->
          <section class="mb-4">
            <h2 class="fw-bold text-light">Contact Me</h2>
            <!-- Facebook -->
            <a class="btn btn-outline-light btn-floating m-1" href="" role="button"
              ><i class="fab fa-whatsapp"></i
            ></a>
      
            <!-- Instagram -->
            <a class="btn btn-outline-light btn-floating m-1" href="" role="button"
              ><i class="fab fa-instagram"></i
            ></a>
          </section>
          <!-- Section: Social media -->
        </div>
        <!-- Grid container -->
      
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
          © 2025 Copyright:
          <a class="text-white" href="https://mdbootstrap.com/">Kelompok VII Manajemen Proyek</a>
        </div>
        <!-- Copyright -->
      </footer>
    <!-- Footer End -->
           
      <a href="#beranda" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
        <!-- Javascript -->
    <script src="js/main.js"></script>

</body>

</html>