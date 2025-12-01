<?php //components/step-identity.php ?>
<div class="bg-slate-900/80 backdrop-blur-md rounded-3xl border border-slate-800 p-6 md:p-8 shadow-xl relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-lg">1</div>
        <h2 class="text-xl font-bold">Data Akun</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-2">
            <label class="text-xs font-bold text-slate-400 ml-1">User ID</label>
            <input type="text" name="user_id" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3.5 text-white focus:border-blue-500 outline-none transition font-mono" placeholder="Contoh: 123456789" required>
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-slate-400 ml-1">Zone ID (Opsional)</label>
            <input type="text" name="zone_id" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3.5 text-white focus:border-blue-500 outline-none transition font-mono" placeholder="Contoh: 2021">
        </div>
    </div>
</div>