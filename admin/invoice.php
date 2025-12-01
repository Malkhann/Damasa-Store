<?php //invoice.php
session_start();
include 'config/database.php';

if (!isset($_GET['inv'])) { header("Location: index.php"); exit; }
$invoice = $_GET['inv'];

// Ambil Data Transaksi
$query = mysqli_query($conn, "SELECT * FROM transactions WHERE invoice_id='$invoice'");
$trx = mysqli_fetch_assoc($query);

if (!$trx) { header("Location: index.php"); exit; }

// Ambil Detail Metode Pembayaran untuk mendapatkan No Rek / QR Image
// Kita cari berdasarkan NAMA method yang disimpan di transaksi
$pay_method_name = $trx['payment_method'];
$query_pay = mysqli_query($conn, "SELECT * FROM payment_methods WHERE name='$pay_method_name'");
$pay_detail = mysqli_fetch_assoc($query_pay);

// Status Logic
$status_class = "bg-yellow-500/10 text-yellow-400 border-yellow-500/20";
$status_text = "Menunggu Pembayaran";
$icon = "ph-hourglass";

if ($trx['status'] == 'success') {
    $status_class = "bg-green-500/10 text-green-400 border-green-500/20";
    $status_text = "Pembayaran Berhasil";
    $icon = "ph-check-circle";
} elseif ($trx['status'] == 'failed') {
    $status_class = "bg-red-500/10 text-red-400 border-red-500/20";
    $status_text = "Transaksi Gagal";
    $icon = "ph-x-circle";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $invoice; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-slate-900 rounded-3xl shadow-2xl border border-slate-800 overflow-hidden relative">
        <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-600 w-full"></div>
        
        <div class="p-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800 mb-4 ring-4 ring-slate-800 shadow-xl">
                     <?php if(!empty($pay_detail['logo']) && file_exists("assets/uploads/payments/".$pay_detail['logo'])): ?>
                        <img src="assets/uploads/payments/<?= $pay_detail['logo']; ?>" class="h-8 object-contain">
                    <?php else: ?>
                        <i class="ph-fill ph-receipt text-3xl text-slate-400"></i>
                    <?php endif; ?>
                </div>
                <h1 class="text-xl font-bold text-white">Detail Transaksi</h1>
                <p class="text-slate-400 text-sm mt-1">No. Invoice: <span class="font-mono text-blue-400"><?= $invoice; ?></span></p>
                
                <div class="mt-4 flex justify-center">
                    <span class="px-4 py-1.5 rounded-full border text-xs font-bold flex items-center gap-2 <?= $status_class; ?>">
                        <i class="ph-fill <?= $icon; ?>"></i> <?= strtoupper($status_text); ?>
                    </span>
                </div>
            </div>

            <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 space-y-3 mb-6 relative overflow-hidden">
                <i class="ph-fill ph-game-controller absolute -right-4 -bottom-4 text-6xl text-slate-800/50"></i>

                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Item</span>
                    <span class="font-bold text-white text-right w-1/2 truncate"><?= $trx['product_name']; ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">User ID</span>
                    <span class="font-mono text-white"><?= $trx['game_user_id']; ?></span>
                </div>
                <div class="h-px bg-slate-800 border-t border-dashed border-slate-700 my-2"></div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Total Bayar</span>
                    <span class="text-xl font-bold text-green-400">Rp <?= number_format($trx['amount']); ?></span>
                </div>
            </div>

            <?php if($trx['status'] == 'pending'): ?>
                
                <?php if(!empty($pay_detail['qr_image']) && file_exists("assets/uploads/payments/".$pay_detail['qr_image'])): ?>
                    <div class="bg-white p-4 rounded-2xl text-center mb-6">
                        <p class="text-slate-800 text-xs font-bold mb-2 uppercase tracking-wider">Scan QRIS di bawah ini</p>
                        <img src="assets/uploads/payments/<?= $pay_detail['qr_image']; ?>" class="w-48 h-48 mx-auto object-contain border-2 border-slate-200 rounded-lg">
                        <p class="text-[10px] text-slate-500 mt-2">Mendukung semua E-Wallet & Bank</p>
                    </div>

                <?php else: ?>
                    <div class="bg-blue-600/10 border border-blue-600/30 rounded-2xl p-5 text-center mb-6">
                        <p class="text-xs text-blue-300 mb-2">Silakan Transfer ke:</p>
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <?php if(!empty($pay_detail['logo'])): ?>
                                <img src="assets/uploads/payments/<?= $pay_detail['logo']; ?>" class="h-5 object-contain">
                            <?php endif; ?>
                            <span class="font-bold text-white"><?= $trx['payment_method']; ?></span>
                        </div>
                        
                        <div class="bg-slate-900 rounded-lg p-3 border border-slate-700 mt-3 flex items-center justify-between gap-3">
                            <span class="font-mono text-lg font-bold text-white tracking-wide" id="rekNum">
                                <?= isset($pay_detail['account_number']) ? $pay_detail['account_number'] : '-'; ?>
                            </span>
                            <button onclick="copyToClipboard('rekNum')" class="text-xs bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded transition flex items-center gap-1">
                                <i class="ph-bold ph-copy"></i> Salin
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">a.n <?= isset($pay_detail['account_holder']) ? $pay_detail['account_holder'] : 'Damasa'; ?></p>
                    </div>
                <?php endif; ?>

                <a href="https://wa.me/628123456789?text=Halo%20Admin,%20saya%20sudah%20bayar%20invoice%20<?= $invoice; ?>" target="_blank" class="block w-full bg-green-600 hover:bg-green-500 text-white text-center py-3.5 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-lg shadow-green-600/20">
                    <i class="ph-fill ph-whatsapp-logo text-xl"></i> Konfirmasi Pembayaran
                </a>
            
            <?php else: ?>
                <a href="index.php" class="block w-full bg-slate-800 hover:bg-slate-700 text-white text-center py-3.5 rounded-xl font-bold transition">
                    Buat Pesanan Baru
                </a>
            <?php endif; ?>
            
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            var copyText = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(copyText).then(function() {
                alert("Nomor berhasil disalin!");
            });
        }
    </script>
</body>
</html>