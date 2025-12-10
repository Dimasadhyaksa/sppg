<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/supabase.php';

$menu_id = $_GET['menu'] ?? null;

/* =========================================
   AMBIL DATA MENU
========================================= */
$menu_data = [];
if ($menu_id) {
    $get = supabase_get("menu?id=eq.$menu_id&select=*");
    if (is_array($get) && count($get) > 0) {
        $menu_data = $get[0];
    }
}

/* =========================================
   SUBMIT
========================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id        = $_POST['menu_id'];
    $estimasi  = intval($_POST['estimasi']);
    $catatan   = $_POST['keterangan'] ?? '';

    $insert = supabase_insert("estimasi", [
        "menu_id"    => $id,
        "estimasi"   => $estimasi,
        "keterangan" => $catatan
    ]);

    if (isset($insert['error'])) {
        echo "<pre>"; print_r($insert); echo "</pre>";
        die("Gagal insert estimasi.");
    }

    header("Location: /akuntansi/dashboard.php");
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<h2 class="text-3xl font-bold mb-6">Tambah Estimasi Biaya</h2>

<?php if (!$menu_data): ?>
    <div class="bg-red-900 text-red-300 p-4 rounded">
        Menu tidak ditemukan.
    </div>

<?php else: ?>

<div class="bg-[#161616] p-6 rounded-xl border border-gray-800 mb-6">

    <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($menu_data['judul']) ?></h3>
    <p class="text-gray-400 text-sm mb-1">📅 <?= htmlspecialchars($menu_data['tanggal']) ?></p>
    <p class="text-gray-400 text-sm mb-3">Bahan-bahan: <?= htmlspecialchars($menu_data['bahan']) ?></p>

    <form method="POST" class="space-y-4 mt-4">

        <input type="hidden" name="menu_id" value="<?= htmlspecialchars($menu_id) ?>">

        <div>
            <label class="block mb-1 font-semibold">Estimasi Biaya (Rp)</label>
            <input type="number" name="estimasi" class="w-full px-3 py-2 rounded bg-[#111] text-white border border-gray-700" required>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Catatan (opsional)</label>
            <textarea name="keterangan" class="w-full px-3 py-2 rounded bg-[#111] text-white border border-gray-700"></textarea>
        </div>

        <button class="bg-green-600 hover:bg-green-500 px-5 py-2 rounded-lg font-semibold">
            Simpan Estimasi
        </button>

        <a href="/akuntansi/dashboard.php" class="bg-gray-700 hover:bg-gray-600 px-5 py-2 rounded-lg">
            Batal
        </a>

    </form>

</div>

<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
