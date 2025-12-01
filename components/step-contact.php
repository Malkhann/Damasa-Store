<?php //components/step-contact.php ?>
<div class="bg-slate-900/80 backdrop-blur-md rounded-3xl border border-slate-800 p-6 md:p-8 shadow-xl relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-lg">5</div>
        <h2 class="text-xl font-bold">Detail Kontak</h2>
    </div>
    <div class="space-y-2">
        <label class="text-xs font-bold text-slate-400 ml-1">Email Bukti Pembayaran</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition">
                <i class="ph-fill ph-envelope-simple"></i>
            </div>
            <input type="email" name="email" value="<?= $user_email; ?>" <?= $is_readonly; ?> 
                   class="w-full bg-slate-950 border border-slate-700 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition disabled:bg-slate-900 disabled:text-slate-500 disabled:cursor-not-allowed shadow-inner" 
                   placeholder="Masukkan email aktif anda..." required>
        </div>
        <?php if(!empty($user_email)): ?>
            <p class="text-[10px] text-green-400 ml-1 flex items-center gap-1 mt-1"><i class="ph-fill ph-check-circle"></i> Otomatis terisi dari akun login.</p>
        <?php endif; ?>
    </div>
</div>