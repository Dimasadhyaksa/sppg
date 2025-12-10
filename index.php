<?php
session_start();

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header(header: "Location: index.php");
    exit;
}

$error = "";

// LOGIN PROSES
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";

    // Demo akun
    $akun = [
        "sarah@sppg.id" => ["password" => "password123", "role" => "ahli-gizi"],
        "budi@sppg.id"  => ["password" => "password123", "role" => "manajemen"],
        "sari@sppg.id"  => ["password" => "password123", "role" => "akuntansi"]
    ];

    // Validasi
    if (isset($akun[$email]) && $akun[$email]["password"] === $password) {

        $_SESSION["login"] = true;
        $_SESSION["email"] = $email;
        $_SESSION["role"]  = $akun[$email]["role"];

        // Redirect berdasarkan role
        if ($_SESSION["role"] === "ahli-gizi") {
            header("Location: ahli-gizi/dashboard.php");
            exit;
        }

        if ($_SESSION["role"] === "manajemen") {
            header(header: "Location: manajemen/dashboard.php");
            exit;
        }

        if ($_SESSION["role"] === "akuntansi") {
            header(header: "Location: akuntansi/dashboard.php");
            exit;
        }

    } else {
        $error = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem SPPG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0d0d0d] text-white min-h-screen flex flex-col justify-center items-center">

    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold">Sistem SPPG</h1>
        <p class="text-gray-400 mt-2">Sistem Informasi Manajemen SPPG</p>
    </div>

    <div class="w-full max-w-md bg-transparent">

        <?php if (!empty($error)): ?>
            <div class="bg-red-900/40 border border-red-700 text-red-300 p-3 rounded-lg mb-4">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="block mb-2 font-semibold">Email</label>
                <input type="email" name="email"
                       class="w-full p-3 rounded-lg bg-[#dce6ff] text-black focus:outline-none"
                       required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Password</label>
                <input type="password" name="password"
                       class="w-full p-3 rounded-lg bg-[#dce6ff] text-black focus:outline-none"
                       required>
            </div>

            <button class="w-full bg-white text-black font-semibold p-3 rounded-lg mt-2 hover:bg-gray-200">
                Masuk
            </button>
        </form>

        <!-- DEMO AKUN -->
        <div class="bg-[#111] border border-gray-800 p-5 rounded-xl mt-8 text-gray-300">
            <h3 class="font-semibold mb-3">Demo Akun:</h3>

            <p>👩‍⚕️ Ahli Gizi: <span class="text-white">sarah@sppg.id</span></p>
            <p>👨‍💼 Manajemen: <span class="text-white">budi@sppg.id</span></p>
            <p>📊 Akuntansi: <span class="text-white">sari@sppg.id</span></p>

            <p class="mt-2 text-gray-400 text-sm">Password: password123</p>
        </div>

    </div>

</body>
</html>
