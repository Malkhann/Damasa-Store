<?php
// logic/checkout.php
if (!isset($_POST['beli'])) { header("Location: index.php"); exit; }

$game_id = $_POST['game_id'];
$user_id = $_POST['user_id'];
$zone_id = $_POST['zone_id'] ?? '';
$product_raw = $_POST['product_code']; 
$payment_name = $_POST['payment'];
$email = $_POST['email'];
$quantity = max(1, (int)$_POST['quantity']);

$product_data = explode('|', $product_raw);
$product_price = $product_data[1];
$product_name = $product_data[2];
$total_bayar = $product_price * $quantity;

// Ambil Data Game & Payment via PDO
$stmt_game = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt_game->execute([$game_id]);
$game = $stmt_game->fetch();

$stmt_pay = $conn->prepare("SELECT * FROM payment_methods WHERE name = ?");
$stmt_pay->execute([$payment_name]);
$pay_info = $stmt_pay->fetch();
?>