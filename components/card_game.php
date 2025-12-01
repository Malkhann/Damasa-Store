<?php //components/card_game.php ?>
<a href="detail.php?slug=<?= $game['slug']; ?>" class="group relative flex flex-col h-full">
    <div class="relative aspect-square overflow-hidden rounded-2xl bg-gray-200 dark:bg-slate-800 shadow-sm hover:shadow-xl dark:shadow-none transition-all duration-300 group-hover:-translate-y-1 ring-1 ring-black/5 dark:ring-white/5">
        
        <img src="<?= $game['thumbnail']; ?>" 
             alt="<?= $game['name']; ?>" 
             class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
             loading="lazy"
             onerror="this.src='https://placehold.co/400x400?text=No+Image'">
        
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-4 translate-y-2 group-hover:translate-y-0 transition duration-300 flex justify-end">
            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg opacity-0 group-hover:opacity-100 transition duration-300 scale-0 group-hover:scale-100">
                <i class="ph-fill ph-caret-right"></i>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition line-clamp-1">
            <?= $game['name']; ?>
        </h4>
        <p class="text-[10px] text-slate-500 dark:text-slate-500 group-hover:text-slate-700 dark:group-hover:text-slate-400 transition truncate">
            <?= explode(',', $game['category'])[0]; ?>
        </p>
    </div>
</a>