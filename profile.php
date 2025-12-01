<?php
session_start();
include 'config/database.php';
// Panggil logic yang sudah dipisah
include 'logic/profile.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        // SCRIPT PENTING: Agar mode tersimpan (Memory) dan tidak kedip putih
        tailwind.config = { darkMode: 'class' };
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-slate-900 dark:bg-slate-950 dark:text-white min-h-screen transition-colors duration-300">
    
    <?php include 'components/navbar.php'; ?>

    <div class="container mx-auto px-4 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-lg relative transition-colors">
                    <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                    <div class="px-6 pb-6 relative">
                        <div class="-mt-12 mb-4 flex justify-center">
                            <img src="<?= $user['avatar']; ?>" class="w-24 h-24 rounded-full border-4 border-white dark:border-slate-900 object-cover bg-gray-200 dark:bg-slate-800 shadow-lg" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name']); ?>&background=random'">
                        </div>
                        <div class="text-center mb-6">
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white"><?= $user['name']; ?></h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm"><?= $user['email']; ?></p>
                            <p class="text-slate-400 dark:text-slate-500 text-xs mt-1"><?= $user['phone'] ? $user['phone'] : '-'; ?></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-slate-950 p-3 rounded-xl border border-gray-200 dark:border-slate-800 text-center">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold">Sukses</p>
                                <p class="text-lg font-bold text-green-600 dark:text-green-400"><?= $total_trx; ?>x</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-slate-950 p-3 rounded-xl border border-gray-200 dark:border-slate-800 text-center">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold">Total</p>
                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">Rp <?= number_format($total_spent/1000); ?>k</p>
                            </div>
                        </div>

                        <button onclick="toggleEdit()" class="w-full py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-bold transition border border-gray-200 dark:border-slate-700">
                            Edit Profil
                        </button>
                    </div>
                </div>

                <div id="editForm" class="hidden bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 shadow-lg transition-colors">
                    <h3 class="font-bold mb-4 text-slate-800 dark:text-white">Update Data</h3>
                    <form method="post" class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="<?= $user['name']; ?>" class="w-full bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-3 text-slate-800 dark:text-white outline-none text-sm focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">No. WhatsApp</label>
                            <input type="text" name="phone" value="<?= $user['phone']; ?>" class="w-full bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-3 text-slate-800 dark:text-white outline-none text-sm focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">Link Avatar (URL)</label>
                            <input type="url" name="avatar" value="<?= $user['avatar']; ?>" class="w-full bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-3 text-slate-800 dark:text-white outline-none text-sm focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ubah" class="w-full bg-gray-50 dark:bg-slate-950 border border-gray-300 dark:border-slate-700 rounded-xl p-3 text-slate-800 dark:text-white outline-none text-sm focus:border-blue-500 transition">
                        </div>
                        <button type="submit" name="update_profile" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg shadow-blue-600/20">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 min-h-[500px] shadow-lg transition-colors">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2 text-slate-800 dark:text-white">
                            <i class="ph-fill ph-clock-counter-clockwise text-blue-600 dark:text-blue-500"></i> Riwayat Transaksi
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-100 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-xs uppercase border-b border-gray-200 dark:border-slate-800">
                                <tr>
                                    <th class="p-4 rounded-tl-xl">Invoice</th>
                                    <th class="p-4">Item</th>
                                    <th class="p-4">Harga</th>
                                    <th class="p-4 text-center">Status</th>
                                    <th class="p-4 text-right rounded-tr-xl">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-slate-800">
                                <?php if(count($history) == 0): ?>
                                    <tr><td colspan="5" class="p-8 text-center text-slate-500 italic">Belum ada transaksi.</td></tr>
                                <?php endif; ?>

                                <?php foreach($history as $trx): 
                                    $status_style = "bg-gray-200 text-slate-600 dark:bg-slate-800 dark:text-slate-400";
                                    if($trx['status']=='success') $status_style = "bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20";
                                    if($trx['status']=='pending') $status_style = "bg-yellow-100 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/20";
                                    if($trx['status']=='failed') $status_style = "bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20";
                                ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition group">
                                    <td class="p-4">
                                        <a href="invoice.php?inv=<?= $trx['invoice_id']; ?>" class="font-mono text-blue-600 dark:text-blue-400 hover:underline font-bold flex items-center gap-1">
                                            #<?= substr($trx['invoice_id'], -5); ?>
                                            <i class="ph-bold ph-arrow-square-out text-xs opacity-0 group-hover:opacity-100 transition"></i>
                                        </a>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800 dark:text-white"><?= $trx['product_name']; ?></div>
                                        <div class="text-xs text-slate-500 mt-0.5 font-mono bg-gray-100 dark:bg-slate-800 px-1.5 py-0.5 rounded w-fit"><?= $trx['game_user_id']; ?></div>
                                    </td>
                                    <td class="p-4 font-mono text-slate-700 dark:text-slate-200">Rp <?= number_format($trx['amount']); ?></td>
                                    <td class="p-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase <?= $status_style; ?>">
                                            <?= $trx['status']; ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-right text-slate-500 text-xs">
                                        <?= date('d/m/Y', strtotime($trx['created_at'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-6 text-right">
                    <a href="profile.php?logout=true" onclick="return confirm('Yakin ingin keluar?')" class="inline-flex items-center gap-2 text-red-600 dark:text-red-400 text-sm font-bold bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 px-4 py-2 rounded-xl transition border border-red-200 dark:border-red-500/20">
                        <i class="ph-bold ph-sign-out"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>

    <script>
        function toggleEdit() {
            const form = document.getElementById('editForm');
            form.classList.toggle('hidden');
        }
    </script>
</body>
</html>