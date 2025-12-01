<?php //components/sidebar-game.php ?>
<div class="lg:col-span-4 lg:sticky lg:top-28 h-fit z-10">
    <div class="bg-slate-900/80 backdrop-blur-xl rounded-3xl border border-slate-800 p-6 shadow-2xl relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-blue-600/20 rounded-full blur-3xl"></div>

        <div class="relative w-32 h-32 md:w-full md:h-auto md:aspect-square rounded-2xl overflow-hidden border-2 border-slate-700 shadow-lg mb-5 mx-auto lg:mx-0 group">
            <img src="assets/uploads/games/<?= $game['thumbnail']; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
        </div>
        
        <div class="text-center lg:text-left relative z-10">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2"><?= $game['name']; ?></h1>
            <p class="text-slate-400 text-sm leading-relaxed mb-5">
                Top up resmi <?= $game['name']; ?>. Cukup masukkan ID, pilih item, dan bayar. Otomatis masuk!
            </p>
            
            <div class="flex flex-wrap justify-center lg:justify-start gap-2">
                <div class="px-3 py-1.5 rounded-lg bg-slate-950 border border-slate-800 text-xs font-bold text-green-400 flex items-center gap-1.5">
                    <i class="ph-fill ph-lightning"></i> Proses Instan
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-slate-950 border border-slate-800 text-xs font-bold text-blue-400 flex items-center gap-1.5">
                    <i class="ph-fill ph-shield-check"></i> Resmi 100%
                </div>
            </div>
        </div>
    </div>
</div>