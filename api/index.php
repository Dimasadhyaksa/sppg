<?php

require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/supabase.php';

// forward request ke routing manual
$path = $_GET['path'] ?? 'index';

$file = __DIR__ . '/../' . $path . '.php';

if (file_exists($file)) {
    include $file;
} else {
    echo "404 Not Found";
}
