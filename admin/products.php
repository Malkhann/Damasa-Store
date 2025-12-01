<?php //admin/products.php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

// --- 1. DOWNLOAD TEMPLATE CSV ---
if (isset($_GET['action']) && $_GET['action'] == 'download_template') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="template_produk.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Nama Item', 'Harga', 'Kategori (Diamonds/Membership/Bundles)']);
    fputcsv($output, ['100 Diamonds', '15000', 'Diamonds']);
    fputcsv($output, ['Weekly Pass', '30000', 'Membership']);
    fclose($output);
    exit;
}

$game_id = $_GET['game_id'];

$stmt_game = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt_game->execute([$game_id]);
$game = $stmt_game->fetch();

// --- 2. PROSES IMPORT CSV ---
if (isset($_POST['import_csv'])) {
    if($_FILES['csv_file']['name']) {
        $filename = explode(".", $_FILES['csv_file']['name']);
        if($filename[1] == 'csv'){
            $handle = fopen($_FILES['csv_file']['tmp_name'], "r");
            fgetcsv($handle); // Skip header row
            
            try {
                $conn->beginTransaction();
                $stmt_import = $conn->prepare("INSERT INTO products (game_id, name, price, category) VALUES (?, ?, ?, ?)");
                
                while($data = fgetcsv($handle)) {
                    $name = $data[0];
                    $price = $data[1];
                    $cat = !empty($data[2]) ? $data[2] : 'Diamonds';
                    
                    $stmt_import->execute([$game_id, $name, $price, $cat]);
                }
                
                $conn->commit();
                fclose($handle);
                echo "<script>alert('Import Berhasil!'); window.location.href='products.php?game_id=$game_id';</script>";
            } catch(Exception $e) {
                $conn->rollBack();
                echo "<script>alert('Gagal Import: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Format file harus .csv');</script>";
        }
    }
}

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO products (game_id, name, price) VALUES (?, ?, ?)");
        $insert = $stmt->execute([$game_id, $name, $price]);
        
        if($insert){
            echo "<script>window.location.href='products.php?game_id=$game_id';</script>";
        }
    } catch(PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

if (isset($_GET['delete'])) {
    $pid = $_GET['delete'];
    $stmt_del = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt_del->execute([$pid]);
    header("Location: products.php?game_id=$game_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Harga - <?= $game['name']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-900 text-white min-h-screen p-6">

    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-8 border-b border-slate-800 pb-6">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-slate-700 shadow-lg relative bg-slate-800">
                    <img src="<?= $game['thumbnail']; ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100?text=No+IMG'">
                </div>

                <div>
                    <h1 class="text-3xl font-bold text-white">Kelola Produk</h1>
                    <h2 class="text-xl text-blue-500 font-semibold mt-1"><?= $game['name']; ?></h2>
                </div>
            </div>
            
            <a href="index.php" class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl font-bold transition border border-slate-700 flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="space-y-6">
                <div class="bg-slate-800 p-6 rounded-2xl shadow-2xl border border-slate-700">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-700 pb-3">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center font-bold text-lg"><i class="ph-bold ph-file-csv"></i></div>
                        <h3 class="font-bold text-lg">Import Masal</h3>
                    </div>
                    
                    <form method="post" enctype="multipart/form-data" class="space-y-4">
                        <div class="text-xs text-slate-400 mb-2">
                            1. <a href="products.php?game_id=<?= $game_id; ?>&action=download_template" class="text-blue-400 underline hover:text-blue-300">Download Template CSV</a><br>
                            2. Isi data sesuai format<br>
                            3. Upload file di bawah ini
                        </div>
                        
                        <input type="file" name="csv_file" accept=".csv" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-slate-700 file:text-blue-400 hover:file:bg-slate-600 cursor-pointer bg-slate-950 rounded-xl border border-slate-600" required>
                        
                        <button type="submit" name="import_csv" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 rounded-xl shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-upload-simple"></i> Upload & Import
                        </button>
                    </form>
                </div>

                <div class="bg-slate-800 p-6 rounded-2xl shadow-2xl border border-slate-700">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-700 pb-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-lg">+</div>
                        <h3 class="font-bold text-lg">Tambah Manual</h3>
                    </div>
                    
                    <form method="post" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Nama Item</label>
                            <input type="text" name="name" placeholder="Contoh: 86 Diamonds" class="w-full bg-slate-950 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none transition" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Harga (Rp)</label>
                            <input type="number" name="price" placeholder="20000" class="w-full bg-slate-950 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 focus:outline-none transition font-mono font-bold" required>
                        </div>
                        
                        <button type="submit" name="add_product" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl shadow-lg transition transform active:scale-95">
                            Simpan Produk
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2 bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 overflow-hidden h-fit">
                <div class="p-6 border-b border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-white">Daftar Harga Aktif</h3>
                    <?php
                        $stmt_count = $conn->prepare("SELECT COUNT(*) FROM products WHERE game_id = ?");
                        $stmt_count->execute([$game_id]);
                        $total_items = $stmt_count->fetchColumn();
                    ?>
                    <span class="bg-slate-700 text-slate-300 text-xs px-3 py-1 rounded-full">
                        <?= $total_items; ?> Item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-900 text-slate-400 text-xs uppercase">
                                <th class="p-5 font-semibold">Nama Item</th>
                                <th class="p-5 font-semibold">Kategori</th>
                                <th class="p-5 font-semibold">Harga</th>
                                <th class="p-5 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <?php
                            $stmt_products = $conn->prepare("SELECT * FROM products WHERE game_id = ? ORDER BY price ASC");
                            $stmt_products->execute([$game_id]);
                            $products = $stmt_products->fetchAll();
                            
                            if(count($products) == 0) {
                                echo "<tr><td colspan='4' class='p-8 text-center text-slate-500 italic'>Belum ada item yang dijual. Silakan tambah manual atau import CSV.</td></tr>";
                            }

                            foreach($products as $p){
                            ?>
                            <tr class="hover:bg-slate-700/40 transition duration-200 group">
                                <td class="p-5 font-bold text-white text-base"><?= $p['name']; ?></td>
                                <td class="p-5 text-slate-400 text-xs"><span class="bg-slate-900 px-2 py-1 rounded border border-slate-600"><?= $p['category']; ?></span></td>
                                <td class="p-5 font-mono text-green-400 font-bold text-base">Rp <?= number_format($p['price']); ?></td>
                                <td class="p-5 text-right">
                                    <div class="flex justify-end gap-2 opacity-80 group-hover:opacity-100 transition">
                                        <a href="product_edit.php?id=<?= $p['id']; ?>&game_id=<?= $game_id; ?>" class="bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white px-3 py-1.5 rounded-lg transition border border-blue-500/30 text-xs font-bold">Edit</a>
                                        <a href="products.php?game_id=<?= $game_id; ?>&delete=<?= $p['id']; ?>" onclick="return confirm('Yakin hapus item <?= $p['name']; ?>?')" class="bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white px-3 py-1.5 rounded-lg transition border border-red-500/30 text-xs font-bold">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>