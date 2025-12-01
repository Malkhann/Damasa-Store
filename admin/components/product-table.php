<?php //admin/components/product-table.php ?>
<div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl shadow-2xl border border-slate-800 overflow-hidden">
    
    <div class="p-5 border-b border-slate-800 bg-slate-900/50 flex flex-col md:flex-row gap-4 justify-between items-center">
        <form method="GET" class="relative w-full md:w-auto">
            <i class="ph ph-magnifying-glass absolute left-3 top-3 text-slate-500"></i>
            <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Cari produk..." class="bg-slate-950 border border-slate-700 text-white text-sm rounded-xl pl-10 pr-4 py-2.5 w-full md:w-64 focus:border-blue-500 focus:outline-none transition">
            
            <?php if($cat_filter): ?><input type="hidden" name="cat" value="<?= $cat_filter; ?>"><?php endif; ?>
            <?php if($status_filter): ?><input type="hidden" name="status" value="<?= $status_filter; ?>"><?php endif; ?>
        </form>

        <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
            <a href="index.php" class="px-4 py-2 rounded-lg text-xs font-bold border transition <?= (!$cat_filter && !$status_filter) ? 'bg-blue-600 border-blue-500 text-white' : 'bg-slate-950 border-slate-700 text-slate-400 hover:text-white'; ?>">
                Semua
            </a>
            
            <form method="GET" id="filterForm" class="flex gap-2">
                <select name="cat" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 text-slate-300 text-xs rounded-lg px-3 py-2 focus:outline-none cursor-pointer hover:border-slate-500 transition">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Mobile Game" <?= ($cat_filter == 'Mobile Game') ? 'selected' : ''; ?>>Mobile Game</option>
                    <option value="PC Game" <?= ($cat_filter == 'PC Game') ? 'selected' : ''; ?>>PC Game</option>
                    <option value="Voucher" <?= ($cat_filter == 'Voucher') ? 'selected' : ''; ?>>Voucher</option>
                </select>
                
                <select name="status" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 text-slate-300 text-xs rounded-lg px-3 py-2 focus:outline-none cursor-pointer hover:border-slate-500 transition">
                    <option value="">-- Status --</option>
                    <option value="active" <?= ($status_filter == 'active') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="inactive" <?= ($status_filter == 'inactive') ? 'selected' : ''; ?>>Non-Aktif</option>
                </select>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-950/50 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-800">
                    <th class="p-5 font-semibold">Cover</th>
                    <th class="p-5 font-semibold">Info Produk</th>
                    <th class="p-5 font-semibold">Kategori</th>
                    <th class="p-5 font-semibold">Status</th>
                    <th class="p-5 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50 text-sm">
                <?php
                if(mysqli_num_rows($query) == 0) echo "<tr><td colspan='5' class='p-8 text-center text-slate-500'>Data tidak ditemukan.</td></tr>";
                while ($row = mysqli_fetch_assoc($query)) {
                    $cats = explode(',', $row['category']);
                ?>
                <tr class="hover:bg-slate-800/50 transition duration-200 group">
                    <td class="p-4 w-20">
                        <img src="../assets/uploads/games/<?= $row['thumbnail']; ?>" class="w-12 h-12 rounded-lg object-cover border border-slate-700 group-hover:scale-110 transition duration-300">
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-white group-hover:text-blue-400 transition"><?= $row['name']; ?></div>
                        <div class="text-xs text-slate-500 mt-0.5 font-mono">/<?= $row['slug']; ?></div>
                    </td>
                    <td class="p-4">
                        <div class="flex flex-wrap gap-1 max-w-[180px]">
                            <?php foreach($cats as $c): ?>
                                <span class="px-2 py-0.5 bg-slate-800 border border-slate-700 rounded text-[10px] text-slate-300"><?= trim($c); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border <?= ($row['status']=='active') ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-slate-800 text-slate-400 border-slate-700'; ?>">
                            <i class="ph-bold <?= ($row['status']=='active') ? 'ph-check' : 'ph-prohibit'; ?>"></i>
                            <?= ($row['status']=='active') ? 'AKTIF' : 'OFF'; ?>
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openItemsModal(<?= $row['id']; ?>, '<?= $row['name']; ?>')" title="Kelola Item & Harga" 
   class="w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-500 hover:bg-yellow-500 hover:text-black flex items-center justify-center transition border border-yellow-500/20">
    <i class="ph-bold ph-list-dashes"></i>
</button>
                            <button onclick='openModal("edit", <?= json_encode($row); ?>)' class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition border border-blue-500/20"><i class="ph-bold ph-pencil-simple"></i></button>
                            <button onclick="confirmDelete(<?= $row['id']; ?>)" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition border border-red-500/20"><i class="ph-bold ph-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-900/30 flex justify-between items-center">
        <p class="text-xs text-slate-500">Hal <span class="text-white font-bold"><?= $page; ?></span> dari <?= $total_pages; ?></p>
        <div class="flex gap-1">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page-1; ?>&search=<?= $search; ?>&cat=<?= $cat_filter; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-blue-600 transition text-slate-400 hover:text-white"><i class="ph-bold ph-caret-left"></i></a>
            <?php endif; ?>
            <?php if($page < $total_pages): ?>
                <a href="?page=<?= $page+1; ?>&search=<?= $search; ?>&cat=<?= $cat_filter; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-blue-600 transition text-slate-400 hover:text-white"><i class="ph-bold ph-caret-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
</div>