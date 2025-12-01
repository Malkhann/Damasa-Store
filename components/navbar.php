<?php 
// components/navbar.php
// Memanggil logika PHP
require_once 'navbar/logic.php'; 
?>

<nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-white/90 dark:bg-slate-950/90 backdrop-blur-md border-b border-gray-200 dark:border-white/5 shadow-sm">
    
    <?php 
    // Memanggil Tampilan Utama (Logo & Desktop Menu)
    include 'navbar/content.php'; 
    
    // Memanggil Menu Mobile (Hidden by default)
    include 'navbar/mobile-menu.php'; 
    ?>

</nav>

<?php 
// Memanggil Script Javascript
include 'navbar/scripts.php'; 
?>