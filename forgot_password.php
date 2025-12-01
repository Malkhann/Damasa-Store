<?php // forgot_password.php
session_start();
include 'config/database.php';

if (isset($_POST['reset_request'])) {
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $update->execute([$token, $expires, $email]);

        $resetLink = "reset_password.php?token=" . $token;
        
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Tautan Reset Siap!',
                    html: 'Demi keamanan, silakan klik tombol di bawah ini untuk mengatur ulang password Anda:<br><br><a href=\"$resetLink\" style=\"display:inline-block; background:#2563eb; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:bold;\">RESET PASSWORD</a>',
                    background: '#0f172a', 
                    color: '#fff',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });
            }
        </script>";
    } else {
        $error = "Email tidak ditemukan dalam sistem.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px]"></div>

    <div class="w-full max-w-sm bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 p-8 rounded-3xl shadow-2xl relative z-10">
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700 text-yellow-400 text-2xl">
                <i class="ph-fill ph-lock-key-open"></i>
            </div>
            <h1 class="text-xl font-bold text-white">Reset Password</h1>
            <p class="text-slate-400 text-xs mt-2 leading-relaxed">Masukkan email yang terdaftar. Kami akan membantu memulihkan akun Anda.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl mb-6 text-center text-xs font-bold flex items-center justify-center gap-2">
                <i class="ph-fill ph-x-circle text-lg"></i> <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-6">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 ml-1">Email Terdaftar</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition"><i class="ph-bold ph-envelope-simple"></i></div>
                    <input type="email" name="email" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="nama@email.com" required>
                </div>
            </div>
            <button type="submit" name="reset_request" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2 group">
                Kirim Tautan <i class="ph-bold ph-paper-plane-right group-hover:translate-x-1 transition"></i>
            </button>
        </form>

        <div class="mt-8 text-center pt-6 border-t border-slate-800">
            <a href="login.php" class="text-slate-500 hover:text-white transition flex items-center justify-center gap-2 text-sm font-medium">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>