<?php
session_start();
include 'config/database.php';
// Panggil Logika dari file terpisah
include 'logic/detail.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up <?= $game['name']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        // SCRIPT PENTING: Mencegah kedipan putih saat load (Memory Dark Mode)
        tailwind.config = { darkMode: 'class' };
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .radio-product:checked + div { border-color: #3b82f6; background-color: rgba(59, 130, 246, 0.1); }
        .radio-payment:checked + div { border-color: #3b82f6; background-color: rgba(59, 130, 246, 0.1); ring: 2px solid #3b82f6; }
        .radio-payment:checked + div .check-icon { opacity: 1; transform: scale(1); }
    </style>
</head>
<body class="bg-gray-50 text-slate-900 dark:bg-slate-950 dark:text-white transition-colors duration-300">
    <?php include 'components/navbar.php'; ?>

    <div class="container mx-auto px-4 py-24">
        <form action="checkout.php" method="POST">
            <input type="hidden" name="game_id" value="<?= $game['id']; ?>">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 sticky top-24 shadow-xl">
                        <div class="relative aspect-square w-32 mx-auto mb-4 rounded-xl overflow-hidden shadow-lg">
                            <img src="<?= $game['thumbnail']; ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/200'">
                        </div>
                        <h1 class="text-2xl font-bold text-center mb-2 text-slate-800 dark:text-white"><?= $game['name']; ?></h1>
                        <div class="text-center mb-4">
                            <span class="bg-blue-100 dark:bg-blue-600/20 text-blue-600 dark:text-blue-400 text-xs px-3 py-1 rounded-full font-bold"><?= $game['category']; ?></span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm text-center leading-relaxed">
                            Layanan Top Up resmi <?= $game['name']; ?> buka 24 Jam Nonstop.
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
                        <h2 class="text-lg font-bold mb-4 flex items-center gap-3 text-slate-800 dark:text-white">
                            <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-blue-600 dark:text-blue-500 border border-slate-200 dark:border-slate-700">1</span>
                            Masukkan User ID
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="user_id" class="w-full bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-4 text-slate-900 dark:text-white outline-none focus:border-blue-500 transition" placeholder="User ID" required>
                            <input type="text" name="zone_id" class="w-full bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-4 text-slate-900 dark:text-white outline-none focus:border-blue-500 transition" placeholder="Zone ID (Opsional)">
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
                        <h2 class="text-lg font-bold mb-4 flex items-center gap-3 text-slate-800 dark:text-white">
                            <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-blue-600 dark:text-blue-500 border border-slate-200 dark:border-slate-700">2</span>
                            Pilih Nominal
                        </h2>
                        
                        <?php if(!empty($promos)): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-red-500 mb-3 flex items-center gap-2"><i class="ph-fill ph-fire"></i> Promo Spesial</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php foreach($promos as $p): ?>
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="product_code" value="<?= $p['id'].'|'.$p['promo_price'].'|'.$p['name']; ?>" class="peer hidden radio-product" required>
                                    <div class="bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-4 h-full flex flex-col justify-between relative overflow-hidden transition-all hover:border-red-500">
                                        <div class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-bl-lg">HEMAT</div>
                                        <span class="font-bold text-sm block mb-1 text-slate-800 dark:text-white"><?= $p['name']; ?></span>
                                        <div>
                                            <span class="text-xs text-slate-400 line-through">Rp <?= number_format($p['price']); ?></span>
                                            <span class="block text-red-500 font-bold font-mono">Rp <?= number_format($p['promo_price']); ?></span>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php foreach($categories as $cat => $items): ?>
                        <div class="mb-6 last:mb-0">
                            <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-3 uppercase tracking-wider"><?= $cat; ?></h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php foreach($items as $p): ?>
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="product_code" value="<?= $p['id'].'|'.$p['price'].'|'.$p['name']; ?>" class="peer hidden radio-product" required>
                                    <div class="bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-4 h-full flex flex-col justify-between transition-all hover:border-blue-500">
                                        <span class="font-bold text-sm block mb-2 text-slate-800 dark:text-white"><?= $p['name']; ?></span>
                                        <span class="block text-blue-600 dark:text-blue-400 font-bold font-mono">Rp <?= number_format($p['price']); ?></span>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold flex items-center gap-3 text-slate-800 dark:text-white">
                                <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-blue-600 dark:text-blue-500 border border-slate-200 dark:border-slate-700">3</span>
                                Masukkan Jumlah
                            </h2>
                            <div class="flex items-center gap-4 bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl px-2 py-1">
                                <button type="button" onclick="updateQty(-1)" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 shadow text-slate-600 dark:text-white flex items-center justify-center transition"><i class="ph-bold ph-minus"></i></button>
                                <span id="displayQty" class="font-mono font-bold w-8 text-center text-slate-800 dark:text-white">1</span>
                                <input type="hidden" name="quantity" id="inputQty" value="1">
                                <button type="button" onclick="updateQty(1)" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-500 text-white flex items-center justify-center transition"><i class="ph-bold ph-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
                        <h2 class="text-lg font-bold mb-6 flex items-center gap-3 text-slate-800 dark:text-white">
                            <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-blue-600 dark:text-blue-500 border border-slate-200 dark:border-slate-700">4</span>
                            Pilih Pembayaran
                        </h2>

                        <div class="space-y-6">
                            <?php foreach($payments as $category => $methods): ?>
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="bg-gray-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold px-3 py-1 rounded-full border border-gray-200 dark:border-slate-700 uppercase tracking-wider"><?= $category; ?></span>
                                    <div class="h-px bg-gray-200 dark:bg-slate-800 flex-1"></div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php foreach($methods as $pay): ?>
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="payment" value="<?= $pay['name']; ?>" class="peer hidden radio-payment" required>
                                        <div class="bg-white dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-4 flex items-center gap-4 transition-all hover:border-slate-400 dark:hover:border-slate-500 group relative overflow-hidden">
                                            
                                            <div class="w-16 h-10 bg-white rounded-lg p-1 flex items-center justify-center flex-shrink-0 border border-gray-100">
                                                <img src="<?= $pay['logo']; ?>" class="max-h-full max-w-full object-contain" alt="<?= $pay['name']; ?>">
                                            </div>
                                            
                                            <div class="flex-1">
                                                <h4 class="font-bold text-sm text-slate-800 dark:text-white"><?= $pay['name']; ?></h4>
                                                <p class="text-xs text-slate-500">Proses Otomatis</p>
                                            </div>

                                            <div class="check-icon absolute top-0 right-0 bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-bl-xl opacity-0 transition-all transform scale-0 origin-top-right">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
                        <h2 class="text-lg font-bold mb-4 flex items-center gap-3 text-slate-800 dark:text-white">
                            <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-blue-600 dark:text-blue-500 border border-slate-200 dark:border-slate-700">5</span>
                            Kontak
                        </h2>
                        <div class="grid grid-cols-1">
                            <input type="email" name="email" value="<?= $user_email; ?>" class="w-full bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-4 text-slate-900 dark:text-white outline-none focus:border-blue-500 transition" placeholder="Email Bukti Pembayaran" required>
                        </div>
                    </div>

                    <button type="submit" name="beli" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 rounded-xl text-lg shadow-lg shadow-blue-600/20 transition-all transform hover:-translate-y-1">
                        Beli Sekarang
                    </button>

                </div>
            </div>
        </form>
    </div>

    <?php include 'components/footer.php'; ?>

    <script>
        const inputQty = document.getElementById('inputQty');
        const displayQty = document.getElementById('displayQty');
        function updateQty(change) {
            let current = parseInt(inputQty.value);
            let next = current + change;
            if(next < 1) next = 1;
            inputQty.value = next;
            displayQty.innerText = next;
        }
    </script>
</body>
</html>