<?php //index.php
session_start(); 
include 'config/database.php'; 

// Ambil Running Text
$stmt_run = $conn->query("SELECT setting_value FROM settings WHERE setting_key='running_text'");
$run_txt = $stmt_run->fetchColumn() ?: 'Selamat Datang!';

// Ambil Banner
$stmt_ban = $conn->query("SELECT * FROM banners ORDER BY id DESC");
$banners = $stmt_ban->fetchAll();

// Ambil Game Aktif
$stmt_games = $conn->query("SELECT * FROM games WHERE status='active' ORDER BY name ASC");
$all_games = $stmt_games->fetchAll();

// Kategorisasi Game
$categorized_games = [
    'Populer' => [], 'Mobile Game' => [], 'PC Game' => [], 'Voucher' => [], 'Lainnya' => []
];

foreach ($all_games as $game) {
    if (stripos($game['category'], 'Mobile Game') !== false) $categorized_games['Mobile Game'][] = $game;
    elseif (stripos($game['category'], 'PC Game') !== false) $categorized_games['PC Game'][] = $game;
    elseif (stripos($game['category'], 'Voucher') !== false) $categorized_games['Voucher'][] = $game;
    else $categorized_games['Lainnya'][] = $game;
}
$categorized_games['Populer'] = array_slice($all_games, 0, 6); 
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Damasa Store - Top Up Game Termurah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-slate-900 dark:bg-slate-950 dark:text-white transition-colors duration-300">

    <?php include 'components/navbar.php'; ?>

    <div class="pt-[72px] bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
        <div class="container mx-auto px-4 py-2 flex items-center gap-4">
            <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded flex-shrink-0 animate-pulse">INFO</span>
            <marquee class="text-xs text-slate-600 dark:text-slate-300 font-medium"><?= htmlspecialchars($run_txt); ?></marquee>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-screen-xl">
        
        <div class="swiper mySwiper rounded-2xl overflow-hidden shadow-lg shadow-blue-900/5 dark:shadow-black/50 mb-10 aspect-[16/9] md:aspect-[21/7]">
            <div class="swiper-wrapper">
                <?php foreach($banners as $bn): ?>
                <div class="swiper-slide relative w-full h-full bg-slate-200 dark:bg-slate-800">
                    <img src="<?= $bn['image']; ?>" class="w-full h-full object-cover" alt="Banner">
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="relative max-w-xl mx-auto mb-12">
            <div class="relative bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-sm flex items-center p-1.5 focus-within:ring-2 focus-within:ring-blue-500 transition-all">
                <div class="pl-3 text-slate-400"><i class="ph-bold ph-magnifying-glass text-xl"></i></div>
                <input type="text" id="searchGame" class="w-full bg-transparent px-3 py-2 outline-none text-slate-800 dark:text-white placeholder-slate-400 font-medium" placeholder="Cari game...">
            </div>
        </div>

        <?php 
        $sections = [
            ['id' => 'Mobile Game', 'title' => 'Mobile Games', 'icon' => 'ph-device-mobile', 'color' => 'text-blue-500'],
            ['id' => 'PC Game', 'title' => 'PC Games', 'icon' => 'ph-desktop', 'color' => 'text-purple-500'],
            ['id' => 'Voucher', 'title' => 'Voucher & Lainnya', 'icon' => 'ph-ticket', 'color' => 'text-green-500']
        ];
        ?>

        <?php foreach($sections as $sec): 
            $items = $categorized_games[$sec['id']];
            if(empty($items)) continue;
        ?>
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-lg <?= $sec['color']; ?>">
                    <i class="ph-fill <?= $sec['icon']; ?>"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white"><?= $sec['title']; ?></h3>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3 md:gap-6">
                <?php foreach($items as $game) { include 'components/card_game.php'; } ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Swiper Init
        new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true }
        });

        // Search Logic
        document.getElementById('searchGame').addEventListener('keyup', (e) => {
            let term = e.target.value.toLowerCase();
            document.querySelectorAll('.group').forEach(card => {
                let title = card.querySelector('h4').innerText.toLowerCase();
                card.parentElement.style.display = title.includes(term) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>