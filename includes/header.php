<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>SPPG Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d0d0d] text-white min-h-screen">
<nav class="w-full bg-black/40 border-b border-gray-800 p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Sistem SPPG</h1>

    <div class="flex items-center gap-4">
        <?php if (!empty($_SESSION['role'])): ?>
        <div class="flex items-center gap-2 bg-gray-900 px-4 py-2 rounded-lg">
            <div class="w-8 h-8 bg-gray-700 rounded-full"></div>
            <div>
                <p class="text-sm font-semibold"><?= htmlspecialchars(string: $_SESSION['role']) ?></p>
                <p class="text-xs text-gray-400 -mt-1">User demo</p>
            </div>
        </div>
        <?php endif; ?>
        <a href="/logout.php" class="text-red-400 hover:text-red-300">Keluar</a>
    </div>
</nav>

<div class="p-6">
