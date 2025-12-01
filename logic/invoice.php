<?php
// logic/invoice.php
if (!isset($_GET['inv'])) { header("Location: index.php"); exit; }
$invoice = $_GET['inv'];

$stmt = $conn->prepare("SELECT * FROM transactions WHERE invoice_id = ?");
$stmt->execute([$invoice]);
$trx = $stmt->fetch();

if (!$trx) { header("Location: index.php"); exit; }

$stmt_pay = $conn->prepare("SELECT * FROM payment_methods WHERE name = ?");
$stmt_pay->execute([$trx['payment_method']]);
$pay_detail = $stmt_pay->fetch();

$status_config = [
    'pending' => ['bg-yellow-500/10', 'text-yellow-500', 'border-yellow-500/20', 'ph-hourglass', 'Menunggu Pembayaran'],
    'success' => ['bg-green-500/10', 'text-green-500', 'border-green-500/20', 'ph-check-circle', 'Pembayaran Berhasil'],
    'failed' => ['bg-red-500/10', 'text-red-500', 'border-red-500/20', 'ph-x-circle', 'Gagal']
];
$st = $status_config[$trx['status']];
?>