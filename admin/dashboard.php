
<?php

/**
 * SPMB STMIK PAMITRAN
 * Dashboard Administrator
 */

// Proteksi halaman
require_once __DIR__ . '/auth_check.php';

// Koneksi database
require_once __DIR__ . '/../config/database.php';

// Judul halaman
$page_title = 'Dashboard Administrator';


// =====================================
// 1. MENGAMBIL STATISTIK PENDAFTARAN
// =====================================

$sql_stats = "SELECT COUNT(*) AS total, SUM(status = 'Menunggu') AS menunggu, SUM(status = 'Diterima') AS diterima, SUM(status = 'Ditolak') AS ditolak FROM pendaftar";

$stmt_stats = $pdo->prepare($sql_stats);
$stmt_stats->execute();

$stats = $stmt_stats->fetch();


// =====================================
// 2. MENGAMBIL 5 PENDAFTAR TERBARU
// =====================================

$sql_recent = "SELECT p.id, p.no_pendaftaran, p.nik, p.nama_lengkap, p.email, p.status, p.created_at, ps.nama_prodi, ps.jenjang FROM pendaftar p INNER JOIN program_studi ps ON p.program_studi_id = ps.id ORDER BY p.id DESC LIMIT 5";

$stmt_recent = $pdo->prepare($sql_recent);
$stmt_recent->execute();

$pendaftar_terbaru = $stmt_recent->fetchAll();


// Memanggil header
require_once __DIR__ . '/../includes/header.php';

?>

<div class="container-fluid py-4">

    <!-- HEADER DASHBOARD -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Dashboard Administrator
            </h3>

            <p class="text-secondary mb-0">
                Selamat datang, <?= e($_SESSION['admin_nama']) ?>
            </p>

        </div>

        <a href="logout.php" class="btn btn-outline-danger">

            <i class="bi bi-box-arrow-right"></i>
            Logout

        </a>

    </div>


    <!-- STATISTIK -->
    <div class="row g-3 mb-4">

        <!-- TOTAL -->
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-secondary mb-1">
                                Total Pendaftar
                            </p>

                            <h2 class="fw-bold text-primary">
                                <?= (int) $stats['total'] ?>
                            </h2>

                        </div>

                        <i class="bi bi-people-fill text-primary"
                           style="font-size: 40px;"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- MENUNGGU -->
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-secondary mb-1">
                                Menunggu
                            </p>

                            <h2 class="fw-bold text-warning">
                                <?= (int) $stats['menunggu'] ?>
                            </h2>

                        </div>

                        <i class="bi bi-clock-history text-warning"
                           style="font-size: 40px;"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- DITERIMA -->
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-secondary mb-1">
                                Diterima
                            </p>

                            <h2 class="fw-bold text-success">
                                <?= (int) $stats['diterima'] ?>
                            </h2>

                        </div>

                        <i class="bi bi-check-circle-fill text-success"
                           style="font-size: 40px;"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- DITOLAK -->
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-secondary mb-1">
                                Ditolak
                            </p>

                            <h2 class="fw-bold text-danger">
                                <?= (int) $stats['ditolak'] ?>
                            </h2>

                        </div>

                        <i class="bi bi-x-circle-fill text-danger"
                           style="font-size: 40px;"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- TABEL PENDAFTAR TERBARU -->
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white p-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-table text-primary"></i>
                    Pendaftar Terbaru
                </h5>

                <a href="<?= BASE_URL ?>/admin/pendaftar/index.php"
                   class="btn btn-primary btn-sm">

                    Lihat Semua

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>No</th>
                            <th>No. Pendaftaran</th>
                            <th>Nama Lengkap</th>
                            <th>Program Studi</th>
                            <th>Email</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (count($pendaftar_terbaru) > 0): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($pendaftar_terbaru as $row): ?>

                                <tr>

                                    <td><?= $no++ ?></td>

                                    <td>
                                        <span class="fw-semibold">
                                            <?= e($row['no_pendaftaran']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= e($row['nama_lengkap']) ?>
                                    </td>

                                    <td>
                                        <?= e($row['nama_prodi']) ?>
                                        (<?= e($row['jenjang']) ?>)
                                    </td>

                                    <td>
                                        <?= e($row['email']) ?>
                                    </td>

                                    <td>

                                        <?php if ($row['status'] === 'Menunggu'): ?>

                                            <span class="badge bg-warning text-dark">
                                                Menunggu
                                            </span>

                                        <?php elseif ($row['status'] === 'Diterima'): ?>

                                            <span class="badge bg-success">
                                                Diterima
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="6"
                                    class="text-center text-secondary py-4">

                                    Belum ada data pendaftar.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php

// Memanggil footer
require_once __DIR__ . '/../includes/footer.php';

?>
