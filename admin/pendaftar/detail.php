
<?php

/**
 * SPMB STMIK PAMITRAN
 * Detail Calon Mahasiswa
 */

// Proteksi administrator
require_once __DIR__ . '/../auth_check.php';

// Koneksi database
require_once __DIR__ . '/../../config/database.php';

// Mengambil ID dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    redirect(BASE_URL . '/admin/pendaftar/index.php');
}

// Query detail pendaftar
$sql = "SELECT p.*, ps.nama_prodi, ps.kode_prodi, ps.jenjang, ta.tahun FROM pendaftar p INNER JOIN program_studi ps ON p.program_studi_id = ps.id INNER JOIN tahun_akademik ta ON p.tahun_akademik_id = ta.id WHERE p.id = :id LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'id' => $id
]);

$pendaftar = $stmt->fetch();

// Jika data tidak ditemukan
if (!$pendaftar) {
    http_response_code(404);
    exit('Data pendaftar tidak ditemukan.');
}

// Judul halaman
$page_title = 'Detail Pendaftar';

// Memanggil header
require_once __DIR__ . '/../../includes/header.php';

?>

<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">
                <i class="bi bi-person-vcard text-primary"></i>
                Detail Calon Mahasiswa
            </h3>

            <p class="text-secondary mb-0">
                Informasi lengkap calon mahasiswa baru.
            </p>
        </div>

        <a href="index.php" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>


    <!-- IDENTITAS PENDAFTAR -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white p-3">

            <h5 class="mb-0">
                Informasi Pendaftaran
            </h5>

        </div>

        <div class="card-body p-4">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Nomor Pendaftaran</label>
                    <h6 class="fw-bold">
                        <?= e($pendaftar['no_pendaftaran']) ?>
                    </h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Tanggal Pendaftaran</label>
                    <h6>
                        <?= e($pendaftar['created_at']) ?>
                    </h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Status Pendaftaran</label>
                    <div>
                        <?php if ($pendaftar['status'] === 'Menunggu'): ?>
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        <?php elseif ($pendaftar['status'] === 'Diterima'): ?>
                            <span class="badge bg-success">Diterima</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Tahun Akademik</label>
                    <h6>
                        <?= e($pendaftar['tahun']) ?>
                    </h6>
                </div>

            </div>

        </div>

    </div>


    <!-- IDENTITAS PRIBADI -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white p-3">
            <h5 class="fw-bold mb-0">
                A. Identitas Pribadi
            </h5>
        </div>

        <div class="card-body p-4">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">NIK</label>
                    <h6><?= e($pendaftar['nik']) ?></h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Nama Lengkap</label>
                    <h6><?= e($pendaftar['nama_lengkap']) ?></h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Tempat Lahir</label>
                    <h6><?= e($pendaftar['tempat_lahir']) ?></h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Tanggal Lahir</label>
                    <h6><?= e($pendaftar['tanggal_lahir']) ?></h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Jenis Kelamin</label>
                    <h6>
                        <?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                    </h6>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="text-secondary">Alamat</label>
                    <h6><?= nl2br(e($pendaftar['alamat'])) ?></h6>
                </div>

            </div>

        </div>

    </div>


    <!-- INFORMASI KONTAK -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white p-3">
            <h5 class="fw-bold mb-0">
                B. Informasi Kontak
            </h5>
        </div>

        <div class="card-body p-4">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Nomor HP</label>
                    <h6><?= e($pendaftar['no_hp']) ?></h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Email</label>
                    <h6><?= e($pendaftar['email']) ?></h6>
                </div>

            </div>

        </div>

    </div>


    <!-- INFORMASI PENDIDIKAN -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white p-3">
            <h5 class="fw-bold mb-0">
                C. Informasi Pendidikan
            </h5>
        </div>

        <div class="card-body p-4">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Asal Sekolah</label>
                    <h6><?= e($pendaftar['asal_sekolah']) ?></h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Program Studi</label>
                    <h6>
                        <?= e($pendaftar['nama_prodi']) ?>
                        (<?= e($pendaftar['jenjang']) ?>)
                    </h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-secondary">Kode Program Studi</label>
                    <h6><?= e($pendaftar['kode_prodi']) ?></h6>
                </div>

            </div>

        </div>

    </div>


    <!-- TOMBOL KEMBALI -->
    <div class="mb-5">

        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Data Pendaftar
        </a>

    </div>

</div>

<?php

require_once __DIR__ . '/../../includes/footer.php';

?>
