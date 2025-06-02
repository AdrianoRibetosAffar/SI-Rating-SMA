<?php
// Pastikan hanya output JSON yang dikirim
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Buffer output untuk mencegah output yang tidak diinginkan
ob_start();

try {
    session_start();
    include 'config/conn.php';
    
    // Validasi metode request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Metode request tidak valid');
    }
    
    // Validasi input
    if (!isset($_POST['school_id']) || !isset($_POST['rating'])) {
        throw new Exception('Data tidak lengkap');
    }
    
    $school_id = intval($_POST['school_id']);
    $rating = intval($_POST['rating']);
    
    // Validasi nilai rating
    if ($rating < 1 || $rating > 5) {
        throw new Exception('Rating harus antara 1-5');
    }
    
    // Validasi school_id
    if ($school_id <= 0) {
        throw new Exception('ID sekolah tidak valid');
    }
    
    // Cek koneksi database
    if (!$conn) {
        throw new Exception('Koneksi database gagal');
    }
    
    // Gunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($conn, "INSERT INTO rating (sekolah_id, rating, tanggal_rating) VALUES (?, ?, NOW())");
    
    if (!$stmt) {
        throw new Exception('Gagal menyiapkan query: ' . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $school_id, $rating);
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        throw new Exception('Gagal menyimpan rating: ' . mysqli_stmt_error($stmt));
    }
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    if ($affected_rows > 0) {
        // Berhasil
        $response = [
            'success' => true,
            'message' => 'Rating berhasil disimpan!',
            'data' => [
                'school_id' => $school_id,
                'rating' => $rating,
                'affected_rows' => $affected_rows
            ]
        ];
    } else {
        throw new Exception('Tidak ada data yang tersimpan');
    }
    
} catch (Exception $e) {
    // Tangani error
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ];
    
    // Log error untuk debugging (opsional)
    error_log("Rating Error: " . $e->getMessage() . " - School ID: " . ($school_id ?? 'undefined') . " - Rating: " . ($rating ?? 'undefined'));
}

// Bersihkan buffer dan kirim JSON response
ob_clean();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>