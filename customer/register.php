<?php
require_once dirname(__DIR__) . '/auth.php';

$user = currentUser();
if ($user && $user['role'] === 'customer') {
    header('Location: index.php');
    exit;
}

$message = $databaseError ?: $accountTableError;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo && !$accountTableError) {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($name === '' || strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Nama dan alamat email harus valid.';
    } elseif (strlen($password) < 8) {
        $message = 'Kata sandi harus memiliki minimal 8 karakter.';
    } elseif ($password !== $passwordConfirm) {
        $message = 'Konfirmasi kata sandi tidak cocok.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'customer')");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            loginUser([
                'id' => (int) $pdo->lastInsertId(),
                'name' => $name,
                'role' => 'customer'
            ]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            error_log('Customer registration failed: ' . $e->getMessage());
            $message = $e->getCode() === '23000'
                ? 'Email sudah terdaftar. Silakan masuk.'
                : 'Pendaftaran gagal. Periksa koneksi database lalu coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Customer | Kuliner Sultan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-amber-50 flex items-center justify-center p-4">
    <main class="w-full max-w-md bg-white rounded-3xl shadow-lg border border-amber-100 p-8">
        <h1 class="text-2xl font-black text-stone-900">Daftar Customer</h1>
        <p class="text-sm text-stone-500 mt-2">Buat akun untuk memesan dari Kuliner Sultan.</p>
        <?php if ($message): ?>
            <p class="mt-5 rounded-xl bg-amber-50 border border-amber-200 p-3 text-sm text-amber-800"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php if ($pdo && !$accountTableError): ?>
        <form method="post" class="mt-6 space-y-4">
            <label class="block text-sm font-bold text-stone-700">Nama
                <input type="text" name="name" required maxlength="100" autocomplete="name" class="mt-1 w-full rounded-xl border border-stone-300 p-3">
            </label>
            <label class="block text-sm font-bold text-stone-700">Email
                <input type="email" name="email" required autocomplete="email" class="mt-1 w-full rounded-xl border border-stone-300 p-3">
            </label>
            <label class="block text-sm font-bold text-stone-700">Kata sandi (minimal 8 karakter)
                <input type="password" name="password" required minlength="8" autocomplete="new-password" class="mt-1 w-full rounded-xl border border-stone-300 p-3">
            </label>
            <label class="block text-sm font-bold text-stone-700">Konfirmasi kata sandi
                <input type="password" name="password_confirm" required minlength="8" autocomplete="new-password" class="mt-1 w-full rounded-xl border border-stone-300 p-3">
            </label>
            <button type="submit" class="w-full rounded-xl bg-red-600 p-3 font-bold text-white hover:bg-red-700">Buat Akun</button>
        </form>
        <?php endif; ?>
        <p class="mt-5 text-center text-sm text-stone-600">Sudah punya akun? <a href="login.php" class="font-bold text-red-600 hover:underline">Masuk</a></p>
    </main>
</body>
</html>
