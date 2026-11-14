
<?php

/**
 * SPMB STMIK PAMITRAN
 * Data Pendaftar - READ, UPDATE, DELETE
 */

// 1. Proteksi administrator
require_once __DIR__ . '/../auth_check.php';

// 2. Koneksi database
require_once __DIR__ . '/../../config/database.php';

$page_title = 'Data Pendaftar';


// =====================================
// 3. INPUT PENCARIAN
// =====================================

$keyword = trim($_GET['keyword'] ?? '');
$status = $_GET['status'] ?? '';
$prodi_id = filter_var($_GET['prodi_id'] ?? '', FILTER_VALIDATE_INT);

$status_valid = ['Menunggu', 'Diterima', 'Ditolak'];

if (!in_array($status, $status_valid, true)) {
    $status = '';
}

if ($prodi_id === false || $prodi_id < 1) {
    $prodi_id = null;
}


// =====================================
// 4. DATA PROGRAM STUDI
// =====================================

$sql_prodi = "SELECT id, nama_prodi FROM program_studi ORDER BY nama_prodi ASC";

$stmt_prodi = $pdo->prepare($sql_prodi);
$stmt_prodi->execute();

$daftar_prodi = $stmt_prodi->fetchAll(PDO::FETCH_ASSOC);


// =====================================
// 5. FILTER PENCARIAN
// =====================================

$conditions = [];
$params = [];

if ($keyword !== '') {

    $conditions[] = "(p.nama_lengkap LIKE :keyword_nama OR p.nik LIKE :keyword_nik OR p.no_pendaftaran LIKE :keyword_nomor)";

    $params['keyword_nama'] = '%' . $keyword . '%';
    $params['keyword_nik'] = '%' . $keyword . '%';
    $params['keyword_nomor'] = '%' . $keyword . '%';

}

if ($status !== '') {

    $conditions[] = "p.status = :status";
    $params['status'] = $status;

}

if ($prodi_id !== null) {

    $conditions[] = "p.program_studi_id = :prodi_id";
    $params['prodi_id'] = $prodi_id;

}


// =====================================
// 6. QUERY DATA PENDAFTAR
// =====================================

$sql = "SELECT p.id, p.no_pendaftaran, p.nik, p.nama_lengkap, p.jenis_kelamin, p.email, p.status, p.created_at, ps.nama_prodi, ps.jenjang, ta.tahun FROM pendaftar p INNER JOIN program_studi ps ON p.program_studi_id = ps.id INNER JOIN tahun_akademik ta ON p.tahun_akademik_id = ta.id";

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY p.id DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$pendaftar = $stmt->fetchAll(PDO::FETCH_ASSOC);


// =====================================
// 7. HEADER
// =====================================

require_once __DIR__ . '/../../includes/header.php';

?>

<div class="container-fluid py-4">

    <!-- HEADER HALAMAN -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                <i class="bi bi-people-fill text-primary"></i>
                Data Pendaftar
            </h3>

            <p class="text-secondary mb-0">
                Pengelolaan seluruh calon mahasiswa baru.
            </p>

        </div>

        <a href="<?= BASE_URL ?>/admin/dashboard.php"
           class="btn btn-outline-primary">

            <i class="bi bi-arrow-left"></i>
            Dashboard

        </a>

    </div>


    <!-- NOTIFIKASI -->

    <?php if (($_GET['success'] ?? '') === 'updated'): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill"></i>
            Status pendaftaran berhasil diperbarui.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php elseif (($_GET['success'] ?? '') === 'deleted'): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill"></i>
            Data pendaftar berhasil dihapus.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php elseif (($_GET['error'] ?? '') === 'invalid'): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            Data yang dikirim tidak valid.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php elseif (($_GET['error'] ?? '') === 'notfound'): ?>

        <div class="alert alert-warning alert-dismissible fade show">

            Data pendaftar tidak ditemukan.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php elseif (($_GET['error'] ?? '') === 'failed'): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            Proses gagal. Periksa log server atau relasi database.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php endif; ?>


    <!-- FORM PENCARIAN -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET" action="index.php">

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            Cari Pendaftar
                        </label>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Nama, NIK, atau nomor pendaftaran"
                            value="<?= e($keyword) ?>">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="">Semua Status</option>

                            <option value="Menunggu" <?= $status === 'Menunggu' ? 'selected' : '' ?>>
                                Menunggu
                            </option>

                            <option value="Diterima" <?= $status === 'Diterima' ? 'selected' : '' ?>>
                                Diterima
                            </option>

                            <option value="Ditolak" <?= $status === 'Ditolak' ? 'selected' : '' ?>>
                                Ditolak
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Program Studi
                        </label>

                        <select name="prodi_id" class="form-select">

                            <option value="">Semua Prodi</option>

                            <?php foreach ($daftar_prodi as $prodi): ?>

                                <option
                                    value="<?= (int) $prodi['id'] ?>"
                                    <?= (string) $prodi_id === (string) $prodi['id'] ? 'selected' : '' ?>>

                                    <?= e($prodi['nama_prodi']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-search"></i>
                                Cari

                            </button>

                            <a href="index.php" class="btn btn-secondary">

                                <i class="bi bi-arrow-counterclockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- TABEL PENDAFTAR -->

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white p-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-table text-primary"></i>
                    Tabel Calon Mahasiswa

                </h5>

                <span class="badge bg-primary">

                    <?= count($pendaftar) ?> Data Ditampilkan

                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>No</th>
                            <th>No. Pendaftaran</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Program Studi</th>
                            <th>Tahun Akademik</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($pendaftar)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($pendaftar as $row): ?>

                                <tr>

                                    <td><?= $no++ ?></td>

                                    <td class="fw-semibold">
                                        <?= e($row['no_pendaftaran']) ?>
                                    </td>

                                    <td>
                                        <?= e($row['nik']) ?>
                                    </td>

                                    <td>
                                        <?= e($row['nama_lengkap']) ?>
                                    </td>

                                    <td>
                                        <?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                    </td>

                                    <td>
                                        <?= e($row['nama_prodi']) ?>

                                        <span class="badge bg-secondary">
                                            <?= e($row['jenjang']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= e($row['tahun']) ?>
                                    </td>

                                    <td>
                                        <?= e($row['email']) ?>
                                    </td>


                                    <!-- STATUS -->

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


                                    <!-- AKSI -->

                                    <td style="min-width: 240px;">

                                        <div class="d-flex flex-wrap gap-2 mb-2">

                                            <!-- DETAIL -->

                                            <a
                                                href="detail.php?id=<?= (int) $row['id'] ?>"
                                                class="btn btn-sm btn-info text-white">

                                                <i class="bi bi-eye"></i>
                                                Detail

                                            </a>


                                            <!-- DELETE -->

                                            <form
                                                action="delete.php"
                                                method="POST"
                                                onsubmit="return confirm('PERINGATAN! Data <?= e($row['nama_lengkap']) ?> akan dihapus permanen. Lanjutkan?')">

                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= e(csrf_token()) ?>">

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int) $row['id'] ?>">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-danger">

                                                    <i class="bi bi-trash"></i>
                                                    Hapus

                                                </button>

                                            </form>

                                        </div>


                                        <!-- UPDATE STATUS -->

                                        <form
                                            action="update_status.php"
                                            method="POST"
                                            class="d-flex gap-1"
                                            onsubmit="return confirm('Yakin ingin mengubah status pendaftar ini?')">

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= e(csrf_token()) ?>">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $row['id'] ?>">

                                            <select
                                                name="status"
                                                class="form-select form-select-sm"
                                                required>

                                                <option value="Menunggu" <?= $row['status'] === 'Menunggu' ? 'selected' : '' ?>>
                                                    Menunggu
                                                </option>

                                                <option value="Diterima" <?= $row['status'] === 'Diterima' ? 'selected' : '' ?>>
                                                    Diterima
                                                </option>

                                                <option value="Ditolak" <?= $row['status'] === 'Ditolak' ? 'selected' : '' ?>>
                                                    Ditolak
                                                </option>

                                            </select>

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-primary">

                                                <i class="bi bi-save"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                        <?php else: ?>

                            <tr>

                                <td colspan="10" class="text-center py-5">

                                    <i class="bi bi-inbox text-secondary"
                                       style="font-size: 40px;"></i>

                                    <p class="text-secondary mt-2 mb-0">
                                        Data pendaftar tidak ditemukan.
                                    </p>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>


            <p class="text-secondary small mb-0">
                Maksimal 100 data ditampilkan.
            </p>

        </div>

    </div>

</div>


<?php

// Memanggil footer
require_once __DIR__ . '/../../includes/footer.php';

?>
