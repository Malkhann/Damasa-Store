<?php // login.php
session_start();
include 'config/database.php';

if (isset($_SESSION['user_logged_in'])) { header("Location: index.php"); exit; }

$alert_type = "";
$alert_message = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_avatar'] = $user['avatar'];
            header("Location: index.php?login=success");
            exit;
        } else {
            $alert_type = "error";
            $alert_message = "Password yang anda masukkan salah!";
        }
    } else {
        $alert_type = "error";
        $alert_message = "Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Area</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute top-[-20%] right-[20%] w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-blue-600/20 rounded-full blur-[100px]"></div>

    <div class="w-full max-w-sm bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 p-8 rounded-3xl shadow-2xl relative z-10 transition-all hover:border-slate-600/50">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg shadow-blue-600/30 mx-auto mb-4 transform rotate-3 hover:rotate-6 transition duration-300">
                <i class="ph-bold ph-fingerprint"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Selamat Datang</h1>
            <p class="text-slate-400 text-sm mt-1">Silakan masuk dengan akun Anda.</p>
        </div>

        <form method="post" class="space-y-5">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 ml-1">Username</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-user"></i></div>
                    <input type="text" name="username" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="space-y-1">
                <div class="flex justify-between items-center px-1">
                    <label class="text-xs font-bold text-slate-400">Password</label>
                    <a href="forgot_password.php" class="text-[10px] font-bold text-blue-400 hover:text-white transition">Lupa Password?</a>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-lock-key"></i></div>
                    <input type="password" name="password" id="loginPass" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-12 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="••••••••" required>
                    <button type="button" onclick="togglePass('loginPass', 'iconLogin')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition cursor-pointer">
                        <i id="iconLogin" class="ph-bold ph-eye-slash text-lg"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all transform hover:-translate-y-1 active:scale-95">
                Masuk Sekarang
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-400">
            Belum punya akun? <a href="register.php" class="text-blue-400 font-bold hover:text-blue-300 transition underline decoration-blue-500/30">Daftar</a>
        </p>
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
        <?php if(!empty($alert_type)): ?>
        Swal.fire({ icon: '<?= $alert_type; ?>', title: 'Akses Ditolak', text: '<?= $alert_message; ?>', background: '#0f172a', color: '#fff', confirmButtonColor: '#2563eb' });
        <?php endif; ?>
    </script>
</body>
</html>