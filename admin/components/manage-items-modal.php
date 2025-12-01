<?php //admin/components/manage-items-modal.php ?>
<div id="itemsModal" class="fixed inset-0 z-[110] flex items-center justify-center bg-black/90 backdrop-blur-sm transition-all duration-300 modal-enter hidden">
    <div class="bg-slate-900 w-full max-w-4xl h-[90vh] rounded-2xl border border-slate-700 shadow-2xl relative flex flex-col overflow-hidden">
        
        <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-950">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="ph-bold ph-list-dashes text-yellow-500"></i> Kelola Daftar Harga
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Game: <span id="manageGameName" class="text-white font-bold">Loading...</span></p>
            </div>
            <button onclick="closeItemsModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition"><i class="ph-bold ph-x"></i></button>
        </div>

        <div class="flex flex-col md:flex-row h-full overflow-hidden">
            
            <div class="w-full md:w-64 bg-slate-950/50 border-r border-slate-800 p-4 flex flex-col gap-2">
                <button onclick="switchItemTab('list')" id="tab-btn-list" class="text-left px-4 py-3 rounded-xl text-sm font-bold transition flex items-center gap-3 bg-blue-600 text-white shadow-lg">
                    <i class="ph-bold ph-table"></i> Daftar Item
                </button>
                <button onclick="switchItemTab('add')" id="tab-btn-add" class="text-left px-4 py-3 rounded-xl text-sm font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition flex items-center gap-3">
                    <i class="ph-bold ph-plus"></i> Tambah Manual
                </button>
                <button onclick="switchItemTab('import')" id="tab-btn-import" class="text-left px-4 py-3 rounded-xl text-sm font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition flex items-center gap-3">
                    <i class="ph-bold ph-file-csv"></i> Import CSV
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 bg-slate-900 relative">
                
                <div id="tab-content-list" class="space-y-4">
                    <div id="loadingItems" class="text-center py-10 text-slate-500 hidden">
                        <i class="ph-bold ph-spinner animate-spin text-2xl"></i><br>Memuat data...
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-slate-800">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="p-4">Nama Item</th>
                                    <th class="p-4">Kategori</th>
                                    <th class="p-4">Harga</th>
                                    <th class="p-4">Promo</th>
                                    <th class="p-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody" class="divide-y divide-slate-800 bg-slate-900/50"></tbody>
                        </table>
                    </div>
                </div>

                <div id="tab-content-add" class="hidden max-w-xl mx-auto">
                    <h3 class="text-xl font-bold text-white mb-6"><span id="formTitle">Tambah Item</span></h3>
                    <form method="post" class="space-y-5">
                        <input type="hidden" name="save_item" value="true">
                        <input type="hidden" name="game_id" id="inputGameId">
                        <input type="hidden" name="product_id" id="inputProductId">
                        <div><label class="text-xs text-slate-400 font-bold">Nama</label><input type="text" name="item_name" id="inputItemName" class="w-full bg-slate-950 border border-slate-700 p-3 rounded-xl text-white outline-none focus:border-blue-500" required></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="text-xs text-slate-400 font-bold">Harga</label><input type="number" name="item_price" id="inputItemPrice" class="w-full bg-slate-950 border border-slate-700 p-3 rounded-xl text-white outline-none focus:border-blue-500" required></div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold">Kategori</label>
                                <select name="item_category" id="inputItemCat" class="w-full bg-slate-950 border border-slate-700 p-3 rounded-xl text-white outline-none focus:border-blue-500">
                                    <option value="Diamonds">💎 Diamonds</option>
                                    <option value="Membership">👑 Membership</option>
                                    <option value="Bundles">🎫 Bundles</option>
                                    <option value="Voucher">🎟 Voucher</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-blue-900/10 border border-blue-500/20 space-y-4">
                            <h4 class="text-sm font-bold text-blue-400 flex gap-2"><i class="ph-fill ph-tag"></i> Promo (Opsional)</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="text-xs text-slate-400 font-bold">Harga Promo</label><input type="number" name="promo_price" id="inputPromoPrice" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white outline-none focus:border-blue-500"></div>
                                <div><label class="text-xs text-slate-400 font-bold">Berakhir</label><input type="datetime-local" name="promo_date" id="inputPromoDate" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white outline-none focus:border-blue-500 text-xs"></div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-4">
                            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-blue-600 text-white hover:bg-blue-500 shadow-lg">Simpan</button>
                        </div>
                    </form>
                </div>

                <div id="tab-content-import" class="hidden h-full flex flex-col items-center justify-center">
                    <div class="w-full max-w-md text-center">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-green-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-green-500 border border-green-500/20">
                                <i class="ph-fill ph-microsoft-excel-logo text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Import Masal CSV</h3>
                            <p class="text-slate-400 text-sm mt-2">Pastikan format kolom: Nama, Harga, Kategori.</p>
                        </div>

                        <form method="post" enctype="multipart/form-data" class="space-y-4" id="importForm">
                            <input type="hidden" name="import_csv" value="true">
                            <input type="hidden" name="game_id" id="importGameId">

                            <div id="drop-zone" class="relative w-full h-40 border-2 border-dashed border-slate-700 hover:border-green-500 hover:bg-slate-800/50 rounded-2xl flex flex-col items-center justify-center cursor-pointer transition group bg-slate-950">
                                <input type="file" name="csv_file" id="csvInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".csv" required>
                                
                                <div id="drop-content" class="pointer-events-none flex flex-col items-center transition-all duration-300">
                                    <i class="ph-bold ph-upload-simple text-3xl text-slate-500 group-hover:text-green-400 mb-3 transition"></i>
                                    <span class="text-sm font-bold text-slate-300 group-hover:text-white">Klik atau Seret file CSV ke sini</span>
                                    <span class="text-xs text-slate-500 mt-1">Maksimal 2MB</span>
                                </div>

                                <div id="file-info" class="hidden pointer-events-none flex-col items-center animate-fade-in">
                                    <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white mb-2 shadow-lg">
                                        <i class="ph-bold ph-file-csv text-xl"></i>
                                    </div>
                                    <span id="filename-display" class="text-sm font-bold text-white truncate max-w-[200px]">filename.csv</span>
                                    <span class="text-xs text-green-400 mt-1">File Siap Diupload!</span>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a href="index.php?action=download_template" class="flex-1 py-3 rounded-xl bg-slate-800 text-slate-300 font-bold text-sm hover:text-white transition flex items-center justify-center gap-2 border border-slate-700">
                                    <i class="ph-bold ph-download-simple"></i> Template
                                </a>
                                <button type="submit" class="flex-1 py-3 rounded-xl bg-green-600 text-white font-bold text-sm hover:bg-green-500 shadow-lg transition transform active:scale-95">
                                    Upload Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('drop-zone');
    const csvInput = document.getElementById('csvInput');
    const dropContent = document.getElementById('drop-content');
    const fileInfo = document.getElementById('file-info');
    const filenameDisplay = document.getElementById('filename-display');

    // Efek Visual saat Drag Over
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('border-green-500', 'bg-slate-800');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-green-500', 'bg-slate-800');
        }, false);
    });

    // Handle Drop
    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        csvInput.files = files; // Set file ke input
        updateFileInfo(files[0]);
    });

    // Handle Klik Manual
    csvInput.addEventListener('change', function() {
        if (this.files[0]) updateFileInfo(this.files[0]);
    });

    function updateFileInfo(file) {
        if(file.name.endsWith('.csv')) {
            dropContent.classList.add('hidden');
            fileInfo.classList.remove('hidden');
            fileInfo.classList.add('flex');
            filenameDisplay.innerText = file.name;
        } else {
            Swal.fire({ icon: 'error', title: 'File Salah', text: 'Harap upload file .CSV', background: '#1e293b', color: '#fff' });
            csvInput.value = ''; // Reset
        }
    }
</script>