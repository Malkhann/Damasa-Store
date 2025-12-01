<?php
session_start();
include 'config/database.php';
include 'logic/invoice.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $invoice; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-slate-900 dark:bg-slate-950 dark:text-white min-h-screen flex items-center justify-center p-4 transition-colors duration-300">

    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-200 dark:border-slate-800 overflow-hidden relative">
        <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-600 w-full"></div>
        
        <div class="p-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-slate-800 mb-4 shadow-inner">
                     <?php if(!empty($pay_detail['logo'])): ?>
                        <img src="<?= $pay_detail['logo']; ?>" class="h-8 object-contain">
                    <?php else: ?>
                        <i class="ph-fill ph-receipt text-3xl text-slate-400"></i>
                    <?php endif; ?>
                </div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-white">Detail Transaksi</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 font-mono"><?= $invoice; ?></p>
                
                <div class="mt-4 flex justify-center">
                    <span class="px-4 py-1.5 rounded-full border text-xs font-bold flex items-center gap-2 <?= $st[0].' '.$st[1].' '.$st[2]; ?>">
                        <i class="ph-fill <?= $st[3]; ?>"></i> <?= strtoupper($st[4]); ?>
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-2xl p-5 space-y-3 mb-6 relative overflow-hidden">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Item</span>
                    <span class="font-bold text-slate-800 dark:text-white text-right w-1/2 truncate"><?= $trx['product_name']; ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">User ID</span>
                    <span class="font-mono text-slate-800 dark:text-white"><?= $trx['game_user_id']; ?></span>
                </div>
                <div class="h-px bg-gray-200 dark:bg-slate-800 border-t border-dashed border-gray-300 dark:border-slate-700 my-2"></div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400">Total Bayar</span>
                    <span class="text-xl font-bold text-green-500 dark:text-green-400">Rp <?= number_format($trx['amount']); ?></span>
                </div>
            </div>

            <?php if($trx['status'] == 'pending'): ?>
                
                <?php if(!empty($pay_detail['qr_image'])): ?>
                    <div class="bg-white p-4 rounded-2xl text-center mb-6 border border-gray-200 shadow-sm">
                        <p class="text-slate-800 text-xs font-bold mb-2 uppercase tracking-wider">Scan QRIS</p>
                        <img src="<?= $pay_detail['qr_image']; ?>" class="w-48 h-48 mx-auto object-contain rounded-lg">
                    </div>
                <?php else: ?>
                    <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-500/20 rounded-2xl p-5 text-center mb-6">
                        <p class="text-xs text-blue-500 dark:text-blue-400 mb-2">Silakan Transfer ke:</p>
                        <div class="flex items-center justify-center gap-2 mb-2">
                            <span class="font-bold text-slate-800 dark:text-white"><?= $trx['payment_method']; ?></span>
                        </div>
                        <p class="font-mono text-xl font-bold text-slate-800 dark:text-white tracking-wide select-all"><?= $pay_detail['account_number'] ?? '-'; ?></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">a.n <?= $pay_detail['account_holder'] ?? 'Damasa'; ?></p>
                    </div>
                <?php endif; ?>

                <a href="https://wa.me/628123456789?text=Halo%20Admin,%20saya%20sudah%20bayar%20invoice%20<?= $invoice; ?>" target="_blank" class="block w-full bg-green-600 hover:bg-green-500 text-white text-center py-3.5 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-lg shadow-green-600/20">
                    <i class="ph-fill ph-whatsapp-logo text-xl"></i> Konfirmasi Pembayaran
                </a>
            
            <?php else: ?>
                <a href="index.php" class="block w-full bg-slate-800 hover:bg-slate-700 text-white text-center py-3.5 rounded-xl font-bold transition">
                    Buat Pesanan Baru
                </a>
            <?php endif; ?>
            
        </div>
    </div>
</body>
</html>