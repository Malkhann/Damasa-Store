<?php
// components/navbar/logic.php

// Cek Active Page untuk styling
$current_page = basename($_SERVER['PHP_SELF']);

function isActive($page_name, $current) {
    return ($current == $page_name) ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white';
}

// Ambil Data User (Jika Login)
$is_logged_in = isset($_SESSION['user_logged_in']);
$user_avatar = $is_logged_in ? $_SESSION['user_avatar'] : 'https://ui-avatars.com/api/?name=User&background=random';
$user_name = $is_logged_in ? explode(' ', $_SESSION['user_name'])[0] : 'Guest';
?>