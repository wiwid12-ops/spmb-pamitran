
<?php

/**
 * SPMB STMIK PAMITRAN
 * Halaman Utama
 */

// Koneksi database
require_once 'config/database.php';

// Helper functions
require_once 'includes/functions.php';

// Judul halaman
$page_title = 'Beranda';

// Mengambil program studi dari database
$sql_prodi = "SELECT id, kode_prodi, nama_prodi, jenjang
              FROM program_studi
              ORDER BY nama_prodi ASC";

$stmt_prodi = $pdo->prepare($sql_prodi);
$stmt_prodi->execute();

$program_studi = $stmt_prodi->fetchAll();

// Mengambil tahun akademik yang aktif
$sql_tahun = "SELECT id, tahun
              FROM tahun_akademik
              WHERE status_aktif = :status
              ORDER BY id DESC
              LIMIT 1";

$stmt_tahun = $pdo->prepare($sql_tahun);

$stmt_tahun->execute([
    'status' => 1
]);

$tahun_aktif = $stmt_tahun->fetch();

// Memanggil header
require_once 'includes/header.php';

?>

<!-- HERO SECTION -->
<section class="hero-section">

    <div class="container text-center">

        <h1 class="display-5">
            Penerimaan Mahasiswa Baru
        </h1>

        <h2 class="fw-bold mt-2">
            STMIK PAMITRAN
        </h2>

        <p class="mt-3">
            Wujudkan masa depan bersama pendidikan
            teknologi informasi.
        </p>

        <?php if ($tahun_aktif): ?>

            <div class="badge bg-warning text-dark fs-6 p-2">

                Tahun Akademik
                <?= e($tahun_aktif['tahun']) ?>

            </div>

        <?php else: ?>

            <div class="badge bg-danger fs-6 p-2">

                Pendaftaran Belum Dibuka

            </div>

        <?php endif; ?>

        <div class="mt-4">

            <a href="daftar.php"
               class="btn btn-warning btn-lg fw-bold">

                Daftar Sekarang

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</section>


<!-- PROGRAM STUDI -->
<section class="container py-5">

    <div class="text-center mb-4">

        <h2 class="section-title">
            Program Studi
        </h2>

        <p class="text-secondary">
            Pilih program studi sesuai minat dan kemampuanmu.
        </p>

    </div>

    <div class="row g-4">

        <?php if (count($program_studi) > 0): ?>

            <?php foreach ($program_studi as $prodi): ?>

                <div class="col-md-4">

                    <div class="card prodi-card shadow-sm h-100">

                        <div class="card-body text-center p-4">

                            <div class="mb-3">

                                <i class="bi bi-mortarboard-fill
                                          text-primary"
                                   style="font-size: 45px;">
                                </i>

                            </div>

                            <h5 class="fw-bold">

                                <?= e($prodi['nama_prodi']) ?>

                            </h5>

                            <p class="text-secondary">

                                Kode:
                                <?= e($prodi['kode_prodi']) ?>

                            </p>

                            <span class="badge bg-primary">

                                <?= e($prodi['jenjang']) ?>

                            </span>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">

                <div class="alert alert-warning text-center">

                    Belum ada program studi tersedia.

                </div>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- INFORMASI PENDAFTARAN -->
<section class="container pb-5">

    <div class="card shadow-sm border-0">

        <div class="card-body text-center p-5">

            <h3 class="fw-bold">
                Siap Menjadi Mahasiswa STMIK Pamitran?
            </h3>

            <p class="text-secondary mt-3">

                Mulai perjalanan akademikmu sekarang.

            </p>

            <a href="daftar.php"
               class="btn btn-primary btn-lg">

                Mulai Pendaftaran

            </a>

        </div>

    </div>

</section>


<?php

// Memanggil footer
require_once 'includes/footer.php';

?>
