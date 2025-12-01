<?php // register.php
session_start();
include 'config/database.php';

if (isset($_SESSION['user_logged_in'])) { header("Location: index.php"); exit; }

if (isset($_POST['register'])) {
    $name  = $_POST['name'];
    $user  = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $avatar = "https://ui-avatars.com/api/?background=random&color=fff&name=" . urlencode($name);

    try {
        $cek = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $cek->execute([$user, $email]);
        if($cek->rowCount() > 0){
            $error = "Username atau Email sudah terpakai!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, phone, avatar) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $user, $email, $pass, $phone, $avatar]);
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location.href='login.php';</script>";
        }
    } catch(PDOException $e) {
        $error = "Terjadi kesalahan sistem.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600/30 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-600/30 rounded-full blur-[100px]"></div>

    <div class="w-full max-w-md bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 p-8 rounded-3xl shadow-2xl relative z-10 transition-all hover:border-slate-600/50">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Buat Akun</h1>
            <p class="text-slate-400 text-sm mt-2">Gabung sekarang dan nikmati fiturnya.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl mb-6 text-center text-sm font-bold flex items-center justify-center gap-2">
                <i class="ph-fill ph-warning-circle text-lg"></i> <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 ml-1">Nama Lengkap</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-user"></i></div>
                    <input type="text" name="name" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="Nama Anda" required>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 ml-1">Username</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-at"></i></div>
                    <input type="text" name="username" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="Username unik" required pattern="^\S+$" title="Tanpa spasi">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-400 ml-1">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-envelope"></i></div>
                        <input type="email" name="email" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="email@contoh.com" required>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-400 ml-1">WhatsApp</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-whatsapp-logo"></i></div>
                        <input type="text" name="phone" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="08..." required>
                    </div>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 ml-1">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-lock-key"></i></div>
                    <input type="password" name="password" id="regPass" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-12 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="••••••••" required>
                    <button type="button" onclick="togglePass('regPass', 'iconReg')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition cursor-pointer">
                        <i id="iconReg" class="ph-bold ph-eye-slash text-lg"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="register" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all transform hover:-translate-y-1 active:scale-95">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-400">
            Sudah punya akun? <a href="login.php" class="text-blue-400 font-bold hover:text-blue-300 transition underline decoration-blue-500/30">Login disini</a>
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
    </script>
</body>
</html>