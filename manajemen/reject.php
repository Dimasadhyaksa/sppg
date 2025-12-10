<?php
// require_once __DIR__ . '/../includes/auth.php';
// require_once __DIR__ . '/../includes/supabase.php';

// Ambil data dari POST
// $id     = $_POST['id']     ?? null;
// $alasan = trim($_POST['alasan'] ?? '');

// Validasi
// if (!$id) {
//     die("ID menu tidak valid.");
// }

// $data = [
//     'status'  => 'ditolak',
//     'catatan' => $alasan !== "" ? $alasan : "Tidak ada alasan diberikan."
// ];

// Update ke Supabase
// $res = supabase_update("menu", $id, $data);

// Jika gagal update
// if (!$res || isset($res['error'])) {
//     echo "<pre>";
//     print_r($res);
//     echo "</pre>";
//     die("Gagal update status pada Supabase.");
// }

// Redirect kembali
// header("Location: /manajemen/dashboard.php");
// exit;
