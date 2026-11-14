
<?php

/**
 * SPMB STMIK PAMITRAN
 * Proses Autentikasi Administrator
 */

// Konfigurasi aplikasi
require_once __DIR__ . '/../config/app.php';

// Koneksi database
require_once __DIR__ . '/../config/database.php';

// Helper functions
require_once __DIR__ . '/../includes/functions.php';


// =====================================
// 1. HANYA MENERIMA POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/login.php');
}


// =====================================
// 2. MENGAMBIL INPUT
// =====================================

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$token = $_POST['csrf_token'] ?? '';


// =====================================
// 3. VALIDASI CSRF TOKEN
// =====================================

if (!verify_csrf_token($token)) {
    http_response_code(403);
    exit('Permintaan tidak valid. Silakan muat ulang halaman login.');
}


// =====================================
// 4. VALIDASI INPUT
// =====================================

if (
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 100 ||
    $password === ''
) {
    redirect(BASE_URL . '/admin/login.php?error=invalid');
}


// =====================================
// 5. MENCARI ADMIN DI DATABASE
// =====================================

// Query SQL satu baris
$sql = "SELECT id, nama, email, password FROM admin_users WHERE email = :email LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'email' => $email
]);

$admin = $stmt->fetch();


// =====================================
// 6. MEMERIKSA PASSWORD
// =====================================

if (!$admin || !password_verify($password, $admin['password'])) {

    redirect(BASE_URL . '/admin/login.php?error=invalid');

}


// =====================================
// 7. MEMBUAT SESSION LOGIN
// =====================================

session_regenerate_id(true);

$_SESSION['admin_id'] = $admin['id'];

$_SESSION['admin_nama'] = $admin['nama'];

$_SESSION['admin_email'] = $admin['email'];


// =====================================
// 8. REDIRECT KE DASHBOARD
// =====================================

redirect(BASE_URL . '/admin/dashboard.php');

