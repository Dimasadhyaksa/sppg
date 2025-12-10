<?php

function status_badge($status): string {
    // pastikan lowercase
    $s = strtolower(string: $status);

    if ($s === 'disetujui' || $s === 'approved') {
        return '<span class="text-green-400 font-semibold">Disetujui</span>';
    }

    if ($s === 'ditolak' || $s === 'rejected') {
        return '<span class="text-red-400 font-semibold">Ditolak</span>';
    }

    if ($s === 'menunggu' || $s === 'pending') {
        return '<span class="text-yellow-400 font-semibold">Menunggu</span>';
    }

    return '<span class="text-gray-300">'.htmlspecialchars(string: $status).'</span>';
}
