
<?php

/**
 * SPMB STMIK PAMITRAN
 * Bukti Pendaftaran
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/app.php';

// Periksa session keberhasilan
if (empty($_SESSION['pendaftaran_berhasil'])) {
    redirect(BASE_URL . '/');
}

$data = $_SESSION['pendaftaran_berhasil'];

// Hapus session setelah diambil
unset($_SESSION['pendaftaran_berhasil']);

$page_title = 'Pendaftaran Berhasil';

require_once __DIR__ . '/includes/header.php';

?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm text-center">

                <div class="card-body p-5">

                    <div class="mb-4">

                        <i class="bi bi-check-circle-fill text-success"
                           style="font-size: 75px;">
                        </i>

                    </div>

                    <h2 class="fw-bold text-success">

                        Pendaftaran Berhasil!

                    </h2>

                    <p class="text-secondary mt-3">

                        Terima kasih telah mendaftar sebagai
                        calon mahasiswa STMIK Pamitran.

                    </p>

                    <hr>

                    <p class="mb-2">
                        Nama Calon Mahasiswa
                    </p>

                    <h5 class="fw-bold">

                        <?= e($data['nama_lengkap']) ?>

                    </h5>

                    <p class="mt-4 mb-2">

                        Nomor Pendaftaran

                    </p>

                    <div class="alert alert-primary">

                        <h4 class="fw-bold mb-0">

                            <?= e($data['no_pendaftaran']) ?>

                        </h4>

                    </div>

                    <p class="text-secondary small">

                        Simpan nomor pendaftaran ini
                        sebagai bukti registrasi.

                    </p>

                    <a href="<?= BASE_URL ?>/"
                       class="btn btn-primary mt-3">

                        <i class="bi bi-house"></i>
                        Kembali ke Beranda

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require_once __DIR__ . '/includes/footer.php';

?>
