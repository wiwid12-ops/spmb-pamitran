
<?php

/**
 * SPMB STMIK PAMITRAN
 * Proses Penyimpanan Pendaftaran
 */

// Koneksi database
require_once __DIR__ . '/config/database.php';

// Helper functions
require_once __DIR__ . '/includes/functions.php';

// Konfigurasi aplikasi
require_once __DIR__ . '/config/app.php';


// Hanya menerima metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/daftar.php');
}

// Mengambil dan membersihkan input
$nik              = trim($_POST['nik'] ?? '');
$nama_lengkap     = trim($_POST['nama_lengkap'] ?? '');
$tempat_lahir     = trim($_POST['tempat_lahir'] ?? '');
$tanggal_lahir    = trim($_POST['tanggal_lahir'] ?? '');
$jenis_kelamin    = trim($_POST['jenis_kelamin'] ?? '');
$alamat           = trim($_POST['alamat'] ?? '');
$no_hp            = trim($_POST['no_hp'] ?? '');
$email            = trim($_POST['email'] ?? '');
$asal_sekolah     = trim($_POST['asal_sekolah'] ?? '');
$program_studi_id = filter_var(
    $_POST['program_studi_id'] ?? null,
    FILTER_VALIDATE_INT
);
$tahun_akademik_id = filter_var(
    $_POST['tahun_akademik_id'] ?? null,
    FILTER_VALIDATE_INT
);

$token = $_POST['csrf_token'] ?? '';


// =====================================
// 1. VALIDASI CSRF TOKEN
// =====================================

if (!verify_csrf_token($token)) {
    http_response_code(403);
    die('Permintaan tidak valid. Silakan kembali dan muat ulang formulir.');
}


// =====================================
// 2. VALIDASI INPUT
// =====================================

$errors = [];

// Validasi NIK
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    $errors[] = 'NIK harus terdiri dari 16 digit angka.';
}

// Validasi nama
if ($nama_lengkap === '' || strlen($nama_lengkap) > 600) {
    $errors[] = 'Nama lengkap wajib diisi.';
}

// Validasi tempat lahir
if ($tempat_lahir === '' || strlen($tempat_lahir) > 400) {
    $errors[] = 'Tempat lahir wajib diisi.';
}

// Validasi tanggal lahir
$date = DateTime::createFromFormat('!Y-m-d', $tanggal_lahir);

if (
    !$date ||
    $date->format('Y-m-d') !== $tanggal_lahir ||
    $tanggal_lahir > date('Y-m-d')
) {
    $errors[] = 'Tanggal lahir tidak valid.';
}

// Validasi jenis kelamin
if (!in_array($jenis_kelamin, ['L', 'P'], true)) {
    $errors[] = 'Jenis kelamin tidak valid.';
}

// Validasi alamat
if ($alamat === '') {
    $errors[] = 'Alamat wajib diisi.';
}

// Validasi nomor HP
if (!preg_match('/^[0-9+\-\s()]{8,20}$/', $no_hp)) {
    $errors[] = 'Nomor HP tidak valid.';
}

// Validasi email
if (
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 100
) {
    $errors[] = 'Alamat email tidak valid.';
}

// Validasi asal sekolah
if ($asal_sekolah === '' || strlen($asal_sekolah) > 600) {
    $errors[] = 'Asal sekolah wajib diisi.';
}

// Validasi program studi
if (!$program_studi_id || $program_studi_id < 1) {
    $errors[] = 'Silakan pilih program studi.';
}

// Validasi tahun akademik
if (!$tahun_akademik_id || $tahun_akademik_id < 1) {
    $errors[] = 'Tahun akademik tidak valid.';
}


// =====================================
// 3. PERIKSA HASIL VALIDASI
// =====================================

if (!empty($errors)) {

    http_response_code(422);

    require_once __DIR__ . '/includes/header.php';

    ?>

    <div class="container py-5">

        <div class="alert alert-danger">

            <h5>Validasi Gagal</h5>

            <ul class="mb-0">

                <?php foreach ($errors as $error): ?>

                    <li><?= e($error) ?></li>

                <?php endforeach; ?>

            </ul>

        </div>

        <a href="daftar.php" class="btn btn-primary">
            Kembali ke Formulir
        </a>

    </div>

    <?php

    require_once __DIR__ . '/includes/footer.php';

    exit;
}


// =====================================
// 4. PERIKSA PROGRAM STUDI
// =====================================

$stmt = $pdo->prepare(
    "SELECT id
     FROM program_studi
     WHERE id = :id"
);

$stmt->execute([
    'id' => $program_studi_id
]);

if (!$stmt->fetch()) {
    die('Program studi tidak ditemukan.');
}


// =====================================
// 5. PERIKSA TAHUN AKADEMIK AKTIF
// =====================================

$stmt = $pdo->prepare(
    "SELECT id
     FROM tahun_akademik
     WHERE id = :id
     AND status_aktif = :status"
);

$stmt->execute([
    'id' => $tahun_akademik_id,
    'status' => 1
]);

if (!$stmt->fetch()) {
    die('Tahun akademik tidak aktif atau tidak ditemukan.');
}


// =====================================
// 6. CEK NIK DUPLIKAT
// =====================================

$stmt = $pdo->prepare(
    "SELECT id
     FROM pendaftar
     WHERE nik = :nik"
);

$stmt->execute([
    'nik' => $nik
]);

if ($stmt->fetch()) {
    die('NIK sudah terdaftar. Silakan periksa kembali data Anda.');
}


// =====================================
// 7. MEMBUAT NOMOR PENDAFTARAN
// =====================================

$no_pendaftaran = 'SPMB-' .
    date('Ymd') . '-' .
    strtoupper(bin2hex(random_bytes(3)));


// =====================================
// 8. INSERT DATA MENGGUNAKAN PDO
// =====================================

$sql = "INSERT INTO pendaftar (
            no_pendaftaran,
            nik,
            nama_lengkap,
            tempat_lahir,
            tanggal_lahir,
            jenis_kelamin,
            alamat,
            no_hp,
            email,
            asal_sekolah,
            program_studi_id,
            tahun_akademik_id
        ) VALUES (
            :no_pendaftaran,
            :nik,
            :nama_lengkap,
            :tempat_lahir,
            :tanggal_lahir,
            :jenis_kelamin,
            :alamat,
            :no_hp,
            :email,
            :asal_sekolah,
            :program_studi_id,
            :tahun_akademik_id
        )";

try {

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        'no_pendaftaran' => $no_pendaftaran,
        'nik' => $nik,
        'nama_lengkap' => $nama_lengkap,
        'tempat_lahir' => $tempat_lahir,
        'tanggal_lahir' => $tanggal_lahir,
        'jenis_kelamin' => $jenis_kelamin,
        'alamat' => $alamat,
        'no_hp' => $no_hp,
        'email' => $email,
        'asal_sekolah' => $asal_sekolah,
        'program_studi_id' => $program_studi_id,
        'tahun_akademik_id' => $tahun_akademik_id

    ]);

    // Simpan informasi keberhasilan ke session
    $_SESSION['pendaftaran_berhasil'] = [
        'no_pendaftaran' => $no_pendaftaran,
        'nama_lengkap' => $nama_lengkap
    ];

    // Arahkan ke halaman berhasil
    redirect(BASE_URL . '/berhasil.php');

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);

    die('Terjadi kesalahan saat menyimpan pendaftaran. Silakan coba kembali.');

}
