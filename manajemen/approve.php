<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/supabase.php';

$id     = $_POST['id']     ?? null;
$aksi   = $_POST['aksi']   ?? null;
$alasan = trim($_POST['alasan'] ?? '');

if (!$id || !$aksi) {
    die("Aksi tidak valid.");
}

// =========================
// SETUJUI
// =========================
if ($aksi === "setuju") {

    // update status menu
    $update = supabase_update("menu", $id, [
        "status" => "disetujui"
    ]);

    if (!$update || isset($update['error'])) {
        echo "<pre>";
        print_r($update);
        echo "</pre>";
        die("Gagal update status ke disetujui.");
    }
}

// =========================
// TOLAK
// =========================
elseif ($aksi === "tolak") {

    // 1) update status menu
    $update = supabase_update("menu", $id, [
        "status" => "ditolak"
    ]);

    if (!$update || isset($update['error'])) {
        echo "<pre>";
        print_r($update);
        echo "</pre>";
        die("Gagal update status ke ditolak.");
    }

    // 2) insert catatan ke tabel catatan_manajemen
    $insertCatatan = supabase_insert("catatan_manajemen", [
        "menu_id"    => $id,
        "aksi"       => $aksi,
        "pesan"      => $alasan !== "" ? $alasan : "Tidak ada alasan diberikan.",
        "created_at" => date("Y-m-d H:i:s")
    ]);

    if (!$insertCatatan || isset($insertCatatan['error'])) {
        echo "<pre>";
        print_r($insertCatatan);
        echo "</pre>";
        die("Gagal menyimpan catatan manajemen.");
    }
}

else {
    die("Aksi tidak dikenali.");
}

// Berhasil → kembali ke dashboard
header("Location: /manajemen/dashboard.php");
exit;
