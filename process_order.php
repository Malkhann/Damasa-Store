<?php //process_order.php
session_start();
include 'config/database.php';

if (isset($_POST['beli'])) {
    $invoice = "INV/" . date('Ymd') . "/" . rand(1000,9999);
    $email   = $_POST['email'];
    $game_id = $_POST['game_id'];
    $user_game_id = $_POST['user_id'];
    $zone_id = $_POST['zone_id'] ?? '';
    $payment = $_POST['payment'];
    $product_name = $_POST['product_name'];
    $amount  = $_POST['total_amount'];
    $qty     = $_POST['quantity'];
    $uid     = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

    try {
        $sql = "INSERT INTO transactions (invoice_id, user_id, email_buyer, game_id, product_name, amount, quantity, payment_method, game_user_id, game_zone_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$invoice, $uid, $email, $game_id, $product_name, $amount, $qty, $payment, $user_game_id, $zone_id]);
        
        header("Location: invoice.php?inv=$invoice");
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
}
?>