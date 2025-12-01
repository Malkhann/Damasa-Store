<?php //admin/content.php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

// LOGIC UPDATE RUNNING TEXT
if (isset($_POST['update_text'])) {
    $text = $_POST['running_text'];
    // Gunakan UPSERT (Insert jika belum ada, Update jika sudah ada) agar tidak error jika data kosong
    $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('running_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    $stmt->execute([$text, $text]);
    header("Location: content.php?msg=Teks Diupdate"); exit;
}

// LOGIC TAMBAH BANNER
if (isset($_POST['save_banner'])) {
    $url = $_POST['banner_url'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    
    if (!empty($url)) {
        $stmt = $conn->prepare("INSERT INTO banners (image, title, subtitle) VALUES (?, ?, ?)");
        $stmt->execute([$url, $title, $subtitle]);
        header("Location: content.php?msg=Banner Ditambah"); exit;
    }
}

// LOGIC HAPUS BANNER
if (isset($_GET['delete_banner'])) {
    $id = $_GET['delete_banner'];
    $conn->prepare("DELETE FROM banners WHERE id = ?")->execute([$id]);
    header("Location: content.php?msg=Banner Dihapus"); exit;
}

// --- PERBAIKAN UTAMA DI SINI ---
// Ambil Running Text dengan aman
$stmt_rt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key='running_text'");
$stmt_rt->execute();
$row_rt = $stmt_rt->fetch();

// Cek apakah data ditemukan. Jika tidak (false), set ke string kosong.
$running_text = ($row_rt) ? $row_rt['setting_value'] : ""; 

// Ambil Data Banner
$banners = $conn->query("SELECT * FROM banners ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Konten</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-950 text-white min-h-screen p-6 pt-24">
    <?php include 'components/navbar.php'; ?>
    
    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-8">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
                <h2 class="font-bold mb-4">Tambah Banner (URL)</h2>
                <form method="post" class="space-y-4">
                    <input type="hidden" name="save_banner" value="true">
                    <div>
                        <label class="text-xs text-slate-400">URL Gambar Banner</label>
                        <input type="url" name="banner_url" placeholder="https://..." class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-white outline-none" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="title" placeholder="Judul" class="bg-slate-950 border border-slate-700 rounded-xl p-3 text-white outline-none">
                        <input type="text" name="subtitle" placeholder="Sub Judul" class="bg-slate-950 border border-slate-700 rounded-xl p-3 text-white outline-none">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 py-3 rounded-xl font-bold hover:bg-indigo-500 transition">Simpan</button>
                </form>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
                <h2 class="font-bold mb-4">Running Text</h2>
                <form method="post" class="flex gap-2">
                    <input type="text" name="running_text" value="<?= htmlspecialchars($running_text); ?>" class="flex-1 bg-slate-950 border border-slate-700 rounded-xl p-3 text-white outline-none" placeholder="Masukkan teks berjalan...">
                    <button type="submit" name="update_text" class="bg-slate-800 px-6 rounded-xl font-bold hover:bg-slate-700 transition">Update</button>
                </form>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl h-fit">
            <h2 class="font-bold mb-4">List Banner</h2>
            <div class="space-y-4">
                <?php if(count($banners) == 0): ?>
                    <p class="text-slate-500 text-sm text-center py-4">Belum ada banner.</p>
                <?php endif; ?>

                <?php foreach($banners as $b): ?>
                <div class="flex items-center gap-4 bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <img src="<?= $b['image']; ?>" class="w-24 h-14 object-cover rounded-lg" onerror="this.src='https://placehold.co/100?text=Err'">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm truncate"><?= !empty($b['title']) ? $b['title'] : 'Tanpa Judul'; ?></p>
                        <p class="text-xs text-slate-500 truncate"><?= $b['image']; ?></p>
                    </div>
                    <a href="?delete_banner=<?= $b['id']; ?>" onclick="return confirm('Hapus?')" class="text-red-400 hover:text-white p-2"><i class="ph-bold ph-trash text-xl"></i></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>