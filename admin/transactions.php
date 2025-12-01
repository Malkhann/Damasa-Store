<?php //admin/transactions.php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

// --- 1. LOGIC UPDATE STATUS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'update_trx') {
    $inv = $_POST['invoice_id'];
    $new_status = $_POST['new_status'];
    $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : "transactions.php";
    
    $allowed_status = ['pending', 'success', 'failed'];
    if (in_array($new_status, $allowed_status)) {
        try {
            $stmt = $conn->prepare("UPDATE transactions SET status = ? WHERE invoice_id = ?");
            $update = $stmt->execute([$new_status, $inv]);
            
            if($update) {
                header("Location: " . $return_url . (strpos($return_url, '?') ? '&' : '?') . "msg=Status Berhasil Diubah");
                exit;
            }
        } catch(PDOException $e) {
            echo "Error Update: " . $e->getMessage();
        }
    }
}

// --- 2. LOGIC FILTER & PAGINATION ---
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Hitung Jumlah Data (Statistik Button) - Menggunakan PDO query langsung
$total_pending = $conn->query("SELECT COUNT(*) FROM transactions WHERE status='pending'")->fetchColumn();
$total_success = $conn->query("SELECT COUNT(*) FROM transactions WHERE status='success'")->fetchColumn();
$total_failed  = $conn->query("SELECT COUNT(*) FROM transactions WHERE status='failed'")->fetchColumn();

// Query Dasar dengan Parameter Binding
$sql_base = "FROM transactions WHERE 1=1";
$params = [];

if($filter != 'all') { 
    $sql_base .= " AND status = ?"; 
    $params[] = $filter;
}
if($search) { 
    $sql_base .= " AND (invoice_id LIKE ? OR email_buyer LIKE ?)"; 
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

// Hitung Total Data (untuk pagination)
$stmt_count = $conn->prepare("SELECT COUNT(*) as total $sql_base");
$stmt_count->execute($params);
$total_data = $stmt_count->fetchColumn();
$total_pages = ceil($total_data / $limit);

// Ambil Data Utama
// Note: LIMIT & OFFSET di PDO sebaiknya di-bind sebagai INT atau dimasukkan langsung jika variabel sudah divalidasi (int)
$stmt_data = $conn->prepare("SELECT * $sql_base ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt_data->execute($params);
$transactions = $stmt_data->fetchAll(); // Ambil semua data
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Transaksi - Damasa Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex flex-col relative overflow-x-hidden">
    
    <div class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none">
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-600/10 rounded-full blur-[100px]"></div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <div class="container mx-auto px-4 py-10 pt-28">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center gap-2">
                    Data Transaksi
                    <span class="bg-purple-600/20 text-purple-400 border border-purple-500/30 text-xs px-2 py-0.5 rounded-full">Live</span>
                </h1>
                <p class="text-slate-400 text-sm mt-1">Pantau pembayaran masuk dan proses pesanan.</p>
            </div>
            
            <form method="GET" class="relative w-full md:w-auto">
                <i class="ph ph-magnifying-glass absolute left-3 top-3 text-slate-500"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Cari No. Invoice..." 
                       class="bg-slate-900 border border-slate-700 text-white text-sm rounded-xl pl-10 pr-4 py-2.5 w-full md:w-72 focus:border-blue-500 focus:outline-none transition shadow-lg">
                <?php if($filter != 'all'): ?><input type="hidden" name="status" value="<?= $filter; ?>"><?php endif; ?>
            </form>
        </div>

        <div class="flex flex-wrap gap-3 mb-6 overflow-x-auto pb-2">
            <a href="transactions.php?status=all" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition border flex items-center gap-2 <?= ($filter == 'all') ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-slate-900 text-slate-400 border-slate-700 hover:bg-slate-800 hover:text-white'; ?>">
               <i class="ph-bold ph-squares-four"></i> Semua
            </a>

            <a href="transactions.php?status=pending" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition border flex items-center gap-2 <?= ($filter == 'pending') ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/50 shadow-[0_0_15px_rgba(234,179,8,0.1)]' : 'bg-slate-900 text-slate-400 border-slate-700 hover:bg-slate-800 hover:text-white'; ?>">
               <i class="ph-bold ph-hourglass"></i> Menunggu
               <?php if($total_pending > 0): ?>
                   <span class="bg-yellow-500 text-black text-[10px] px-1.5 py-0.5 rounded-full font-bold"><?= $total_pending; ?></span>
               <?php endif; ?>
            </a>

            <a href="transactions.php?status=success" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition border flex items-center gap-2 <?= ($filter == 'success') ? 'bg-green-500/10 text-green-400 border-green-500/50' : 'bg-slate-900 text-slate-400 border-slate-700 hover:bg-slate-800 hover:text-white'; ?>">
               <i class="ph-bold ph-check-circle"></i> Berhasil
               <span class="bg-slate-800 border border-slate-700 text-slate-300 text-[10px] px-1.5 py-0.5 rounded-full"><?= $total_success; ?></span>
            </a>

            <a href="transactions.php?status=failed" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition border flex items-center gap-2 <?= ($filter == 'failed') ? 'bg-red-500/10 text-red-400 border-red-500/50' : 'bg-slate-900 text-slate-400 border-slate-700 hover:bg-slate-800 hover:text-white'; ?>">
               <i class="ph-bold ph-x-circle"></i> Gagal
               <span class="bg-slate-800 border border-slate-700 text-slate-300 text-[10px] px-1.5 py-0.5 rounded-full"><?= $total_failed; ?></span>
            </a>
        </div>

        <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl shadow-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/50 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-800">
                            <th class="p-5">Invoice / Tanggal</th>
                            <th class="p-5">Item</th>
                            <th class="p-5">Pembayaran</th>
                            <th class="p-5 text-center">Status</th>
                            <th class="p-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-sm">
                        <?php
                        if(count($transactions) == 0){
                            echo "<tr><td colspan='5' class='p-10 text-center text-slate-500 italic'>Tidak ada transaksi ditemukan.</td></tr>";
                        }

                        foreach ($transactions as $row) {
                            $badge_color = "bg-slate-800 text-slate-400 border-slate-700";
                            $icon = "ph-minus";
                            
                            if($row['status'] == 'pending') { 
                                $badge_color = "bg-yellow-500/10 text-yellow-400 border-yellow-500/20 animate-pulse"; 
                                $icon = "ph-hourglass";
                            }
                            if($row['status'] == 'success') { 
                                $badge_color = "bg-green-500/10 text-green-400 border-green-500/20"; 
                                $icon = "ph-check";
                            }
                            if($row['status'] == 'failed') { 
                                $badge_color = "bg-red-500/10 text-red-400 border-red-500/20"; 
                                $icon = "ph-x";
                            }
                        ?>
                        <tr class="hover:bg-slate-800/50 transition duration-200 group">
                            <td class="p-5 align-top">
                                <div class="flex flex-col gap-1">
                                    <span class="font-mono font-bold text-blue-400 text-base">#<?= $row['invoice_id']; ?></span>
                                    <div class="text-xs text-slate-500 flex items-center gap-1"><i class="ph-fill ph-calendar-blank"></i> <?= date('d M Y, H:i', strtotime($row['created_at'])); ?></div>
                                    <div class="text-xs text-slate-400 mt-1 flex items-center gap-1"><i class="ph-fill ph-envelope-simple"></i> <?= $row['email_buyer']; ?></div>
                                </div>
                            </td>

                            <td class="p-5 align-top">
                                <div class="font-bold text-white text-base mb-1"><?= $row['product_name']; ?></div>
                                <div class="inline-flex items-center gap-2 bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5">
                                    <i class="ph-fill ph-identification-card text-slate-500"></i>
                                    <div class="font-mono text-xs text-slate-300">
                                        ID: <span class="text-white font-bold select-all"><?= $row['game_user_id']; ?></span> 
                                        <?= !empty($row['game_zone_id']) ? "<span class='text-slate-600 mx-1'>|</span> Zone: <span class='text-white font-bold select-all'>$row[game_zone_id]</span>" : ""; ?>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5 align-top">
                                <div class="font-mono font-bold text-green-400 text-lg">Rp <?= number_format($row['amount']); ?></div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="ph-fill ph-wallet"></i> Via <?= $row['payment_method']; ?></div>
                            </td>

                            <td class="p-5 align-middle text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border <?= $badge_color; ?>">
                                    <i class="ph-bold <?= $icon; ?>"></i> <?= strtoupper($row['status']); ?>
                                </span>
                            </td>

                            <td class="p-5 align-middle text-center">
                                <?php if($row['status'] == 'pending'): ?>
                                    <div class="flex justify-center gap-2">
                                        <button onclick="updateStatus('<?= $row['invoice_id']; ?>', 'success')" 
                                                class="w-10 h-10 rounded-xl bg-green-600 hover:bg-green-500 text-white flex items-center justify-center shadow-lg shadow-green-600/20 transition transform hover:-translate-y-1" title="Terima Pesanan">
                                            <i class="ph-bold ph-check text-lg"></i>
                                        </button>
                                        
                                        <button onclick="updateStatus('<?= $row['invoice_id']; ?>', 'failed')" 
                                                class="w-10 h-10 rounded-xl bg-red-600 hover:bg-red-500 text-white flex items-center justify-center shadow-lg shadow-red-600/20 transition transform hover:-translate-y-1" title="Tolak Pesanan">
                                            <i class="ph-bold ph-x text-lg"></i>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <button onclick="updateStatus('<?= $row['invoice_id']; ?>', 'pending')" 
                                            class="text-xs text-slate-500 hover:text-white border-b border-dashed border-slate-600 hover:border-white transition pb-0.5">
                                        Reset Status
                                    </button>
                                <?php endif; ?>
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
                        <a href="?page=<?= $page-1; ?>&status=<?= $filter; ?>&search=<?= $search; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-blue-600 transition text-slate-400 hover:text-white"><i class="ph-bold ph-caret-left"></i></a>
                    <?php endif; ?>
                    <?php if($page < $total_pages): ?>
                        <a href="?page=<?= $page+1; ?>&status=<?= $filter; ?>&search=<?= $search; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-blue-600 transition text-slate-400 hover:text-white"><i class="ph-bold ph-caret-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <form id="statusForm" method="POST" class="hidden">
        <input type="hidden" name="action_type" value="update_trx">
        <input type="hidden" name="invoice_id" id="formInvoiceId">
        <input type="hidden" name="new_status" id="formNewStatus">
        <input type="hidden" name="return_url" value="<?= $_SERVER['REQUEST_URI']; ?>">
    </form>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('msg')) {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: urlParams.get('msg'), background: '#1e293b', color: '#fff', confirmButtonColor: '#2563eb' });
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        function updateStatus(invoice, status) {
            Swal.fire({
                title: (status === 'success') ? 'Terima Pesanan?' : (status === 'failed') ? 'Tolak Pesanan?' : 'Reset Status?',
                text: 'Status akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                background: '#1e293b', color: '#fff',
                confirmButtonColor: (status === 'success') ? '#16a34a' : (status === 'failed') ? '#dc2626' : '#ca8a04',
                confirmButtonText: 'Ya, Lanjutkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formInvoiceId').value = invoice;
                    document.getElementById('formNewStatus').value = status;
                    document.getElementById('statusForm').submit();
                }
            });
        }
    </script>

</body>
</html>