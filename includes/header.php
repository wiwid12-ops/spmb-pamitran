
<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? APP_NAME;

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= e($page_title) ?> | SPMB Pamitran</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>/assets/css/style.css">

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

    <div class="container">

        <a class="navbar-brand fw-bold"
           href="<?= BASE_URL ?>/">

            <i class="bi bi-mortarboard-fill"></i>
            SPMB PAMITRAN

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="navbarMenu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link"
                       href="<?= BASE_URL ?>/">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="<?= BASE_URL ?>/daftar.php">
                        Pendaftaran
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="<?= BASE_URL ?>/admin/login.php">
                        Login Admin
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<main>
