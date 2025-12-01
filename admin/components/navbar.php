<?php 
// admin/components/navbar.php

// 1. LOGIC HALAMAN AKTIF
$current_page = basename($_SERVER['PHP_SELF']);

function isActive($page_name, $current) {
    if ($current == $page_name) {
        return 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 border-blue-500';
    }
    return 'text-slate-400 hover:text-white hover:bg-slate-800 border-transparent';
}

// 2. LOGIC NOTIFIKASI (PDO)
// Ambil jumlah pending
$stmt_count = $conn->query("SELECT COUNT(*) FROM transactions WHERE status='pending'");
$pending_count = $stmt_count->fetchColumn();

// Ambil data pending untuk list
$stmt_list = $conn->query("SELECT * FROM transactions WHERE status='pending' ORDER BY created_at DESC LIMIT 5");

// 3. AMBIL DATA ADMIN (Untuk Profil)
$admin_name = "Administrator"; // Default
if(isset($_SESSION['admin_username'])) {
    $admin_name = $_SESSION['admin_username'];
}
?>

<nav class="fixed top-0 left-0 w-full z-[50] bg-slate-950/90 backdrop-blur-xl border-b border-slate-800/50 transition-all duration-300">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex items-center justify-between h-20 relative">
            
            <div class="flex items-center gap-3 min-w-fit">
                <a href="index.php" class="group flex items-center gap-3">
                    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/20 transition-transform duration-300 group-hover:scale-110">
                        <i class="ph-bold ph-cube text-xl"></i>
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-slate-900"></span>
                        </span>
                    </div>
                    <div class="hidden sm:flex flex-col">
                        <span class="text-lg font-bold tracking-wide text-white leading-none">Damasa<span class="text-blue-500">Panel</span></span>
                        <span class="text-[10px] font-medium text-slate-500 uppercase tracking-widest mt-0.5">Administrator</span>
                    </div>
                </a>
            </div>

            <div class="hidden md:flex flex-1 items-center justify-center px-4">
                <div class="flex items-center gap-1 bg-slate-900/50 p-1.5 rounded-2xl border border-slate-800 backdrop-blur-md shadow-inner">
                    <a href="index.php" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 border flex items-center gap-2 whitespace-nowrap <?= isActive('index.php', $current_page); ?>">
                        <i class="ph-fill ph-game-controller text-lg"></i> Produk
                    </a>
                    <a href="payment.php" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 border flex items-center gap-2 whitespace-nowrap <?= isActive('payment.php', $current_page); ?>">
                        <i class="ph-fill ph-wallet text-lg"></i> Pembayaran
                    </a>
                    <a href="transactions.php" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 border flex items-center gap-2 whitespace-nowrap <?= isActive('transactions.php', $current_page); ?>">
                        <i class="ph-fill ph-receipt text-lg"></i> Transaksi
                    </a>
                    <a href="content.php" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 border flex items-center gap-2 whitespace-nowrap <?= isActive('content.php', $current_page); ?>">
                        <i class="ph-fill ph-monitor-play text-lg"></i> Konten
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4 min-w-fit justify-end">
                
                <div class="relative" id="notif-container">
                    <button id="notif-btn" type="button" class="relative w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition flex items-center justify-center hover:shadow-lg active:scale-95">
                        <i class="ph-bold ph-bell text-xl"></i>
                        <?php if($pending_count > 0): ?>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full border-2 border-slate-950 text-[10px] font-bold text-white flex items-center justify-center animate-bounce">
                                <?= $pending_count > 9 ? '9+' : $pending_count; ?>
                            </span>
                        <?php endif; ?>
                    </button>

                    <div id="notif-dropdown" class="hidden absolute right-0 top-full mt-4 w-80 bg-slate-900/95 backdrop-blur-xl border border-slate-700/50 rounded-2xl shadow-2xl transform origin-top-right z-[100] overflow-hidden ring-1 ring-white/10">
                        <div class="px-4 py-3 border-b border-slate-800 bg-slate-950/50 flex justify-between items-center">
                            <span class="text-xs font-bold text-white uppercase tracking-wider">Pesanan Masuk</span>
                            <?php if($pending_count > 0): ?>
                                <span class="text-[10px] bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-full border border-blue-500/30"><?= $pending_count; ?> Menunggu</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="max-h-[350px] overflow-y-auto custom-scrollbar">
                            <?php if($pending_count == 0): ?>
                                <div class="p-8 text-center text-slate-500">
                                    <i class="ph-duotone ph-check-circle text-4xl mb-2 opacity-50"></i>
                                    <p class="text-xs">Semua aman terkendali.</p>
                                </div>
                            <?php else: ?>
                                <?php while($trx = $stmt_list->fetch()): ?>
                                <div class="p-3 border-b border-slate-800/50 hover:bg-slate-800/30 transition group/item">
                                    <div class="flex justify-between items-start mb-1">
                                        <a href="invoice.php?inv=<?= $trx['invoice_id']; ?>" class="text-xs font-mono text-blue-400 font-bold hover:underline">#<?= substr($trx['invoice_id'], -5); ?></a>
                                        <span class="text-[10px] text-slate-500"><?= date('H:i', strtotime($trx['created_at'])); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="text-sm font-bold text-white truncate w-32" title="<?= $trx['product_name']; ?>"><?= $trx['product_name']; ?></div>
                                        <div class="text-xs font-mono text-green-400 bg-green-900/20 px-1.5 py-0.5 rounded border border-green-900/30">Rp <?= number_format($trx['amount']/1000); ?>k</div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <button type="button" onclick="navbarAction('<?= $trx['invoice_id']; ?>', 'success')" class="flex-1 bg-green-600 hover:bg-green-500 text-white py-1.5 rounded-lg text-[10px] font-bold shadow-lg shadow-green-900/20 transition active:scale-95 flex items-center justify-center gap-1">
                                            <i class="ph-bold ph-check"></i> Terima
                                        </button>
                                        <button type="button" onclick="navbarAction('<?= $trx['invoice_id']; ?>', 'failed')" class="flex-1 bg-red-600 hover:bg-red-500 text-white py-1.5 rounded-lg text-[10px] font-bold shadow-lg shadow-red-900/20 transition active:scale-95 flex items-center justify-center gap-1">
                                            <i class="ph-bold ph-x"></i> Tolak
                                        </button>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                        
                        <a href="transactions.php" class="block text-center py-3 text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800 transition border-t border-slate-800">
                            Lihat Semua Transaksi
                        </a>
                    </div>
                </div>

                <div class="relative" id="profile-container">
                    <button id="profile-btn" type="button" class="flex items-center gap-3 pl-1 pr-3 py-1 rounded-full bg-slate-900 border border-slate-800 hover:border-blue-500/50 hover:shadow-lg transition duration-300">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-xs font-bold text-white shadow-inner ring-2 ring-slate-900">
                            <?= substr($admin_name, 0, 1); ?>
                        </div>
                        <i class="ph-bold ph-caret-down text-slate-500 text-xs"></i>
                    </button>
                    
                    <div id="profile-dropdown" class="hidden absolute right-0 top-full mt-4 w-48 bg-slate-900/95 backdrop-blur-xl border border-slate-700/50 rounded-2xl shadow-2xl transform origin-top-right z-[60] overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-800 bg-gradient-to-br from-slate-800/50 to-slate-900/50">
                            <p class="text-sm font-bold text-white"><?= $admin_name; ?></p>
                            <p class="text-[10px] text-slate-400">Administrator</p>
                        </div>
                        <div class="p-1">
                            <a href="index.php?logout=true" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-red-400 hover:text-white hover:bg-red-500/10 rounded-xl transition">
                                <i class="ph-bold ph-sign-out text-lg"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <button id="mobile-menu-btn" class="md:hidden relative w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 border border-slate-800 text-white hover:bg-slate-800 transition z-50">
                <i class="ph-bold ph-list text-2xl transition-transform duration-300"></i>
            </button>

        </div>
    </div>

    <form id="navbarActionForm" action="transactions.php" method="POST" class="hidden">
        <input type="hidden" name="action_type" value="update_trx">
        <input type="hidden" name="invoice_id" id="navInvId">
        <input type="hidden" name="new_status" id="navNewStatus">
        <input type="hidden" name="return_url" value="<?= $_SERVER['REQUEST_URI']; ?>">
    </form>

    <div id="mobile-menu" class="hidden md:hidden fixed inset-x-0 top-[80px] bg-slate-950/95 backdrop-blur-2xl border-b border-slate-800 p-4 shadow-2xl h-[calc(100vh-80px)] overflow-y-auto">
        <div class="grid grid-cols-2 gap-2 mb-4">
            <a href="index.php" class="flex flex-col items-center justify-center p-4 bg-slate-900 rounded-xl border border-slate-800 <?= isActive('index.php', $current_page); ?>">
                <i class="ph-fill ph-game-controller text-2xl mb-1"></i>
                <span class="text-xs">Produk</span>
            </a>
            <a href="transactions.php" class="flex flex-col items-center justify-center p-4 bg-slate-900 rounded-xl border border-slate-800 <?= isActive('transactions.php', $current_page); ?>">
                <i class="ph-fill ph-receipt text-2xl mb-1"></i>
                <span class="text-xs">Transaksi</span>
            </a>
            <a href="payment.php" class="flex flex-col items-center justify-center p-4 bg-slate-900 rounded-xl border border-slate-800 <?= isActive('payment.php', $current_page); ?>">
                <i class="ph-fill ph-wallet text-2xl mb-1"></i>
                <span class="text-xs">Metode</span>
            </a>
            <a href="content.php" class="flex flex-col items-center justify-center p-4 bg-slate-900 rounded-xl border border-slate-800 <?= isActive('content.php', $current_page); ?>">
                <i class="ph-fill ph-monitor-play text-2xl mb-1"></i>
                <span class="text-xs">Konten</span>
            </a>
        </div>
        <a href="index.php?logout=true" class="block w-full bg-red-900/20 text-red-400 text-center py-3 rounded-xl font-bold text-sm border border-red-900/30">Logout</a>
    </div>
</nav>

<script>
// Pastikan fungsi ada di Global Scope (window)
window.navbarAction = function(id, status) {
    Swal.fire({
        title: (status === 'success') ? 'Terima Pesanan?' : 'Tolak Pesanan?',
        text: "Status transaksi akan diperbarui.",
        icon: 'question',
        showCancelButton: true,
        background: '#1e293b', 
        color: '#fff',
        confirmButtonColor: (status === 'success') ? '#16a34a' : '#dc2626',
        cancelButtonColor: '#334155',
        confirmButtonText: 'Ya, Proses!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('navbarActionForm');
            if(form) {
                document.getElementById('navInvId').value = id;
                document.getElementById('navNewStatus').value = status;
                form.submit();
            } else {
                alert('Error: Form not found');
            }
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    // Fungsi Toggle Generic
    function setupDropdown(btnId, dropdownId, containerId) {
        const btn = document.getElementById(btnId);
        const dropdown = document.getElementById(dropdownId);
        const container = document.getElementById(containerId) || btn.parentElement;

        if (btn && dropdown) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                // Tutup dropdown lain
                document.querySelectorAll('[id$="-dropdown"]').forEach(el => {
                    if(el.id !== dropdownId) el.classList.add('hidden');
                });
                dropdown.classList.toggle('hidden');
            });

            // Klik di luar tutup dropdown
            document.addEventListener('click', (e) => {
                if (!container.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    }

    setupDropdown('notif-btn', 'notif-dropdown', 'notif-container');
    setupDropdown('profile-btn', 'profile-dropdown', 'profile-container');

    // Mobile Menu
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const icon = mobileBtn.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.replace('ph-x', 'ph-list');
            } else {
                icon.classList.replace('ph-list', 'ph-x');
            }
        });
    }
});
</script>