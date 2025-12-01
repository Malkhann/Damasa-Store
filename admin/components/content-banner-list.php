<?php //admin/components/content-banner-list.php ?>
<div class="bg-blue-900/20 border border-blue-500/30 p-5 rounded-2xl">
    <h3 class="text-sm font-bold text-blue-400 mb-3 flex items-center gap-2"><i class="ph-fill ph-info"></i> Panduan Ukuran Banner</h3>
    <p class="text-xs text-slate-300 mb-4 leading-relaxed">
        Agar tampilan optimal di semua perangkat menggunakan <b>1 Gambar</b>:
        <br>• Upload gambar dengan resolusi Desktop: <b>1920 x 640 px (Rasio 3:1)</b>.
        <br>• Pastikan konten utama (Teks/Orang) berada di <b>Area Tengah (Rasio 16:9)</b> agar tidak terpotong di HP.
    </p>
    
    <div class="relative w-full aspect-[3/1] bg-slate-800 rounded-lg overflow-hidden border-2 border-green-500 mb-2 shadow-lg group">
        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070" class="w-full h-full object-cover opacity-40">
        
        <div class="absolute top-2 left-2 bg-green-600 text-white text-[9px] font-bold px-2 py-0.5 rounded border border-green-400 z-10">
            Desktop View (Full 3:1)
        </div>
        
        <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 h-full aspect-video border-l-2 border-r-2 border-red-500 bg-red-500/10 flex flex-col items-center justify-center z-0">
            <div class="text-center p-2">
                <span class="bg-red-600 text-white text-[9px] font-bold px-2 py-0.5 rounded border border-red-400 block mb-1 mx-auto w-fit">
                    Mobile View (16:9)
                </span>
                <p class="text-[8px] text-red-300 font-bold uppercase tracking-wider">
                    AREA AMAN<br>(Teks Disini)
                </p>
            </div>
            <div class="absolute top-0 bottom-0 left-1/2 w-px bg-red-500/50 border-dashed"></div>
            <div class="absolute left-0 right-0 top-1/2 h-px bg-red-500/50 border-dashed"></div>
        </div>

        <div class="absolute top-0 bottom-0 left-0 right-[calc(50%+((100vh*1.77)/2))] bg-black/40"></div>
    </div>
    <div class="flex justify-between text-[10px] text-slate-400 px-1">
        <span>Sisi kiri terpotong di HP</span>
        <span>Sisi kanan terpotong di HP</span>
    </div>
</div>

<div class="mt-8">
    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
        Banner Aktif <span class="bg-slate-800 text-xs px-2 py-0.5 rounded-full border border-slate-700 text-slate-400"><?= $total_banners; ?>/7</span>
    </h3>
    
    <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 scrollbar-hide">
        <?php if($total_banners == 0) echo "<div class='p-6 text-center border-2 border-dashed border-slate-800 rounded-xl text-slate-500 text-sm'>Belum ada banner yang diupload.</div>"; ?>
        
        <?php while($b = mysqli_fetch_assoc($banners)): 
            $img_src = (strpos($b['image'], 'http') !== false) ? $b['image'] : "../assets/uploads/banners/" . $b['image'];
        ?>
        <div class="group relative flex gap-4 bg-slate-900 border border-slate-800 p-3 rounded-2xl hover:border-indigo-500/50 transition items-center">
            <div class="w-32 h-20 rounded-xl overflow-hidden bg-slate-950 relative flex-shrink-0 border border-slate-800">
                <img src="<?= $img_src; ?>" class="w-full h-full object-cover">
            </div>
            
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-sm text-white truncate mb-1"><?= $b['title'] ? $b['title'] : '<span class="text-slate-600 italic">Tanpa Judul</span>'; ?></h4>
                <p class="text-xs text-slate-500 truncate"><?= $b['subtitle'] ? $b['subtitle'] : '-'; ?></p>
                <div class="mt-2 flex gap-2">
                    <span class="text-[10px] bg-slate-800 px-2 py-0.5 rounded text-slate-400 border border-slate-700">ID: <?= $b['id']; ?></span>
                </div>
            </div>
            
            <a href="content.php?delete_banner=<?= $b['id']; ?>" onclick="return confirm('Hapus banner ini?')" class="w-9 h-9 flex items-center justify-center bg-slate-800 text-slate-400 hover:bg-red-600 hover:text-white rounded-xl transition shadow-sm">
                <i class="ph-bold ph-trash text-lg"></i>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</div>