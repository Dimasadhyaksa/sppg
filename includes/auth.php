<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika user belum login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header(header: "Location: /index.php");
    exit;
}
