<div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 backdrop-blur-xl absolute left-0 right-0 top-[64px] shadow-2xl transition-all duration-300 origin-top transform scale-y-95 opacity-0">
    <div class="p-4 space-y-2">
        
        <?php if (!$is_logged_in): ?>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <a href="login.php" class="flex items-center justify-center gap-2 bg-gray-100 dark:bg-slate-800 text-slate-800 dark:text-white py-3 rounded-xl font-bold text-sm border border-gray-200 dark:border-slate-700 active:scale-95 transition">
                    <i class="ph-bold ph-sign-in"></i> Masuk
                </a>
                <a href="register.php" class="flex items-center justify-center gap-2 bg-blue-600 text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 active:scale-95 transition">
                    <i class="ph-bold ph-user-plus"></i> Daftar
                </a>
            </div>
        <?php else: ?>
            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-500/30 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow">
                    <?= substr($user_name, 0, 1); ?>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-blue-300">Login sebagai</p>
                    <p class="font-bold text-slate-800 dark:text-white"><?= $_SESSION['user_name']; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2">Menu Utama</p>
        
        <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition <?= isActive('index.php', $current_page); ?> hover:bg-gray-50 dark:hover:bg-slate-800">
            <i class="ph-bold ph-house text-xl"></i> Beranda
        </a>
        
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition text-slate-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-blue-600">
            <i class="ph-bold ph-magnifying-glass text-xl"></i> Cek Pesanan
        </a>

        <?php if ($is_logged_in): ?>
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition <?= isActive('profile.php', $current_page); ?> hover:bg-gray-50 dark:hover:bg-slate-800">
                <i class="ph-bold ph-user-circle text-xl"></i> Profil Saya
            </a>
            <div class="border-t border-gray-100 dark:border-slate-800 my-2"></div>
            <a href="profile.php?logout=true" onclick="return confirm('Keluar dari akun?')" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                <i class="ph-bold ph-sign-out text-xl"></i> Logout
            </a>
        <?php endif; ?>

    </div>
</div>