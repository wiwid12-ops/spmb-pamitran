
<?php

/**
 * SPMB STMIK PAMITRAN
 * DELETE - Menghapus Data Pendaftar
 */

// =====================================
// 1. PROTEKSI ADMINISTRATOR
// =====================================

require_once __DIR__ . '/../auth_check.php';


// =====================================
// 2. KONEKSI DATABASE
// =====================================

require_once __DIR__ . '/../../config/database.php';


// =====================================
// 3. VALIDASI METHOD POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/pendaftar/index.php');
}


// =====================================
// 4. MENGAMBIL INPUT
// =====================================

$id = filter_var(
    $_POST['id'] ?? '',
    FILTER_VALIDATE_INT
);

$token = $_POST['csrf_token'] ?? '';


// =====================================
// 5. VALIDASI CSRF TOKEN
// =====================================

if (!verify_csrf_token($token)) {
    http_response_code(403);
    exit('Token keamanan tidak valid. Silakan kembali dan muat ulang halaman.');
}


// =====================================
// 6. VALIDASI ID
// =====================================

if ($id === false || $id === null || $id < 1) {
    redirect(BASE_URL . '/admin/pendaftar/index.php?error=invalid');
}


// =====================================
// 7. PROSES PENGHAPUSAN
// =====================================

try {

    // Memeriksa apakah data tersedia
    $sql_check = "SELECT id FROM pendaftar WHERE id = :id LIMIT 1";

    $stmt_check = $pdo->prepare($sql_check);

    $stmt_check->execute([
        'id' => $id
    ]);

    $pendaftar = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$pendaftar) {
        redirect(BASE_URL . '/admin/pendaftar/index.php?error=notfound');
    }


    // Menjalankan DELETE
    $sql = "DELETE FROM pendaftar WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);


    // Memeriksa hasil penghapusan
    if ($stmt->rowCount() === 1) {

        redirect(BASE_URL . '/admin/pendaftar/index.php?success=deleted');

    }

    redirect(BASE_URL . '/admin/pendaftar/index.php?error=failed');


} catch (PDOException $e) {

    // Mencatat error ke log server
    error_log($e->getMessage());

    // Jika gagal karena relasi foreign key atau masalah database
    redirect(BASE_URL . '/admin/pendaftar/index.php?error=failed');

}

