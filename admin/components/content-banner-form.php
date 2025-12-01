<?php //admin/components/content-banner-form.php ?>
<div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 p-6 rounded-2xl relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
    <h2 class="text-xl font-bold mb-6 flex items-center gap-2"><i class="ph-bold ph-images-square"></i> Tambah Banner</h2>
    
    <form method="post" enctype="multipart/form-data" id="bannerForm">
        <input type="hidden" name="save_banner" value="true">
        
        <div class="flex gap-2 mb-6 p-1 bg-slate-950 rounded-xl w-fit border border-slate-800">
            <label class="cursor-pointer">
                <input type="radio" name="upload_type" value="file" class="peer hidden" checked onchange="toggleUploadType('file')">
                <span class="px-4 py-2 rounded-lg text-xs font-bold block transition peer-checked:bg-indigo-600 peer-checked:text-white text-slate-400 hover:text-white"><i class="ph-bold ph-upload-simple"></i> Upload File</span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="upload_type" value="url" class="peer hidden" onchange="toggleUploadType('url')">
                <span class="px-4 py-2 rounded-lg text-xs font-bold block transition peer-checked:bg-indigo-600 peer-checked:text-white text-slate-400 hover:text-white"><i class="ph-bold ph-link"></i> Gunakan Link</span>
            </label>
        </div>

        <div id="area-file" class="mb-6">
            <label class="relative w-full h-48 border-2 border-dashed border-slate-700 hover:border-indigo-500 hover:bg-slate-800/50 rounded-2xl flex flex-col items-center justify-center cursor-pointer transition group bg-slate-950/50" id="drop-zone">
                <input type="file" name="banner_files[]" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" multiple accept="image/*">
                <div id="drop-content" class="pointer-events-none flex flex-col items-center text-center p-4">
                    <i class="ph-bold ph-cloud-arrow-up text-4xl text-slate-500 group-hover:text-indigo-400 mb-3 transition"></i>
                    <span class="text-sm font-bold text-slate-300 group-hover:text-white">Klik / Seret Gambar ke sini</span>
                    <span class="text-[10px] text-slate-500 mt-1">Mendukung banyak file sekaligus</span>
                </div>
                <div id="file-preview" class="hidden flex-wrap gap-2 justify-center p-2 pointer-events-none"></div>
            </label>
        </div>

        <div id="area-url" class="mb-6 hidden">
            <label class="text-xs text-slate-400 mb-1 block">URL Gambar</label>
            <div class="flex items-center gap-2 bg-slate-950 border border-slate-700 rounded-xl p-3">
                <i class="ph-bold ph-link text-slate-500"></i>
                <input type="url" name="banner_url" placeholder="https://..." class="w-full bg-transparent text-white text-sm outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div><label class="text-xs text-slate-400 mb-1 block">Judul (Opsional)</label><input type="text" name="title" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-white text-sm outline-none focus:border-indigo-500"></div>
            <div><label class="text-xs text-slate-400 mb-1 block">Sub-Judul (Opsional)</label><input type="text" name="subtitle" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-white text-sm outline-none focus:border-indigo-500"></div>
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl shadow-lg transition">Simpan Banner</button>
    </form>
</div>