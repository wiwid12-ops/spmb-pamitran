
<?php

/**
 * SPMB STMIK PAMITRAN
 * Membuat Akun Administrator
 * Jalankan melalui CMD, bukan browser.
 */

require_once __DIR__ . '/../config/database.php';

if (PHP_SAPI !== 'cli') {
    exit("Script hanya boleh dijalankan melalui CMD.");
}

echo "====================================\n";
echo " PEMBUATAN AKUN ADMIN SPMB PAMITRAN\n";
echo "====================================\n\n";

// Input nama
echo "Nama Administrator : ";
$nama = trim(fgets(STDIN));

// Input email
echo "Email Administrator: ";
$email = trim(fgets(STDIN));

// Input password
echo "Password           : ";
$password = trim(fgets(STDIN));

// Validasi
if ($nama === '' || strlen($nama) > 100) {
    exit("Nama tidak valid.\n");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Format email tidak valid.\n");
}

if (strlen($password) < 10) {
    exit("Password minimal 10 karakter.\n");
}

// Periksa email duplikat
$stmt = $pdo->prepare(
    "SELECT id FROM admin_users WHERE email = :email"
);

$stmt->execute([
    'email' => $email
]);

if ($stmt->fetch()) {
    exit("Email sudah terdaftar.\n");
}

// Hash password
$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

// Simpan administrator
$sql = "INSERT INTO admin_users (
            nama,
            email,
            password
        ) VALUES (
            :nama,
            :email,
            :password
        )";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'nama' => $nama,
    'email' => $email,
    'password' => $password_hash
]);

echo "\n====================================\n";
echo " AKUN ADMIN BERHASIL DIBUAT!\n";
echo "====================================\n";
echo "Nama : $nama\n";
echo "Email: $email\n";
