<?php //admin/index.php
session_start();
include '../config/database.php';

// --- 1. LOGIC LOGOUT (BARU DITAMBAHKAN) ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Cek Login
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

// --- LOGIC SIMPAN GAME (DENGAN LINK GAMBAR) ---
if (isset($_POST['save_game'])) {
    $mode = $_POST['mode']; 
    $id = $_POST['id']; 
    $name = $_POST['name']; 
    $slug = strtolower(str_replace(' ', '-', $name)); 
    $status = $_POST['status'];
    $thumb = $_POST['thumbnail']; // URL
    $cats = isset($_POST['categories']) ? implode(',', $_POST['categories']) : 'Lainnya';

    if ($mode == 'add') {
        $stmt = $conn->prepare("INSERT INTO games (name, slug, thumbnail, category, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $thumb, $cats, $status]);
    } else {
        $stmt = $conn->prepare("UPDATE games SET name=?, slug=?, thumbnail=?, category=?, status=? WHERE id=?");
        $stmt->execute([$name, $slug, $thumb, $cats, $status, $id]);
    }
    header("Location: index.php"); exit;
}

// --- LOGIC HAPUS GAME ---
if (isset($_GET['delete_game'])) {
    $conn->prepare("DELETE FROM games WHERE id=?")->execute([$_GET['delete_game']]);
    header("Location: index.php"); exit;
}

// Data Games
$games = $conn->query("SELECT * FROM games ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-white min-h-screen pt-24 pb-10 px-4">
    <?php include 'components/navbar.php'; ?>

    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Kelola Game</h1>
            <button onclick="openGameModal('add')" class="bg-blue-600 px-4 py-2 rounded-xl font-bold flex gap-2 items-center"><i class="ph-bold ph-plus"></i> Tambah Game</button>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="p-4">Cover</th>
                        <th class="p-4">Nama Game</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php foreach($games as $g): ?>
                    <tr class="hover:bg-slate-800/50">
                        <td class="p-4"><img src="<?= $g['thumbnail']; ?>" class="w-12 h-12 rounded object-cover" onerror="this.src='https://placehold.co/100?text=No+Img'"></td>
                        <td class="p-4 font-bold"><?= $g['name']; ?></td>
                        <td class="p-4 text-sm text-slate-400"><?= $g['category']; ?></td>
                        <td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold <?= $g['status']=='active'?'bg-green-500/20 text-green-400':'bg-red-500/20 text-red-400'; ?>"><?= $g['status']; ?></span></td>
                        <td class="p-4 text-right flex justify-end gap-2">
                            <a href="products.php?game_id=<?= $g['id']; ?>" class="bg-yellow-600/20 text-yellow-500 p-2 rounded"><i class="ph-bold ph-list"></i></a>
                            <button onclick='openGameModal("edit", <?= json_encode($g); ?>)' class="bg-blue-600/20 text-blue-500 p-2 rounded"><i class="ph-bold ph-pencil"></i></button>
                            <a href="?delete_game=<?= $g['id']; ?>" onclick="return confirm('Hapus?')" class="bg-red-600/20 text-red-500 p-2 rounded"><i class="ph-bold ph-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="gameModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-lg border border-slate-700">
            <h2 class="text-xl font-bold mb-4" id="modalTitle">Tambah Game</h2>
            <form method="post" class="space-y-4">
                <input type="hidden" name="save_game" value="1">
                <input type="hidden" name="mode" id="mode">
                <input type="hidden" name="id" id="gameId">
                
                <input type="text" name="name" id="inpName" placeholder="Nama Game" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none" required>
                
                <div>
                    <label class="text-xs text-slate-400">Link Thumbnail (URL)</label>
                    <input type="url" name="thumbnail" id="inpThumb" placeholder="https://..." class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none" required>
                </div>
                
                <div>
                    <label class="text-xs text-slate-400 block mb-1">Kategori</label>
                    <div class="grid grid-cols-2 gap-2">
                        <?php $cats=["Mobile Game","PC Game","Voucher","E-Wallet"]; foreach($cats as $c): ?>
                        <label class="flex items-center gap-2 bg-slate-950 p-2 rounded border border-slate-700">
                            <input type="checkbox" name="categories[]" value="<?= $c; ?>" class="cat-check"> <span class="text-sm"><?= $c; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <select name="status" id="inpStatus" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('gameModal').classList.add('hidden')" class="flex-1 bg-slate-800 py-3 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 py-3 rounded-xl font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openGameModal(mode, data=null) {
            document.getElementById('gameModal').classList.remove('hidden');
            document.getElementById('gameModal').classList.add('flex');
            document.getElementById('mode').value = mode;
            document.querySelectorAll('.cat-check').forEach(c => c.checked = false);
            
            if(mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Game';
                document.getElementById('gameId').value = data.id;
                document.getElementById('inpName').value = data.name;
                document.getElementById('inpThumb').value = data.thumbnail;
                document.getElementById('inpStatus').value = data.status;
                let cats = data.category.split(',');
                document.querySelectorAll('.cat-check').forEach(c => { if(cats.includes(c.value)) c.checked = true; });
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Game';
                document.querySelector('form').reset();
            }
        }
    </script>
</body>
</html>