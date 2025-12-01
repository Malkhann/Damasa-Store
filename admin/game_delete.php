<?php
session_start();
include '../config/database.php';

// 1. Cek Login Admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// 2. Cek apakah ada ID di URL?
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // A. AMBIL DATA DULU (Untuk tahu nama file gambarnya)
    $query_cek = mysqli_query($conn, "SELECT thumbnail FROM games WHERE id='$id'");
    $data = mysqli_fetch_assoc($query_cek);

    // Jika data ditemukan
    if ($data) {
        // B. HAPUS FILE GAMBAR DARI FOLDER
        // Pastikan path-nya mengarah ke folder 'games' yang baru
        $gambar_lama = "../assets/uploads/games/" . $data['thumbnail'];
        
        if (file_exists($gambar_lama) && !empty($data['thumbnail'])) {
            unlink($gambar_lama); // Fungsi PHP untuk menghapus file fisik
        }

        // C. HAPUS DATA DARI DATABASE
        // (Otomatis menghapus produk di dalamnya jika settingan database ON DELETE CASCADE)
        // Jika tidak cascade, produk harus dihapus manual dulu: 
        // mysqli_query($conn, "DELETE FROM products WHERE game_id='$id'");
        
        $delete = mysqli_query($conn, "DELETE FROM games WHERE id='$id'");

        if ($delete) {
            echo "<script>
                    alert('Game berhasil dihapus!'); 
                    window.location.href='index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data dari database.'); 
                    window.location.href='index.php';
                  </script>";
        }

    } else {
        echo "<script>alert('Data game tidak ditemukan.'); window.location.href='index.php';</script>";
    }

} else {
    // Jika tidak ada ID, kembalikan ke dashboard
    header("Location: index.php");
}
?>