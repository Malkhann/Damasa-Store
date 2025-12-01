<?php
session_start();
include 'config/database.php';
include 'logic/checkout.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-slate-900 dark:bg-slate-950 dark:text-white min-h-screen flex items-center justify-center p-4 transition-colors duration-300">
    
    <div class="w-full max-w-lg bg-white dark:bg-slate-900 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200 dark:border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-slate-800 flex items-center gap-4">
            <button onclick="window.history.back()" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 hover:text-blue-600 text-slate-500 dark:text-slate-400 flex items-center justify-center transition">
                <i class="ph-bold ph-arrow-left"></i>
            </button>
            <h1 class="text-xl font-bold text-slate-800 dark:text-white">Konfirmasi Pesanan</h1>
        </div>

        <div class="p-6">
            <div class="flex items-center gap-4 mb-6 bg-gray-50 dark:bg-slate-950 p-4 rounded-2xl border border-gray-100 dark:border-slate-800">
                <img src="<?= $game['thumbnail']; ?>" class="w-16 h-16 rounded-xl shadow-lg object-cover" onerror="this.src='https://placehold.co/100?text=No+IMG'">
                <div>
                    <h2 class="font-bold text-lg text-slate-800 dark:text-white"><?= $game['name']; ?></h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Top Up Instant</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-slate-800 space-y-3 text-sm shadow-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">ID Player</span> 
                    <span class="font-mono font-bold text-slate-800 dark:text-white select-all"><?= $user_id; ?> <?= $zone_id ? "($zone_id)" : ""; ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Item</span> 
                    <span class="font-bold text-slate-800 dark:text-white"><?= $product_name; ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Metode Bayar</span> 
                    <span class="font-bold text-slate-800 dark:text-white"><?= $payment_name; ?></span>
                </div>
                
                <div class="h-px bg-gray-200 dark:bg-slate-800 my-2 border-t border-dashed border-gray-300 dark:border-slate-700"></div>
                
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400">Total Bayar</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400 font-mono">Rp <?= number_format($total_bayar); ?></span>
                </div>
            </div>

            <form action="process_order.php" method="POST" class="mt-6">
                <input type="hidden" name="game_id" value="<?= $game_id; ?>">
                <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                <input type="hidden" name="zone_id" value="<?= $zone_id; ?>">
                <input type="hidden" name="product_name" value="<?= $product_name; ?>">
                <input type="hidden" name="total_amount" value="<?= $total_bayar; ?>">
                <input type="hidden" name="quantity" value="<?= $quantity; ?>">
                <input type="hidden" name="payment" value="<?= $payment_name; ?>">
                <input type="hidden" name="email" value="<?= $email; ?>">
                
                <button type="submit" name="beli" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 rounded-2xl shadow-lg flex justify-center gap-2 items-center transition transform active:scale-[0.98]">
                    <i class="ph-bold ph-check-circle text-xl"></i> Lanjut Bayar
                </button>
            </form>
        </div>
    </div>
</body>
</html>