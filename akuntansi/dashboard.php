<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/role_guard.php';
require_once __DIR__ . '/../includes/supabase.php';

/* =========================================
   AMBIL MENU DISETUJUI
========================================= */
$menus_raw = supabase_get(
    "menu?select=*&status=eq.disetujui&order=created_at.asc"
);

if (!is_array($menus_raw)) $menus_raw = [];

/* =========================================
   HILANGKAN DUPLIKAT BERDASARKAN ID
========================================= */
$menus = array_values(array_reduce($menus_raw, function ($acc, $item) {
    if (isset($item['id'])) {
        $acc[$item['id']] = $item;
    }
    return $acc;
}, []));

/* =========================================
   STATISTIK
========================================= */
$total_menu     = count($menus);
$belum_estimasi = 0;
$sudah_estimasi = 0;
$total_estimasi = 0;

/* =========================================
   AMBIL ESTIMASI (LIMIT 1)
========================================= */
foreach ($menus as &$m) {
    $mid = $m["id"];

    $est = supabase_get(
        "estimasi?select=*&menu_id=eq.$mid&order=created_at.desc&limit=1"
    );

    if ($est && is_array($est) && count($est) > 0) {
        $m["estimasi_data"] = $est[0];
        $sudah_estimasi++;
        $total_estimasi += intval($est[0]["estimasi"]);
    } else {
        $m["estimasi_data"] = null;
        $belum_estimasi++;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h2 class="text-3xl font-bold mb-6">Dashboard Akuntansi</h2>

<div class="grid grid-cols-4 gap-6 mb-10">

    <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
        <p class="text-gray-400">Total Menu Disetujui</p>
        <p class="text-4xl font-bold mt-2"><?= $total_menu ?></p>
    </div>

    <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
        <p class="text-gray-400">Belum Ada Estimasi</p>
        <p class="text-4xl font-bold mt-2"><?= $belum_estimasi ?></p>
    </div>

    <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
        <p class="text-gray-400">Sudah Ada Estimasi</p>
        <p class="text-4xl font-bold mt-2"><?= $sudah_estimasi ?></p>
    </div>

    <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
        <p class="text-gray-400">Total Estimasi</p>
        <p class="text-4xl font-bold mt-2">
            Rp<?= number_format($total_estimasi, 0, ',', '.') ?>
        </p>
    </div>

</div>

<div class="bg-[#161616] p-6 rounded-xl border border-gray-800 mb-6">
    <h3 class="text-xl font-bold mb-4">
        Menu Disetujui
        <span class="bg-green-600 text-white px-2 py-1 rounded text-sm"><?= $total_menu ?></span>
    </h3>

    <?php if ($total_menu === 0): ?>
        <p class="text-gray-400">Belum ada menu disetujui.</p>
    <?php endif; ?>

    <?php foreach ($menus as $m): ?>

        <div class="bg-[#111] p-5 rounded-lg border border-gray-800 mt-4">

            <h4 class="text-lg font-bold"><?= htmlspecialchars($m['judul']) ?></h4>
            <p class="text-xs text-gray-500">ID Menu: <?= $m['id'] ?></p>

            <p class="text-gray-400 text-sm mt-1">
                📅 <?= htmlspecialchars($m['tanggal']) ?>
            </p>

            <p class="text-gray-400 text-sm mb-3">
                Bahan-bahan: <?= htmlspecialchars($m['bahan']) ?>
            </p>

            <?php if (!$m['estimasi_data']): ?>

                <a href="/akuntansi/estimasi_add.php?menu=<?= $m['id'] ?>"
                   class="bg-white text-black px-4 py-2 rounded hover:bg-gray-200">
                    + Tambah Estimasi
                </a>

            <?php else: ?>

                <div class="bg-green-900/40 text-green-300 p-4 rounded-lg mt-4 border border-green-800">
                    <p class="font-semibold">Estimasi biaya</p>
                    <p class="text-sm">
                        Rp <?= number_format($m['estimasi_data']['estimasi'], 0, ',', '.') ?>
                    </p>

                    <?php if (!empty($m['estimasi_data']['keterangan'])): ?>
                        <p class="text-xs mt-2 text-green-400 italic">
                            Catatan: <?= htmlspecialchars($m['estimasi_data']['keterangan']) ?>
                        </p>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </div>

    <?php endforeach; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
