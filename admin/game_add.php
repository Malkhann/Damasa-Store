<?php //admin/game_add.php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $slug = strtolower(str_replace(' ', '-', $name)); 
    $thumbnail = $_POST['thumbnail']; 
    $category = $_POST['category'];

    try {
        $stmt = $conn->prepare("INSERT INTO games (name, slug, thumbnail, category, status) VALUES (?, ?, ?, ?, 'active')");
        $stmt->execute([$name, $slug, $thumbnail, $category]);
        echo "<script>alert('Game Berhasil Ditambahkan'); window.location.href='index.php';</script>";
    } catch(PDOException $e) {
        echo "<script>alert('Gagal: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 p-8">
        <h2 class="text-xl font-bold mb-6">Tambah Game Baru (Link)</h2>
        <form method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Nama Game</label>
                <input type="text" name="name" class="w-full bg-slate-950 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Link Thumbnail (URL)</label>
                <input type="url" name="thumbnail" placeholder="https://example.com/image.jpg" class="w-full bg-slate-950 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Kategori</label>
                <select name="category" class="w-full bg-slate-950 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
                    <option value="Mobile Game">Mobile Game</option>
                    <option value="PC Game">PC Game</option>
                    <option value="Voucher">Voucher</option>
                </select>
            </div>
            <div class="flex gap-2">
                <a href="index.php" class="w-1/3 bg-slate-700 text-center py-3 rounded-xl">Batal</a>
                <button type="submit" name="submit" class="w-2/3 bg-blue-600 hover:bg-blue-500 font-bold py-3 rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>