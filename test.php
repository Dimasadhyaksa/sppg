<?php

echo "START TEST<br><br>";

$path = __DIR__ . "/includes/supabase.php";

echo "LOAD PATH: $path<br><br>";

if (!file_exists(filename: $path)) {
    echo "FILE TIDAK ADA !!!<br>";
    exit;
}

echo "FILE DITEMUKAN, LANJUT LOAD...<br><br>";

require_once $path;

echo "FILE SUDAH LOADED<br><br>";

// =============================
// TEST GET KE SUPABASE
// =============================
echo "RESPON DARI SUPABASE:<br><br>";

$res = supabase_get(endpoint: "menu");
echo "<pre>";
print_r(value: $res);
echo "</pre>";
