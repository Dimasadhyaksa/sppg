<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/role_guard.php';
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/helpers.php';

/* HANDLE SIMPAN MENU BARU */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'simpan') {

    $judul   = trim($_POST['judul'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $catatan = trim($_POST['catatan'] ?? '');

    $bahan = isset($_POST['bahan']) ? implode(", ", $_POST['bahan']) : "";

    supabase_insert('menu', [
        'judul'      => $judul,
        'tanggal'    => $tanggal,
        'bahan'      => $bahan,
        'status'     => 'menunggu',
        'catatan'    => $catatan,   // CATATAN DARI AHLI GIZI
        'created_at' => date('Y-m-d H:i:s')
    ]);

    header("Location: dashboard.php");
    exit;
}


//    AMBIL MENU + CATATAN MANAJEMEN

$menus = supabase_get("menu?select=*,catatan_manajemen(*)") ?: [];

include __DIR__ . '/../includes/header.php';
?>

<h2 class="text-3xl font-bold mb-6">Dashboard Ahli Gizi</h2>

<!-- Tombol buat menu -->
<?php if (!isset($_GET['add'])): ?>
<div class="flex justify-end mb-6">
    <a href="dashboard.php?add=1"
       class="bg-white text-black font-semibold px-4 py-2 rounded-lg hover:bg-gray-200">
        + Buat Menu Baru
    </a>
</div>
<?php endif; ?>

<!-- FORM TAMBAH MENU -->
<?php if (isset($_GET['add'])): ?>
<div class="bg-[#161616] p-6 rounded-xl border border-gray-800 mb-10">

    <h3 class="text-2xl font-bold mb-6">Buat Menu Baru</h3>

    <form action="dashboard.php" method="POST" class="space-y-4">
        <input type="hidden" name="aksi" value="simpan">

        <div>
            <label class="font-semibold block mb-1">Nama Menu</label>
            <input type="text" name="judul" required
                   class="w-full p-3 rounded-lg bg-[#111] border border-gray-700 text-white">
        </div>

        <div>
            <label class="font-semibold block mb-1">Tanggal Jadwal</label>
            <input type="date" name="tanggal" required
                   class="w-full p-3 rounded-lg bg-[#111] border border-gray-700 text-white">
        </div>

        <div>
            <label class="font-semibold block mb-3">Bahan-Bahan</label>
            <div class="grid grid-cols-3 gap-4 max-h-64 overflow-y-auto pr-2">

                <?php  
                $bahanList = [
                    "Ayam","Brokoli","Ikan Dori","Nasi","Tahu","Tempe",
                    "Telur","Kentang","Cabai","Bawang Merah","Bawang Putih",
                    "Garam","Minyak Goreng","Sayur Bayam","Wortel"
                ];

                foreach ($bahanList as $bahan): ?>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="bahan[]" value="<?= $bahan ?>" class="h-4 w-4">
                        <span><?= $bahan ?></span>
                    </label>
                <?php endforeach; ?>

            </div>
        </div>

        <div>
            <label class="font-semibold block mb-1">Catatan (opsional)</label>
            <textarea name="catatan" rows="3" placeholder="Tambahan Bahan dari Ahli Gizi"
                      class="w-full p-3 rounded-lg bg-[#111] border border-gray-700 text-white"></textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit"
                    class="bg-green-600 px-5 py-3 rounded-lg font-semibold hover:bg-green-500">
                Simpan Menu
            </button>

            <a href="dashboard.php"
               class="bg-gray-700 px-5 py-3 rounded-lg hover:bg-gray-600">
               Batal
            </a>
        </div>

    </form>
</div>
<?php endif; ?>

<!-- LIST MENU -->
<div class="space-y-6">

    <?php if (empty($menus)): ?>
        <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
            Belum ada menu.
        </div>
    <?php endif; ?>

    <?php foreach ($menus as $m): ?>
        <div class="bg-[#161616] rounded-xl p-6 border border-gray-800">

            <div class="flex justify-between items-start">

                <div>
                    <h3 class="text-xl font-bold"><?= htmlspecialchars($m['judul']) ?></h3>

                    <div class="text-gray-400 mt-1 text-sm flex items-center gap-3">
                        <span>📅 <?= htmlspecialchars($m['tanggal']) ?></span>
                        <span>•</span>
                        <span><?= htmlspecialchars($m['bahan']) ?></span>
                    </div>
                </div>

                <div class="text-right">
                    <?= status_badge($m['status']) ?>
                </div>

            </div>

            <!-- === CATATAN AHLI GIZI (SELALU ADA JIKA DIISI) === -->
<?php if (!empty($m['catatan'])): ?>
    <div class="bg-yellow-900/40 text-yellow-300 p-4 rounded-lg mt-4 border border-yellow-800">
        <p class="font-semibold">Catatan Bahan Ahli Gizi:</p>
        <p class="text-sm"><?= htmlspecialchars($m['catatan']) ?></p>
    </div>
<?php endif; ?>


<!-- === ALASAN PENOLAKAN DARI MANAJEMEN === -->
<?php if ($m['status'] === 'ditolak' && !empty($m['catatan_manajemen'])): ?>
    <div class="bg-red-900/40 text-red-300 p-4 rounded-lg mt-4 border border-red-700">
        <p class="font-semibold">Alasan Penolakan Manajemen:</p>
        <p class="text-sm">
            <?= htmlspecialchars($m['catatan_manajemen'][0]['pesan']) ?>
        </p>
    </div>
<?php endif; ?>


        </div>
    <?php endforeach; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
