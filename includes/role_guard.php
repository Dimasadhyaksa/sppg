<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login → kembali ke login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header(header: "Location: /index.php");
    exit;
}

$role = $_SESSION['role'] ?? "";

// Ambil folder saat ini (nama folder setelah root)
$current_folder = basename(path: dirname(path: $_SERVER['SCRIPT_NAME']));

// Rule folder → role
$allowed = [
    'ahli-gizi' => 'ahli-gizi',
    'manajemen' => 'manajemen',
    'akuntansi' => 'akuntansi'
];

// Jika folder memiliki aturan role dan user tidak cocok → blok akses
if (isset($allowed[$current_folder]) && $allowed[$current_folder] !== $role) {

    // Boleh tampilkan pesan atau redirect
    header(header: "Location: /index.php?error=akses-ditolak");
    exit;
}
