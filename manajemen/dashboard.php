<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/role_guard.php';
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/helpers.php';

// ============================
// AMBIL DATA MENU
// ============================
$menus_menunggu = supabase_get("menu?status=eq.menunggu") ?: [];
$menus_setuju   = supabase_get("menu?status=eq.disetujui") ?: [];
$menus_tolak    = supabase_get("menu?status=eq.ditolak")  ?: [];

// Statistik
$stat_menunggu = count($menus_menunggu);
$stat_setuju   = count($menus_setuju);
$stat_tolak    = count($menus_tolak);

// Mode tindak lanjut
$tindak_id = $_GET['tindak'] ?? null;

include __DIR__ . '/../includes/header.php';
?>

<h2 class="text-3xl font-bold mb-6">Dashboard Manajemen</h2>

<!-- ====================================================== -->
<!--                       STATISTIK                        -->
<!-- ====================================================== -->
<div class="grid grid-cols-3 gap-6 mb-10">

    <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
        <p class="text-gray-400">Menunggu Persetujuan</p>
        <p class="text-4xl font-bold mt-2"><?= $stat_menunggu ?></p>
    </div>

    <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
        <p class="text-gray-400">Disetujui</p>
        <p class="text-4xl font-bold mt-2"><?= $stat_setuju ?></p>
    </div>

    <div class="bg-[#161616] p-6 rounded-xl border border-gray-800">
        <p class="text-gray-400">Ditolak</p>
        <p class="text-4xl font-bold mt-2"><?= $stat_tolak ?></p>
    </div>

</div>

<!-- ====================================================== -->
<!--              LIST MENU MENUNGGU                        -->
<!-- ====================================================== -->
<div class="bg-[#161616] p-6 rounded-xl border border-gray-800 mb-6">
    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
        Menu Menunggu Persetujuan 
        <span class="bg-yellow-500 text-black px-2 py-1 rounded text-sm"><?= $stat_menunggu ?></span>
    </h3>

    <?php if (empty($menus_menunggu)): ?>
        <p class="text-gray-500">Tidak ada menu yang menunggu persetujuan.</p>
    <?php endif; ?>

    <?php foreach ($menus_menunggu as $m): ?>
        <div class="bg-[#111] p-5 rounded-lg border border-gray-800 mt-4">

            <h4 class="text-lg font-bold"><?= htmlspecialchars($m['judul']) ?></h4>

            <p class="text-gray-400 text-sm mt-1 flex items-center gap-3">
                <span>📅 Jadwal: <?= htmlspecialchars($m['tanggal']) ?></span>
                <span>•</span>
                <span>👤 Dibuat oleh: <?= htmlspecialchars($m['dibuat_oleh'] ?? 'Tidak diketahui') ?></span>
            </p>

            <p class="text-gray-400 text-sm">
                Bahan-bahan: <?= htmlspecialchars($m['bahan']) ?>
            </p>

            <!-- ========================================== -->
            <!-- CATATAN AHLI GIZI (BARU DITAMBAHKAN) -->
            <!-- ========================================== -->
            <?php if (!empty($m['catatan'])): ?>
                <div class="bg-blue-900/40 text-blue-300 p-4 rounded-lg mt-4 border border-blue-700">
                    <p class="font-semibold">Catatan Ahli Gizi:</p>
                    <p class="text-sm"><?= htmlspecialchars($m['catatan']) ?></p>
                </div>
            <?php endif; ?>

            <!-- ============================ -->
            <!-- MODE TINDAK LANJUT -->
            <!-- ============================ -->
            <?php if ($tindak_id === $m['id']): ?>

    <form action="/manajemen/approve.php" method="POST" class="mt-4 flex gap-3 w-full">
        
        <input type="hidden" name="id" value="<?= $m['id'] ?>">

        <!-- Tombol Setujui -->
        <button type="submit" name="aksi" value="setuju"
            class="bg-green-600 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-green-700">
            ✔ Setujui
        </button>

        <!-- Input untuk alasan -->
        <input type="text" 
               name="alasan"
               id="alasan_<?= $m['id'] ?>"
               placeholder="Berikan alasan penolakan..."
               class="flex-1 bg-[#222] border border-gray-700 text-white px-3 py-2 rounded"
               oninput="toggleTolakButton('<?= $m['id'] ?>')">

        <!-- Tombol Tolak (Disabled default) -->
        <button type="submit"
                id="btn_tolak_<?= $m['id'] ?>"
                name="aksi"
                value="tolak"
                class="bg-red-600 px-4 py-2 rounded-lg opacity-50 cursor-not-allowed"
                disabled>
            ✖ Tolak
        </button>

        <a href="dashboard.php" 
           class="bg-gray-700 px-4 py-2 rounded-lg hover:bg-gray-600">
           Batal
        </a>

    </form>

    <script>
        function toggleTolakButton(id) {
            const input = document.getElementById("alasan_" + id);
            const btn   = document.getElementById("btn_tolak_" + id);

            if (input.value.trim().length > 0) {
                btn.disabled = false;
                btn.classList.remove("opacity-50", "cursor-not-allowed");
            } else {
                btn.disabled = true;
                btn.classList.add("opacity-50", "cursor-not-allowed");
            }
        }
    </script>

<?php else: ?>

                <div class="mt-4">
                    <a href="dashboard.php?tindak=<?= $m['id'] ?>"
                       class="bg-teal-600 px-4 py-2 rounded-lg hover:bg-teal-700">
                        🗨 Tindak Lanjut
                    </a>
                </div>

            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
