<?php // reset_password.php
session_start();
include 'config/database.php';

if (!isset($_GET['token'])) { header("Location: login.php"); exit; }

$token = $_GET['token'];
$now = date("Y-m-d H:i:s");

$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > ?");
$stmt->execute([$token, $now]);
$user = $stmt->fetch();

if (!$user) {
    die("<div style='background:#0f172a; height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center; color:white; font-family:sans-serif;'><h2 style='font-size:2rem; margin-bottom:10px;'>Link Kadaluarsa</h2><p style='color:#94a3b8; margin-bottom:20px;'>Tautan ini sudah tidak berlaku.</p><a href='login.php' style='background:#2563eb; padding:10px 20px; border-radius:8px; text-decoration:none; color:white; font-weight:bold;'>Kembali Login</a></div>");
}

if (isset($_POST['change_password'])) {
    $pass1 = $_POST['new_password'];
    $pass2 = $_POST['confirm_password'];

    if ($pass1 !== $pass2) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $new_pass = password_hash($pass1, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $update->execute([$new_pass, $user['id']]);
        echo "<script>alert('Sukses! Password telah diperbarui.'); window.location.href='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/40 via-slate-950 to-slate-950"></div>

    <div class="w-full max-w-sm bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 p-8 rounded-3xl shadow-2xl relative z-10">
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold text-white">Password Baru</h1>
            <p class="text-slate-400 text-xs mt-2">Buat password baru untuk akun <b><?= explode(' ', $user['name'])[0]; ?></b>.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl mb-6 text-center text-xs font-bold flex items-center justify-center gap-2">
                <i class="ph-fill ph-warning"></i> <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 ml-1">Password Baru</label>
                <div class="relative group">
                    <input type="password" name="new_password" id="newPass" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-4 pr-12 text-white outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition" placeholder="Minimal 6 karakter" required minlength="6">
                    <button type="button" onclick="togglePass('newPass', 'iconNew')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition">
                        <i id="iconNew" class="ph-bold ph-eye-slash text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 ml-1">Konfirmasi Password</label>
                <div class="relative group">
                    <input type="password" name="confirm_password" id="confPass" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-4 pr-12 text-white outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition" placeholder="Ulangi password" required>
                    <button type="button" onclick="togglePass('confPass', 'iconConf')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition">
                        <i id="iconConf" class="ph-bold ph-eye-slash text-lg"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="change_password" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-green-600/20 mt-2">
                Simpan Password Baru
            </button>
        </form>
    </div>

    <script>
        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("ph-eye-slash", "ph-eye");
            } else {
                input.type = "password";
                icon.classList.replace("ph-eye", "ph-eye-slash");
            }
        }
    </script>
</body>
</html>