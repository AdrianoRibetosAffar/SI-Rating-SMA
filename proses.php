<?php
// filepath: c:\xampp\htdocs\SI-Rating-SMA\proses.php

session_start();

$sekolah = isset($_SESSION['sekolah']) ? $_SESSION['sekolah'] : [];
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// Fungsi untuk resize gambar
function resizeImage($sourcePath, $destinationPath, $width, $height) {
    if (!function_exists('imagecreatefromjpeg')) {
        // Jika ekstensi GD tidak aktif, return false
        return false;
    }

    list($originalWidth, $originalHeight, $imageType) = getimagesize($sourcePath);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false; // Format tidak didukung
    }

    $resizedImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $destinationPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $destinationPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($resizedImage, $destinationPath);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($resizedImage);

    return true;
}

if ($action == 'save') {
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $akreditasi = $_POST['akreditasi'];

    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $gambar = $uploadDir . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);

        // Resize gambar menjadi 100px x 100px
        if (!resizeImage($gambar, $gambar, 100, 100)) {
            error_log("Resize gagal. Menggunakan gambar asli.");
        }
    } else {
        // Jika tidak ada gambar baru, gunakan gambar lama
        $gambar = $id !== null ? $sekolah[$id]['gambar'] : '';
    }

    if ($id !== null) {
        // Update data di session
        $sekolah[$id]['nama'] = $nama;
        $sekolah[$id]['alamat'] = $alamat;
        $sekolah[$id]['gambar'] = $gambar;
        $sekolah[$id]['akreditasi'] = $akreditasi;

        // Update elemen di school.html
        $filePath = 'school.html';
        $htmlContent = file_get_contents($filePath);

        // Buat pola regex untuk elemen yang akan diganti
        $oldCardPattern = '/<div class="col-md-4">\s*<div class="product-card shadow-sm">\s*<div class="position-relative text-center">\s*<img src="' . preg_quote($sekolah[$id]['gambar'], '/') . '" class="product-image" alt="Gambar Sekolah">\s*<\/div>\s*<div class="p-3 text-start">\s*<h5 class="mb-1">' . preg_quote(htmlspecialchars($sekolah[$id]['nama']), '/') . '<\/h5>\s*<p class="card-text">' . preg_quote(htmlspecialchars($sekolah[$id]['alamat']), '/') . '<\/p>\s*<span class="category-badge mb-2 d-inline-block">Akreditasi ' . preg_quote(htmlspecialchars($sekolah[$id]['akreditasi']), '/') . '<\/span>\s*<\/div>\s*<\/div>\s*<\/div>/';

        // Elemen baru
        $newCard = '
<div class="col-md-4">
    <div class="product-card shadow-sm">
        <div class="position-relative text-center">
            <img src="' . $gambar . '" class="product-image" alt="Gambar Sekolah">
        </div>
        <div class="p-3 text-start">
            <h5 class="mb-1">' . htmlspecialchars($nama) . '</h5>
            <p class="card-text">' . htmlspecialchars($alamat) . '</p>
            <span class="category-badge mb-2 d-inline-block">Akreditasi ' . htmlspecialchars($akreditasi) . '</span>
        </div>
    </div>
</div>';

        // Ganti elemen lama dengan elemen baru
        $htmlContent = preg_replace($oldCardPattern, $newCard, $htmlContent);

        // Simpan kembali file school.html
        file_put_contents($filePath, $htmlContent);
    } else {
        // Tambahkan data baru jika ID tidak ada
        $sekolah[] = ['nama' => $nama, 'alamat' => $alamat, 'gambar' => $gambar, 'akreditasi' => $akreditasi];

        // Tambahkan elemen baru ke school.html
        $newCard = '
<div class="col-md-4">
    <div class="product-card shadow-sm">
        <div class="position-relative text-center">
            <img src="' . $gambar . '" class="product-image" alt="Gambar Sekolah">
        </div>
        <div class="p-3 text-start">
            <h5 class="mb-1">' . htmlspecialchars($nama) . '</h5>
            <p class="card-text">' . htmlspecialchars($alamat) . '</p>
            <span class="category-badge mb-2 d-inline-block">Akreditasi ' . htmlspecialchars($akreditasi) . '</span>
        </div>
    </div>
</div>';

        $filePath = 'school.html';
        $htmlContent = file_get_contents($filePath);

        $insertPosition = strpos($htmlContent, '<!-- Sekolah lain ditambahkan jika mau -->');
        if ($insertPosition !== false) {
            $htmlContent = substr_replace($htmlContent, $newCard . "\n", $insertPosition, 0);
            file_put_contents($filePath, $htmlContent);
        }
    }

    $_SESSION['sekolah'] = $sekolah;
    header('Location: index.php');
    exit;
} elseif ($action == 'delete') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id !== null) {
        $deletedData = $sekolah[$id];
        unset($sekolah[$id]);
        $sekolah = array_values($sekolah);

        // Hapus data dari file school.html
        $filePath = 'school.html';
        $htmlContent = file_get_contents($filePath);

        // Buat pola regex untuk mencocokkan elemen yang akan dihapus
        $cardToDeletePattern = '/<div class="col-md-4">\s*<div class="product-card shadow-sm">\s*<div class="position-relative text-center">\s*<img src="' . preg_quote($deletedData['gambar'], '/') . '" class="product-image" alt="Gambar Sekolah">\s*<\/div>\s*<div class="p-3 text-start">\s*<h5 class="mb-1">' . preg_quote(htmlspecialchars($deletedData['nama']), '/') . '<\/h5>\s*<p class="card-text">' . preg_quote(htmlspecialchars($deletedData['alamat']), '/') . '<\/p>\s*<span class="category-badge mb-2 d-inline-block">Akreditasi ' . preg_quote(htmlspecialchars($deletedData['akreditasi']), '/') . '<\/span>\s*<\/div>\s*<\/div>\s*<\/div>/';

        // Hapus elemen yang cocok dengan pola
        $htmlContent = preg_replace($cardToDeletePattern, '', $htmlContent);

        // Simpan kembali file school.html
        file_put_contents($filePath, $htmlContent);
    }

    $_SESSION['sekolah'] = $sekolah;
    header('Location: index.php');
    exit;
} elseif ($action == 'edit') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    header("Location: index.php?id=$id");
    exit;
} else {
    header('Location: index.php');
    exit;
} 
