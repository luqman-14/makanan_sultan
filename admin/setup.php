<?php
require_once dirname(__DIR__) . '/auth.php';

$message = $databaseError ?: $accountTableError;
$adminExists = false;
$createdAdmin = null;

if ($pdo && !$accountTableError) {
    $adminExists = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn() > 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$adminExists) {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $lockAcquired = false;

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) {
            $message = 'Isi nama dan email yang valid. Kata sandi admin minimal 10 karakter.';
        } else {
            try {
                $lock = $pdo->query("SELECT GET_LOCK('kuliner_sultan_first_admin', 5)")->fetchColumn();
                $lockAcquired = (int) $lock === 1;

                if (!$lockAcquired) {
                    $message = 'Setup sedang digunakan. Silakan coba kembali.';
                } elseif ((int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn() > 0) {
                    $adminExists = true;
                    $message = 'Setup telah ditutup karena akun admin sudah tersedia.';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
                    $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                    $createdAdmin = [
                        'id' => (int) $pdo->lastInsertId(),
                        'name' => $name,
                        'role' => 'admin'
                    ];
                    $adminExists = true;
                }
            } catch (PDOException $e) {
                error_log('Initial admin setup failed: ' . $e->getMessage());
                $message = $e->getCode() === '23000'
                    ? 'Email tersebut sudah digunakan.'
                    : 'Setup admin gagal. Periksa koneksi dan skema database.';
            } finally {
                if ($lockAcquired) {
                    $pdo->query("SELECT RELEASE_LOCK('kuliner_sultan_first_admin')");
                }
            }
        }
    }
}

if ($createdAdmin) {
    loginUser($createdAdmin);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin | Kuliner Sultan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <main class="w-full max-w-md bg-white rounded-3xl shadow-lg border border-slate-200 p-8">
        <h1 class="text-2xl font-black text-slate-900">Setup Admin Pertama</h1>
        <p class="text-sm text-slate-500 mt-2">Halaman ini hanya aktif sebelum admin pertama dibuat.</p>
        <?php if ($message): ?>
            <p class="mt-5 rounded-xl bg-amber-50 border border-amber-200 p-3 text-sm text-amber-800"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php if (!$adminExists && $pdo && !$accountTableError): ?>
            <form method="post" class="mt-6 space-y-4">
                <label class="block text-sm font-bold text-slate-700">Nama
                    <input type="text" name="name" required maxlength="100" autocomplete="name" class="mt-1 w-full rounded-xl border border-slate-300 p-3">
                </label>
                <label class="block text-sm font-bold text-slate-700">Email
                    <input type="email" name="email" required autocomplete="username" class="mt-1 w-full rounded-xl border border-slate-300 p-3">
                </label>
                <label class="block text-sm font-bold text-slate-700">Kata sandi (minimal 10 karakter)
                    <input type="password" name="password" required minlength="10" autocomplete="new-password" class="mt-1 w-full rounded-xl border border-slate-300 p-3">
                </label>
                <button type="submit" class="w-full rounded-xl bg-amber-500 p-3 font-bold text-slate-900 hover:bg-amber-400">Buat Admin</button>
            </form>
        <?php elseif ($adminExists): ?>
            <a href="login.php" class="mt-6 block text-center font-bold text-amber-700 hover:underline">Lanjut ke login admin</a>
        <?php endif; ?>
    </main>
</body>
</html>
