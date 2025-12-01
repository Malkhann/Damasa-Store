<?php //components/step-quantity.php ?>
<div id="section-qty" class="bg-slate-900/80 backdrop-blur-md rounded-3xl border border-slate-800 p-6 md:p-8 shadow-xl relative overflow-hidden transition-all duration-300">
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-lg">3</div>
            <div>
                <h2 class="text-xl font-bold">Atur Jumlah</h2>
                <p id="qty-msg" class="text-xs text-slate-500">Mau beli berapa banyak?</p>
            </div>
        </div>

        <div class="flex items-center gap-4 bg-slate-950 border border-slate-700 rounded-2xl px-3 py-2 shadow-inner">
            <button type="button" onclick="updateQty(-1)" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 text-white flex items-center justify-center transition" id="btnMinus"><i class="ph-bold ph-minus"></i></button>
            <div class="text-center w-12">
                <span id="displayQty" class="font-mono text-xl font-bold text-white">1</span>
                <input type="hidden" name="quantity" id="inputQty" value="1">
            </div>
            <button type="button" onclick="updateQty(1)" class="w-10 h-10 rounded-xl bg-blue-600 hover:bg-blue-500 text-white flex items-center justify-center transition shadow-lg" id="btnPlus"><i class="ph-bold ph-plus"></i></button>
        </div>
    </div>
    
    <div id="promo-alert" class="hidden mt-4 bg-red-500/10 border border-red-500/20 text-red-400 text-xs p-3 rounded-xl flex items-center gap-2">
        <i class="ph-fill ph-warning-circle text-lg"></i> Produk promo hanya bisa dibeli 1 item per transaksi.
    </div>
</div>