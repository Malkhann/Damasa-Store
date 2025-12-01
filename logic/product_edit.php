<?php
// admin/logic/product_edit.php

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

// Ambil Data Produk Saat Ini
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: products.php?game_id=$game_id");
    exit;
}

// Logic Update
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $promo_price = !empty($_POST['promo_price']) ? $_POST['promo_price'] : 0;
    
    // Handle Date (Jika kosong set NULL)
    $promo_date = !empty($_POST['promo_end_date']) ? $_POST['promo_end_date'] : NULL;

    try {
        $sql = "UPDATE products SET name=?, price=?, promo_price=?, promo_end_date=?, category=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $price, $promo_price, $promo_date, $category, $id]);
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Produk telah diperbarui.',
                    background: '#1e293b',
                    color: '#fff',
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    window.location.href='products.php?game_id=$game_id';
                });
              </script>";
        
        // Refresh data agar form terupdate tanpa reload
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();

    } catch(PDOException $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '" . $e->getMessage() . "',
                    background: '#1e293b', 
                    color: '#fff'
                });
              </script>";
    }
}
?>