<?php
// admin/product_edit.php
session_start();
include '../config/database.php';

// --- 1. CEK LOGIN & VALIDASI URL ---
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit; 
}

if (!isset($_GET['id']) || !isset($_GET['game_id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$game_id = $_GET['game_id'];

// --- 2. AMBIL DATA PRODUK (LOGIC) ---
try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if (!$data) {
        // Jika produk tidak ditemukan, kembalikan ke list
        header("Location: products.php?game_id=$game_id");
        exit;
    }
} catch(PDOException $e) {
    die("Error Database: " . $e->getMessage());
}

// --- 3. PROSES UPDATE DATA ---
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $promo_price = !empty($_POST['promo_price']) ? $_POST['promo_price'] : 0;
    
    // Format Tanggal (Jika kosong set NULL)
    $promo_date = !empty($_POST['promo_end_date']) ? $_POST['promo_end_date'] : NULL;

    try {
        $sql = "UPDATE products SET name=?, price=?, promo_price=?, promo_end_date=?, category=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $price, $promo_price, $promo_date, $category, $id]);
        
        // Tampilkan SweetAlert Sukses
        $success_msg = true;
        
        // Refresh data variabel $data agar form langsung berubah
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();

    } catch(PDOException $e) {
        $error_msg = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap'); 
        body{font-family:'Inter',sans-serif;}
        
        /* Animasi Custom */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter { animation: slideUpFade 0.5s ease-out forwards; }
        
        /* Icon Kalender agar terlihat di background gelap */
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-blue-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-96 h-96 bg-purple-600/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="w-full max-w-2xl bg-slate-900/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-800 overflow-hidden animate-enter">
        
        <div class="px-8 py-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-600/20">
                    <i class="ph-fill ph-pencil-simple text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">Edit Produk</h1>
                    <p class="text-slate-400 text-xs mt-0.5">Perbarui detail item dan harga.</p>
                </div>
            </div>
            <a href="products.php?game_id=<?= $game_id; ?>" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-700">
                <i class="ph-bold ph-x text-lg"></i>
            </a>
        </div>

        <div class="p-8">
            <form method="post" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Nama Item</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition">
                                <i class="ph-bold ph-tag"></i>
                            </div>
                            <input type="text" name="name" value="<?= htmlspecialchars($data['name']); ?>" 
                                   class="w-full bg-slate-950 border border-slate-700 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition font-medium" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Kategori</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition">
                                <i class="ph-bold ph-squares-four"></i>
                            </div>
                            <select name="category" class="w-full bg-slate-950 border border-slate-700 rounded-xl py-3.5 pl-11 pr-4 text-white outline-none focus:border-blue-500 appearance-none cursor-pointer transition hover:border-slate-600">
                                <?php 
                                $cats = ['Diamonds', 'Membership', 'Bundles', 'Voucher', 'Coins', 'UC', 'CP', 'Wild Cores', 'Tokens', 'Locks', 'Subscription', 'FC Points', 'PB Cash', 'Gems', 'Crystals', 'Shards', 'Points', 'Robux'];
                                foreach($cats as $c): 
                                ?>
                                    <option value="<?= $c; ?>" <?= ($data['category'] == $c) ? 'selected' : ''; ?>><?= $c; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                <i class="ph-bold ph-caret-down"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Harga Normal (Rp)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-500 group-focus-within:text-green-500 transition">
                                <span class="font-bold text-xs">Rp</span>
                            </div>
                            <input type="number" name="price" value="<?= $data['price']; ?>" 
                                   class="w-full bg-slate-950 border border-slate-700 rounded-xl py-3.5 pl-10 pr-4 text-white font-mono font-bold focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none transition" required>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-900/10 border border-blue-500/20 rounded-2xl p-5 space-y-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-20"><i class="ph-fill ph-percent text-6xl text-blue-500"></i></div>
                    
                    <h3 class="text-sm font-bold text-blue-400 flex items-center gap-2">
                        <i class="ph-fill ph-lightning"></i> Atur Promo (Opsional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Harga Promo</label>
                            <div class="relative group">
                                <input type="number" name="promo_price" value="<?= $data['promo_price'] > 0 ? $data['promo_price'] : ''; ?>" placeholder="0"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 px-4 text-white font-mono font-bold focus:border-blue-500 outline-none transition placeholder-slate-600">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Berakhir Pada</label>
                            <input type="datetime-local" name="promo_end_date" value="<?= $data['promo_end_date'] ? date('Y-m-d\TH:i', strtotime($data['promo_end_date'])) : ''; ?>"
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl py-2.5 px-4 text-white text-sm focus:border-blue-500 outline-none transition font-mono">
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex gap-3">
                    <a href="products.php?game_id=<?= $game_id; ?>" class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-4 rounded-xl text-center transition border border-slate-700">
                        Batal
                    </a>
                    <button type="submit" name="update" class="flex-[2] bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="ph-bold ph-check-circle text-xl"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        <?php if (isset($success_msg)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Produk telah diperbarui.',
                background: '#1e293b',
                color: '#fff',
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.href='products.php?game_id=<?= $game_id; ?>';
            });
        <?php endif; ?>

        <?php if (isset($error_msg)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $error_msg; ?>',
                background: '#1e293b', 
                color: '#fff'
            });
        <?php endif; ?>
    </script>

</body>
</html>