<?php
require_once dirname(__DIR__) . '/auth.php';

$user = currentUser();
if ($user && $user['role'] === 'customer') {
    header('Location: index.php');
    exit;
}

$message = $databaseError ?: $accountTableError;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo && !$accountTableError) {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = 'Email dan kata sandi wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $account = $stmt->fetch();

        if ($account && $account['role'] === 'customer' && password_verify($password, $account['password_hash'])) {
            loginUser($account);
            header('Location: index.php');
            exit;
        }

        $message = 'Email atau kata sandi customer tidak valid.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer | Kuliner Sultan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-amber-50 flex items-center justify-center p-4">
    <main class="w-full max-w-md bg-white rounded-3xl shadow-lg border border-amber-100 p-8">
        <h1 class="text-2xl font-black text-stone-900">Masuk Customer</h1>
        <p class="text-sm text-stone-500 mt-2">Masuk untuk melanjutkan pesanan Anda.</p>
        <?php if ($message): ?>
            <p class="mt-5 rounded-xl bg-amber-50 border border-amber-200 p-3 text-sm text-amber-800"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="post" class="mt-6 space-y-4">
            <label class="block text-sm font-bold text-stone-700">Email
                <input type="email" name="email" required autocomplete="username" class="mt-1 w-full rounded-xl border border-stone-300 p-3">
            </label>
            <label class="block text-sm font-bold text-stone-700">Kata sandi
                <input type="password" name="password" required autocomplete="current-password" class="mt-1 w-full rounded-xl border border-stone-300 p-3">
            </label>
            <button type="submit" class="w-full rounded-xl bg-stone-900 p-3 font-bold text-white hover:bg-black">Masuk</button>
        </form>
        <p class="mt-5 text-center text-sm text-stone-600">Belum punya akun? <a href="register.php" class="font-bold text-red-600 hover:underline">Daftar</a></p>
        <a href="index.php" class="mt-2 block text-center text-sm text-stone-500 hover:underline">Kembali ke toko</a>
    </main>
</body>
</html>
