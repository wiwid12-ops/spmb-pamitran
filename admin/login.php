
<?php

/**
 * SPMB STMIK PAMITRAN
 * Login Administrator
 */

// Konfigurasi aplikasi
require_once __DIR__ . '/../config/app.php';

// Helper functions
require_once __DIR__ . '/../includes/functions.php';

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['admin_id'])) {
    redirect(BASE_URL . '/admin/dashboard.php');
}

// Judul halaman
$page_title = 'Login Administrator';

// Pesan kesalahan
$error = '';

if (isset($_GET['error']) && $_GET['error'] === 'invalid') {
    $error = 'Email atau password yang Anda masukkan salah.';
}

if (isset($_GET['error']) && $_GET['error'] === 'expired') {
    $error = 'Sesi login berakhir. Silakan login kembali.';
}

// Memanggil header
require_once __DIR__ . '/../includes/header.php';

?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="card shadow border-0">

                <div class="card-body p-4 p-md-5">

                    <!-- LOGO -->
                    <div class="text-center mb-4">

                        <i class="bi bi-shield-lock-fill text-primary"
                           style="font-size: 55px;"></i>

                        <h3 class="fw-bold mt-3">
                            Administrator
                        </h3>

                        <p class="text-secondary">
                            Silakan login untuk mengakses dashboard.
                        </p>

                    </div>

                    <!-- PESAN ERROR -->
                    <?php if ($error !== ''): ?>

                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            <?= e($error) ?>
                        </div>

                    <?php endif; ?>

                    <!-- FORM LOGIN -->
                    <form action="auth.php" method="POST">

                        <!-- CSRF TOKEN -->
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= e(csrf_token()) ?>">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email Administrator
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Masukkan email"
                                    maxlength="100"
                                    required>

                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Password
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Masukkan password"
                                    required>

                            </div>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="bi bi-box-arrow-in-right"></i>

                            Masuk Administrator

                        </button>

                    </form>

                    <div class="text-center mt-4">

                        <a href="<?= BASE_URL ?>/"
                           class="text-decoration-none">

                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Beranda

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require_once __DIR__ . '/../includes/footer.php';

?>
