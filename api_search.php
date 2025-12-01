<?php
// api_search.php
include 'config/database.php';

if(isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conn, $_GET['query']);
    $query = mysqli_query($conn, "SELECT * FROM games WHERE name LIKE '%$search%' LIMIT 5");
    
    $results = [];
    while($row = mysqli_fetch_assoc($query)) {
        $results[] = [
            'name' => $row['name'],
            'slug' => $row['slug'],
            'thumbnail' => $row['thumbnail'],
            'category' => explode(',', $row['category'])[0] // Ambil kategori pertama
        ];
    }
    echo json_encode($results);
}
?>