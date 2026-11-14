
<?php

/**
 * SPMB STMIK PAMITRAN
 * Proteksi Halaman Administrator
 */

require_once __DIR__ . '/../config/app.php';

require_once __DIR__ . '/../includes/functions.php';

// Memeriksa session administrator
if (!isset($_SESSION['admin_id'])) {

    redirect(BASE_URL . '/admin/login.php?error=expired');

}
