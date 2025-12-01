<?php
// admin/api_products.php
include '../config/database.php';

if(isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
    $query = mysqli_query($conn, "SELECT * FROM products WHERE game_id='$game_id' ORDER BY price ASC");
    
    $data = [];
    while($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
}
?>