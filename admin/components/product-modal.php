<?php //admin/components/product-modal.php ?>
<div id="productModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm transition-all duration-300 modal-enter hidden">
    <div class="bg-slate-900 w-full max-w-2xl rounded-2xl border border-slate-700 shadow-2xl relative overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
            <h2 id="modalTitle" class="text-lg font-bold text-white flex items-center gap-2">
                <i class="ph-bold ph-cube text-blue-500"></i> Tambah Produk
            </h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white transition"><i class="ph-bold ph-x text-xl"></i></button>
        </div>

        <div class="p-6 max-h-[80vh] overflow-y-auto">
            <form method="post" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="save_product" value="true">
                <input type="hidden" name="mode" id="formMode" value="add">
                <input type="hidden" name="id" id="formId" value="">
                <input type="hidden" name="old_thumbnail" id="formOldThumb" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-400 mb-1">Nama Produk</label>
                        <input type="text" name="name" id="inpName" placeholder="Contoh: Mobile Legends" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 focus:outline-none transition" required>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-400 mb-2">Kategori (Pilih Banyak)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <?php 
                            $list_cat = ["Mobile Game", "PC Game", "Console", "Voucher", "Pulsa & Data", "E-Wallet", "Streaming", "App Premium", "Joki"];
                            foreach($list_cat as $cat): 
                            ?>
                            <label class="cursor-pointer select-none">
                                <input type="checkbox" name="categories[]" value="<?= $cat; ?>" class="peer hidden cat-checkbox">
                                <div class="px-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-slate-400 text-xs font-bold text-center transition peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-500 hover:border-slate-500"><?= $cat; ?></div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Status</label>
                        <select name="status" id="inpStatus" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 outline-none">
                            <option value="active">Aktif (Tampil)</option>
                            <option value="inactive">Non-Aktif (Sembunyi)</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-400 mb-1">Cover Image</label>
                        <div class="flex items-center gap-4">
                            <div id="previewContainer" class="w-16 h-16 bg-slate-800 rounded-xl overflow-hidden border border-slate-700 hidden"><img id="imgPreview" src="" class="w-full h-full object-cover"></div>
                            <input type="file" name="thumbnail" id="inpThumb" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-slate-800 file:text-blue-400 hover:file:bg-slate-700 cursor-pointer bg-slate-950 rounded-xl border border-slate-700">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-300 hover:bg-slate-800 transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-blue-600 text-white hover:bg-blue-500 shadow-lg shadow-blue-600/20 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>