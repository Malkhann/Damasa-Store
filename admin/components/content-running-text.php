<?php //admin/components/content-running-text.php ?>
<div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 p-6 rounded-2xl">
    <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="ph-bold ph-megaphone-simple"></i> Teks Informasi</h2>
    <form method="post" class="flex gap-3">
        <input type="text" name="running_text" value="<?= $running_text; ?>" class="flex-1 bg-slate-950 border border-slate-700 rounded-xl p-3 text-white text-sm outline-none focus:border-indigo-500">
        <button type="submit" name="update_text" class="bg-slate-800 hover:bg-slate-700 text-white px-5 rounded-xl font-bold text-xs transition border border-slate-700">Update</button>
    </form>
</div>