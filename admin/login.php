<?php //admin/login.php
session_start();
include '../config/database.php';

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $data = $stmt->fetch();

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-slate-800 p-8 rounded-2xl border border-slate-700">
        <h1 class="text-2xl font-bold text-center mb-6">Damasa<span class="text-blue-500">Admin</span></h1>
        <?php if(isset($error)): ?>
            <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm text-center"><?= $error; ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-4">
            <div>
                <label class="text-sm text-slate-400">Username</label>
                <input type="text" name="username" class="w-full bg-slate-950 border border-slate-600 rounded-xl p-3 text-white focus:border-blue-500 outline-none" required>
            </div>
            <div>
                <label class="text-sm text-slate-400">Password</label>
                <input type="password" name="password" class="w-full bg-slate-950 border border-slate-600 rounded-xl p-3 text-white focus:border-blue-500 outline-none" required>
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition">Masuk</button>
        </form>
    </div>
</body>
</html>