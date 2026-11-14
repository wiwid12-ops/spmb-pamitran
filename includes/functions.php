
<?php

/**
 * SPMB STMIK PAMITRAN
 * Helper Functions
 */

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi keamanan output HTML
function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Fungsi redirect
function redirect($url)
{
    header("Location: " . $url);
    exit;
}

// Membuat CSRF Token
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(
            random_bytes(32)
        );
    }

    return $_SESSION['csrf_token'];
}

// Memeriksa CSRF Token
function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals(
            $_SESSION['csrf_token'],
            $token
        );
}
