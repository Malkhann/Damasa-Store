<?php
// logic/profile.php

// Cek Login
if (!isset($_SESSION['user_logged_in'])) { 
    header("Location: login.php"); 
    exit; 
}
$user_id = $_SESSION['user_id'];

// Logic Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Logic Update Profil
if (isset($_POST['update_profile'])) {
    $name  = $_POST['name'];
    $phone = $_POST['phone'];
    $avatar = $_POST['avatar'];
    $pass  = $_POST['password'];
    
    try {
        if (!empty($pass)) {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, avatar=?, password=? WHERE id=?");
            $stmt->execute([$name, $phone, $avatar, $hashed, $user_id]);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, avatar=? WHERE id=?");
            $stmt->execute([$name, $phone, $avatar, $user_id]);
        }
        
        // Update Session biar nama/foto di navbar langsung berubah
        $_SESSION['user_name'] = $name;
        $_SESSION['user_avatar'] = $avatar;
        
        echo "<script>alert('Profil Berhasil Diupdate!'); window.location.href='profile.php';</script>";
    } catch(PDOException $e) {
        echo "<script>alert('Gagal Update: " . $e->getMessage() . "');</script>";
    }
}

// Ambil Data User Terbaru
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Hitung Total Transaksi Sukses
$stmt_stat_trx = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE user_id = ? AND status = 'success'");
$stmt_stat_trx->execute([$user_id]);
$total_trx = $stmt_stat_trx->fetchColumn();

// Hitung Total Pengeluaran
$stmt_stat_spent = $conn->prepare("SELECT SUM(amount) FROM transactions WHERE user_id = ? AND status = 'success'");
$stmt_stat_spent->execute([$user_id]);
$total_spent = $stmt_stat_spent->fetchColumn() ?: 0;

// Ambil 10 Riwayat Terakhir
$stmt_hist = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt_hist->execute([$user_id]);
$history = $stmt_hist->fetchAll();
?>