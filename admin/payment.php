<?php //admin/payment.php
session_start();
include '../config/database.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

if (isset($_POST['save'])) {
    $mode = $_POST['mode']; $id = $_POST['id'];
    $name = $_POST['name']; $cat = $_POST['category'];
    $rek = $_POST['account_number']; $an = $_POST['account_holder'];
    $logo = $_POST['logo']; $qr = $_POST['qr_image'];

    if ($mode == 'add') {
        $stmt = $conn->prepare("INSERT INTO payment_methods (name, category, account_number, account_holder, logo, qr_image) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$name, $cat, $rek, $an, $logo, $qr]);
    } else {
        $stmt = $conn->prepare("UPDATE payment_methods SET name=?, category=?, account_number=?, account_holder=?, logo=?, qr_image=? WHERE id=?");
        $stmt->execute([$name, $cat, $rek, $an, $logo, $qr, $id]);
    }
    header("Location: payment.php"); exit;
}

if(isset($_GET['del'])){
    $conn->prepare("DELETE FROM payment_methods WHERE id=?")->execute([$_GET['del']]);
    header("Location: payment.php"); exit;
}
$payments = $conn->query("SELECT * FROM payment_methods")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Metode Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-white min-h-screen pt-24 px-4">
    <?php include 'components/navbar.php'; ?>
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Pembayaran</h1>
            <button onclick="openModal('add')" class="bg-blue-600 px-4 py-2 rounded-xl font-bold">Tambah</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach($payments as $p): ?>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl relative group">
                <div class="flex items-center gap-3 mb-3">
                    <img src="<?= $p['logo']; ?>" class="h-8 bg-white rounded p-1">
                    <span class="font-bold"><?= $p['name']; ?></span>
                </div>
                <p class="text-xs text-slate-400"><?= $p['account_number']; ?></p>
                <p class="text-xs text-slate-500"><?= $p['account_holder']; ?></p>
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition flex gap-2">
                    <button onclick='openModal("edit", <?= json_encode($p); ?>)' class="bg-blue-600 p-1.5 rounded"><i class="ph-bold ph-pencil"></i></button>
                    <a href="?del=<?= $p['id']; ?>" class="bg-red-600 p-1.5 rounded" onclick="return confirm('Hapus?')"><i class="ph-bold ph-trash"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="payModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-slate-900 p-6 rounded-2xl w-full max-w-lg border border-slate-700">
            <h2 class="font-bold mb-4" id="modalTitle">Tambah Metode</h2>
            <form method="post" class="space-y-3">
                <input type="hidden" name="save" value="1"><input type="hidden" name="mode" id="mode"><input type="hidden" name="id" id="pid">
                <input type="text" name="name" id="pName" placeholder="Nama (e.g. BCA)" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none" required>
                <select name="category" id="pCat" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none">
                    <option value="E-Wallet">E-Wallet</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Virtual Account">Virtual Account</option>
                </select>
                <input type="text" name="account_number" id="pRek" placeholder="No Rekening" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none">
                <input type="text" name="account_holder" id="pAn" placeholder="Atas Nama" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none">
                <input type="url" name="logo" id="pLogo" placeholder="URL Logo" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none">
                <input type="url" name="qr_image" id="pQr" placeholder="URL QRIS (Opsional)" class="w-full bg-slate-950 p-3 rounded-xl border border-slate-700 text-white outline-none">
                <button type="submit" class="w-full bg-blue-600 py-3 rounded-xl font-bold mt-2">Simpan</button>
                <button type="button" onclick="document.getElementById('payModal').classList.add('hidden')" class="w-full bg-slate-800 py-3 rounded-xl mt-2">Batal</button>
            </form>
        </div>
    </div>
    <script>
        function openModal(mode, data=null){
            document.getElementById('payModal').classList.remove('hidden');
            document.getElementById('payModal').classList.add('flex');
            document.getElementById('mode').value = mode;
            if(mode==='edit'){
                document.getElementById('modalTitle').innerText = "Edit Metode";
                document.getElementById('pid').value = data.id;
                document.getElementById('pName').value = data.name;
                document.getElementById('pCat').value = data.category;
                document.getElementById('pRek').value = data.account_number;
                document.getElementById('pAn').value = data.account_holder;
                document.getElementById('pLogo').value = data.logo;
                document.getElementById('pQr').value = data.qr_image;
            } else {
                document.querySelector('form').reset();
                document.getElementById('modalTitle').innerText = "Tambah Metode";
            }
        }
    </script>
</body>
</html>