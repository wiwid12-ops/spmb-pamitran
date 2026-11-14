
<?php

/**
 * SPMB STMIK PAMITRAN
 * Formulir Pendaftaran Mahasiswa
 */

// Koneksi database
require_once __DIR__ . '/config/database.php';

// Helper functions
require_once __DIR__ . '/includes/functions.php';

// Judul halaman
$page_title = 'Formulir Pendaftaran';

// Mengambil program studi
$sql_prodi = "SELECT id, kode_prodi, nama_prodi, jenjang
              FROM program_studi
              ORDER BY nama_prodi ASC";

$stmt_prodi = $pdo->prepare($sql_prodi);
$stmt_prodi->execute();

$program_studi = $stmt_prodi->fetchAll();

// Mengambil tahun akademik aktif
$sql_tahun = "SELECT id, tahun
              FROM tahun_akademik
              WHERE status_aktif = :status
              LIMIT 1";

$stmt_tahun = $pdo->prepare($sql_tahun);

$stmt_tahun->execute([
    'status' => 1
]);

$tahun_aktif = $stmt_tahun->fetch();

// Memanggil header
require_once __DIR__ . '/includes/header.php';

?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <!-- JUDUL FORM -->
            <div class="text-center mb-4">

                <h2 class="fw-bold text-primary">
                    Formulir Pendaftaran
                </h2>

                <p class="text-secondary">
                    Penerimaan Mahasiswa Baru
                    STMIK Pamitran
                </p>

            </div>

            <?php if (!$tahun_aktif): ?>

                <div class="alert alert-warning">
                    Pendaftaran belum dibuka.
                </div>

            <?php elseif (empty($program_studi)): ?>

                <div class="alert alert-warning">
                    Program studi belum tersedia.
                </div>

            <?php else: ?>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-primary text-white p-3">

                        <h5 class="mb-0">
                            <i class="bi bi-person-vcard"></i>
                            Data Calon Mahasiswa
                        </h5>

                    </div>

                    <div class="card-body p-4">

                        <form action="simpan.php"
                              method="POST">

                            <!-- CSRF TOKEN -->
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= e(csrf_token()) ?>">

                            <!-- INFORMASI PRIBADI -->
                            <h5 class="fw-bold mb-3">
                                A. Identitas Pribadi
                            </h5>

                            <div class="mb-3">

                                <label class="form-label">
                                    NIK
                                </label>

                                <input
                                    type="text"
                                    name="nik"
                                    class="form-control"
                                    maxlength="16"
                                    minlength="16"
                                    pattern="[0-9]{16}"
                                    placeholder="Masukkan 16 digit NIK"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Lengkap
                                </label>

                                <input
                                    type="text"
                                    name="nama_lengkap"
                                    class="form-control"
                                    maxlength="150"
                                    placeholder="Nama sesuai identitas"
                                    required>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Tempat Lahir
                                    </label>

                                    <input
                                        type="text"
                                        name="tempat_lahir"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Tanggal Lahir
                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_lahir"
                                        class="form-control"
                                        required>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Jenis Kelamin
                                </label>

                                <select
                                    name="jenis_kelamin"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Jenis Kelamin --
                                    </option>

                                    <option value="L">
                                        Laki-laki
                                    </option>

                                    <option value="P">
                                        Perempuan
                                    </option>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Alamat Lengkap
                                </label>

                                <textarea
                                    name="alamat"
                                    class="form-control"
                                    rows="3"
                                    required></textarea>

                            </div>

                            <hr>

                            <!-- KONTAK -->
                            <h5 class="fw-bold mb-3">
                                B. Informasi Kontak
                            </h5>

                            <div class="mb-3">

                                <label class="form-label">
                                    Nomor HP
                                </label>

                                <input
                                    type="tel"
                                    name="no_hp"
                                    class="form-control"
                                    maxlength="20"
                                    placeholder="Contoh: 081234567890"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    maxlength="100"
                                    placeholder="nama@email.com"
                                    required>

                            </div>

                            <hr>

                            <!-- PENDIDIKAN -->
                            <h5 class="fw-bold mb-3">
                                C. Informasi Pendidikan
                            </h5>

                            <div class="mb-3">

                                <label class="form-label">
                                    Asal Sekolah
                                </label>

                                <input
                                    type="text"
                                    name="asal_sekolah"
                                    class="form-control"
                                    maxlength="150"
                                    placeholder="Nama SMA/SMK/MA"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Program Studi
                                </label>

                                <select
                                    name="program_studi_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Program Studi --
                                    </option>

                                    <?php foreach ($program_studi as $prodi): ?>

                                        <option value="<?= e($prodi['id']) ?>">

                                            <?= e($prodi['nama_prodi']) ?>

                                            (<?= e($prodi['jenjang']) ?>)

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Tahun Akademik
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= e($tahun_aktif['tahun']) ?>"
                                    readonly>

                                <input
                                    type="hidden"
                                    name="tahun_akademik_id"
                                    value="<?= e($tahun_aktif['id']) ?>">

                            </div>

                            <hr>

                            <!-- TOMBOL -->
                            <div class="d-flex gap-2 justify-content-between">

                                <a href="index.php"
                                   class="btn btn-outline-secondary">

                                    <i class="bi bi-arrow-left"></i>
                                    Kembali

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="bi bi-send"></i>
                                    Kirim Pendaftaran

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php

require_once __DIR__ . '/includes/footer.php';

?>
