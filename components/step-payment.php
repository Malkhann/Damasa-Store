<?php //components/step-payment.php ?>
<div class="bg-slate-900/80 backdrop-blur-md rounded-3xl border border-slate-800 p-6 md:p-8 shadow-xl relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-lg">4</div>
        <h2 class="text-xl font-bold">Pilih Pembayaran</h2>
    </div>
    <div class="space-y-6">
        <?php foreach ($payments as $kategori => $list_metode): ?>
            <div>
                <h3 class="text-xs font-bold text-slate-500 mb-3 flex items-center gap-2 uppercase tracking-wider px-1"><?= $kategori; ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php foreach ($list_metode as $pay): ?>
                        <label class="cursor-pointer relative w-full group">
                            <input type="radio" name="payment" value="<?= $pay['name']; ?>" class="hidden peer radio-payment" required>
                            <div class="bg-white w-full rounded-xl p-3.5 flex items-center justify-between border border-slate-200 transition-all hover:border-blue-500 hover:shadow-lg h-[70px] relative overflow-hidden">
                                <div class="h-8 w-28 flex items-center justify-start">
                                    <?php if(!empty($pay['logo']) && file_exists("assets/uploads/payments/" . $pay['logo'])): ?>
                                        <img src="assets/uploads/payments/<?= $pay['logo']; ?>" class="h-full w-full object-contain object-left">
                                    <?php else: ?>
                                        <span class="text-sm font-bold text-slate-800"><?= $pay['name']; ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right z-10">
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition font-mono pay-amount-display">Rp 0</p>
                                    <p class="text-[10px] text-slate-500">Otomatis</p>
                                </div>
                                <div class="payment-check absolute top-0 right-0 bg-blue-600 text-white rounded-bl-xl px-2 py-1 opacity-0 transition transform scale-0 origin-top-right shadow-md"><i class="ph-bold ph-check text-xs"></i></div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>