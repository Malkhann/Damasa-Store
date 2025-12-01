<?php
// logic/detail.php
if (!isset($_GET['slug'])) {
    header("Location: index.php");
    exit;
}
$slug = $_GET['slug'];

// Ambil Data Game
$stmt = $conn->prepare("SELECT * FROM games WHERE slug = ?");
$stmt->execute([$slug]);
$game = $stmt->fetch();

if (!$game) {
    header("Location: index.php");
    exit;
}

// Ambil Produk
$stmt_prod = $conn->prepare("SELECT * FROM products WHERE game_id = ? ORDER BY price ASC");
$stmt_prod->execute([$game['id']]);
$products = $stmt_prod->fetchAll();

// Filter Kategori & Promo
$categories = [];
$promos = [];
$current_time = date('Y-m-d H:i:s');

foreach ($products as $item) {
    if ($item['promo_price'] > 0 && $item['promo_end_date'] > $current_time) {
        $promos[] = $item;
    } else {
        $cat = $item['category'] ?: 'Lainnya';
        $categories[$cat][] = $item;
    }
}

// Ambil Metode Pembayaran
$stmt_pay = $conn->query("SELECT * FROM payment_methods ORDER BY category DESC, id ASC");
$payment_rows = $stmt_pay->fetchAll();
$payments = [];
foreach ($payment_rows as $row) {
    $cat = $row['category'] ?: 'Lainnya';
    $payments[$cat][] = $row;
}

// Data User Login
$user_email = isset($_SESSION['user_logged_in']) ? $conn->query("SELECT email FROM users WHERE id=" . $_SESSION['user_id'])->fetchColumn() : "";
?>