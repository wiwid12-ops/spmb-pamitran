
<?php

/**
 * SPMB STMIK PAMITRAN
 * Proses UPDATE Status Pendaftar
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
// 4. MENGAMBIL DATA FORM
// =====================================

$id = filter_var(
    $_POST['id'] ?? '',
    FILTER_VALIDATE_INT
);

$status = $_POST['status'] ?? '';

$token = $_POST['csrf_token'] ?? '';


// =====================================
// 5. VALIDASI CSRF TOKEN
// =====================================

if (!verify_csrf_token($token)) {

    http_response_code(403);

    exit('Token keamanan tidak valid. Silakan kembali dan muat ulang halaman.');

}


// =====================================
// 6. VALIDASI INPUT
// =====================================

$status_valid = [
    'Menunggu',
    'Diterima',
    'Ditolak'
];

if (
    $id === false ||
    $id === null ||
    $id < 1 ||
    !in_array($status, $status_valid, true)
) {

    redirect(BASE_URL . '/admin/pendaftar/index.php?error=invalid');

}


// =====================================
// 7. MEMERIKSA DATA PENDAFTAR
// =====================================

$sql_check = "SELECT id FROM pendaftar WHERE id = :id LIMIT 1";

$stmt_check = $pdo->prepare($sql_check);

$stmt_check->execute([
    'id' => $id
]);

$pendaftar = $stmt_check->fetch(PDO::FETCH_ASSOC);


if (!$pendaftar) {

    redirect(BASE_URL . '/admin/pendaftar/index.php?error=invalid');

}


// =====================================
// 8. MENJALANKAN UPDATE
// =====================================

try {

    $sql = "UPDATE pendaftar SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'status' => $status,
        'id' => $id
    ]);

    // Redirect setelah berhasil
    redirect(BASE_URL . '/admin/pendaftar/index.php?success=updated');

} catch (PDOException $e) {

    // Catat kesalahan pada log server
    error_log($e->getMessage());

    redirect(BASE_URL . '/admin/pendaftar/index.php?error=failed');

}

