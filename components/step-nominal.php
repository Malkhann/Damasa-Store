<?php //components/step-nominal.php ?>
<div class="bg-slate-900/80 backdrop-blur-md rounded-3xl border border-slate-800 p-6 md:p-8 shadow-xl relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
    
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-lg">2</div>
        <h2 class="text-xl font-bold">Pilih Nominal</h2>
    </div>
    
    <?php if (!empty($promos)): ?>
    <div class="mb-8 bg-red-900/10 border border-red-500/20 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-4">
            <div class="bg-red-600 p-1 rounded text-white"><i class="ph-fill ph-fire"></i></div>
            <h3 class="font-bold text-white text-sm uppercase tracking-wider">Promo Spesial</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <?php foreach($promos as $item): ?>
                <label class="cursor-pointer relative group h-full">
                    <input type="radio" name="product_code" data-promo="true" value="<?= $item['id']; ?>|<?= $item['promo_price']; ?>|<?= $item['name']; ?>" class="hidden peer radio-product" required>
                    <div class="bg-slate-950 border border-red-500/30 p-4 rounded-2xl h-full flex flex-col justify-between transition-all hover:border-red-500 relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-bl-lg">HEMAT</div>
                        <div class="mb-2 mt-1"><p class="font-bold text-sm text-white leading-tight"><?= $item['name']; ?></p></div>
                        <div class="mt-auto pt-2 border-t border-red-500/20 w-full">
                            <p class="text-[10px] text-slate-400 line-through">Rp <?= number_format($item['price']); ?></p>
                            <p class="text-base font-bold text-red-400 font-mono">Rp <?= number_format($item['promo_price']); ?></p>
                        </div>
                        <div class="check-icon absolute top-2 left-2 text-red-500 opacity-0 transition transform scale-0 bg-red-500/10 rounded-full p-1"><i class="ph-fill ph-check-circle text-lg"></i></div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="mb-6 border-b border-slate-800 flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
         <?php 
         $first = true; $first_cat = "";
         foreach($categories as $catName => $items): 
            if(empty($items)) continue; 
            if($first) { $first_cat = $catName; $activeClass = "bg-blue-600 text-white shadow-lg"; } else { $activeClass = "bg-slate-800 text-slate-400 hover:text-white"; }
         ?>
         <button type="button" onclick="switchTab('<?= $catName; ?>')" id="tab-<?= $catName; ?>" class="px-4 py-2 text-sm font-bold rounded-lg transition-all whitespace-nowrap <?= $activeClass; ?>"><?= $catName; ?></button>
         <?php $first = false; endforeach; ?>
    </div>

    <?php foreach($categories as $catName => $items): 
        if(empty($items)) continue;
        $display = ($catName == $first_cat) ? "grid" : "hidden";
    ?>
    <div id="content-<?= $catName; ?>" class="product-grid grid-cols-2 md:grid-cols-3 gap-4 transition-all duration-300 <?= $display; ?>">
        <?php foreach($items as $item): 
            if(in_array($item['id'], $promo_ids)) continue; // SKIP JIKA PROMO
        ?>
            <label class="cursor-pointer relative group h-full">
                <input type="radio" name="product_code" data-promo="false" value="<?= $item['id']; ?>|<?= $item['price']; ?>|<?= $item['name']; ?>" class="hidden peer radio-product" required>
                <div class="bg-slate-950 border border-slate-700/50 p-4 rounded-2xl h-full flex flex-col justify-between transition-all hover:border-blue-500 group-hover:-translate-y-1">
                    <div class="mb-2"><p class="font-bold text-sm text-white leading-tight"><?= $item['name']; ?></p></div>
                    <div class="mt-auto pt-3 border-t border-slate-800/50 w-full">
                        <p class="text-base font-bold text-blue-400 font-mono">Rp <?= number_format($item['price']); ?></p>
                    </div>
                    <div class="check-icon absolute top-2 right-2 text-blue-500 opacity-0 transition transform scale-0 bg-blue-500/10 rounded-full p-1"><i class="ph-fill ph-check-circle text-lg"></i></div>
                </div>
            </label>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>

<script>
    function switchTab(selectedCat) {
        document.querySelectorAll('.product-grid').forEach(el => { el.classList.add('hidden'); el.classList.remove('grid'); });
        const target = document.getElementById('content-' + selectedCat);
        if(target) { target.classList.remove('hidden'); target.classList.add('grid'); }
        document.querySelectorAll('[id^="tab-"]').forEach(btn => { btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg'); btn.classList.add('bg-slate-800', 'text-slate-400'); });
        const activeBtn = document.getElementById('tab-' + selectedCat);
        activeBtn.classList.remove('bg-slate-800', 'text-slate-400');
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
    }
</script>