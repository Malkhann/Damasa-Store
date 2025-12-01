<?php
session_start();
include '../config/database.php';

// Cek Login
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

// 1. AMBIL DATA GAME YANG MAU DIEDIT
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM games WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

// Jika ID ngawur/tidak ditemukan
if (!$data) { header("Location: index.php"); exit; }

// 2. PROSES UPDATE DATA
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $slug = strtolower(str_replace(' ', '-', $name)); // Update slug juga
    $status = $_POST['status'];
    
    // Logic Gambar: Cek apakah user upload gambar baru?
    if (!empty($_FILES['thumbnail']['name'])) {
        // --- JIKA UPLOAD GAMBAR BARU ---
        $filename_asli = $_FILES['thumbnail']['name'];
        $ekstensi = pathinfo($filename_asli, PATHINFO_EXTENSION);
        $new_filename = $slug . '-' . time() . '.' . $ekstensi;
        $target = "../assets/uploads/games/" . $new_filename;

        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target)) {
            // Hapus gambar lama biar server gak penuh (Opsional)
            if(file_exists("../assets/uploads/games/" . $data['thumbnail'])){
                unlink("../assets/uploads/games/" . $data['thumbnail']);
            }

            // Query Update DENGAN GAMBAR
            $query_update = "UPDATE games SET name='$name', slug='$slug', thumbnail='$new_filename', status='$status' WHERE id='$id'";
        }
    } else {
        // --- JIKA TIDAK UPLOAD GAMBAR (Hanya ganti nama/status) ---
        $query_update = "UPDATE games SET name='$name', slug='$slug', status='$status' WHERE id='$id'";
    }

    // Eksekusi Query
    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Data Game Berhasil Diupdate!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Game - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-lg bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 overflow-hidden">
        
        <div class="bg-slate-900/50 p-6 border-b border-slate-700 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white">Edit Game</h2>
                <p class="text-slate-400 text-sm mt-1">Ubah nama, status, atau gambar.</p>
            </div>
            <a href="index.php" class="text-sm text-slate-400 hover:text-white bg-slate-700 px-3 py-1 rounded-lg transition">Batal</a>
        </div>

        <div class="p-8">
            <form method="post" enctype="multipart/form-data" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Game</label>
                    <input type="text" name="name" value="<?= $data['name']; ?>" 
                           class="w-full bg-slate-950 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none transition" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select name="status" class="w-full bg-slate-950 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none">
                        <option value="active" <?= ($data['status'] == 'active') ? 'selected' : ''; ?>>Active (Tampil)</option>
                        <option value="inactive" <?= ($data['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive (Sembunyikan)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Gambar Saat Ini</label>
                    <div class="flex items-center gap-4 bg-slate-900/50 p-3 rounded-xl border border-slate-700">
                        <img src="../assets/uploads/games/<?= $data['thumbnail']; ?>" class="w-16 h-16 rounded-lg object-cover">
                        <p class="text-xs text-slate-500">Jika tidak ingin mengubah gambar,<br>biarkan kolom upload di bawah kosong.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Ganti Gambar (Opsional)</label>
                    <input type="file" name="thumbnail" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:bg-slate-700 file:text-blue-400 hover:file:bg-slate-600 cursor-pointer bg-slate-900/50 rounded-xl border border-slate-700">
                </div>
                
                <button type="submit" name="update" 
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.4)] transition transform active:scale-95">
                    Simpan Perubahan
                </button>

            </form>
        </div>
    </div>

</body>
</html>