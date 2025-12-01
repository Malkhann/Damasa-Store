<div class="max-w-screen-xl mx-auto px-4 md:px-8 py-3">
    <div class="flex justify-between items-center">
        
        <a href="index.php" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                <i class="ph-fill ph-game-controller text-2xl"></i>
            </div>
            <div class="leading-tight">
                <span class="block text-xl font-bold tracking-tight text-slate-800 dark:text-white transition-colors">
                    Damasa<span class="text-blue-600 dark:text-blue-500">Store</span>
                </span>
                <span class="block text-[10px] font-medium text-slate-500 dark:text-slate-400 tracking-wider">OFFICIAL TOP UP</span>
            </div>
        </a>

        <div class="flex items-center gap-3">
            
            <button id="theme-toggle" class="relative w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-800 text-slate-600 dark:text-yellow-400 flex items-center justify-center transition-all hover:bg-gray-200 dark:hover:bg-slate-700 active:scale-90 overflow-hidden">
                <i id="theme-icon" class="ph-fill ph-sun text-xl transition-transform duration-500 rotate-0"></i>
            </button>

            <?php if ($is_logged_in): ?>
                <a href="profile.php" class="flex items-center gap-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 border border-gray-200 dark:border-slate-700 pl-1.5 pr-4 py-1.5 rounded-full transition-all group active:scale-95">
                    <img src="<?= $user_avatar; ?>" class="w-8 h-8 rounded-full object-cover border-2 border-white dark:border-slate-600 group-hover:border-blue-500 transition-colors" onerror="this.src='https://ui-avatars.com/api/?name=User'">
                    <div class="text-left hidden sm:block">
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-none">Halo,</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate max-w-[80px] group-hover:text-blue-500 transition-colors">
                            <?= $user_name; ?>
                        </p>
                    </div>
                </a>
            <?php else: ?>
                <div class="hidden md:flex items-center gap-2">
                    <a href="login.php" class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white transition px-4 py-2">Masuk</a>
                    <a href="register.php" class="relative overflow-hidden group bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-600/20 active:scale-95">
                        <span class="relative z-10">Daftar</span>
                        <div class="absolute inset-0 h-full w-full scale-0 rounded-xl transition-all duration-300 group-hover:scale-100 group-hover:bg-blue-700/50"></div>
                    </a>
                </div>
            <?php endif; ?>

            <button id="mobile-menu-btn" class="md:hidden w-10 h-10 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition active:scale-90 border border-transparent dark:border-slate-700">
                <i id="hamburger-icon" class="ph-bold ph-list text-2xl transition-transform duration-300"></i>
            </button>
        </div>
    </div>
</div>