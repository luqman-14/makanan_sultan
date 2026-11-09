<?php
require_once dirname(__DIR__) . '/auth.php';
requireRole('admin', 'login.php');

$message = '';
$messageType = '';

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_product') {
            $name = trim($_POST['name']);
            $category = trim($_POST['category']);
            $price = (int)$_POST['price'];
            $description = trim($_POST['description']);
            $image_url = trim($_POST['image_url']);

            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, description, image_url) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $category, $price, $description, $image_url])) {
                $message = "Produk berhasil ditambahkan!";
                $messageType = "success";
            }
        } elseif ($_POST['action'] === 'toggle_status') {
            $id = (int)$_POST['id'];
            $status = (int)$_POST['status'];
            $stmt = $pdo->prepare("UPDATE products SET is_available = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $message = "Status produk berhasil diperbarui!";
            $messageType = "info";
        } elseif ($_POST['action'] === 'delete_product') {
            $id = (int)$_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Produk berhasil dihapus!";
            $messageType = "danger";
        }
    }
}

$productsList = [];
$ordersList = [];

if ($pdo) {
    $productsList = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
    $ordersList = $pdo->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Kuliner Sultan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">
    <header class="bg-slate-900 text-white border-b border-slate-800 py-4 px-6 sticky top-0 z-30 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-500 flex items-center justify-center font-black text-slate-900">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <h1 class="font-extrabold text-lg tracking-wide">KulinerSultan <span class="text-xs bg-amber-500/20 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded-full uppercase ml-2">Admin Portal</span></h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs font-bold text-slate-400">Restoran Operations Dashboard</span>
            <a href="logout.php" class="text-xs font-extrabold text-amber-400 hover:text-amber-300">Keluar</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8">
        <?php if ($message): ?>
            <div class="p-4 rounded-2xl text-xs font-extrabold flex items-center justify-between <?php echo $messageType === 'success' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($messageType === 'danger' ? 'bg-red-100 text-red-800 border border-red-300' : 'bg-blue-100 text-blue-800 border border-blue-300'); ?>">
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-box"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Menu Produk</span>
                    <span class="text-2xl font-black text-slate-900"><?php echo count($productsList); ?></span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pesanan Masuk</span>
                    <span class="text-2xl font-black text-slate-900"><?php echo count($ordersList); ?></span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Status Koneksi DB</span>
                    <span class="text-xs font-black px-2.5 py-1 rounded-full inline-block mt-1 <?php echo $pdo ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'; ?>">
                        <?php echo $pdo ? 'Terhubung (MySQL)' : 'Mode Standalone'; ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <h2 class="text-lg font-black text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-amber-500"></i> Tambah Menu Kuliner Baru
            </h2>

            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <input type="hidden" name="action" value="add_product">
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Produk</label>
                    <input type="text" name="name" required placeholder="Contoh: Dimsum Mozzarella Mentai" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Kategori</label>
                    <select name="category" required class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="risol">Risol Mayo</option>
                        <option value="tahu">Tahu Kocek</option>
                        <option value="esteler">Es Teler Sultan</option>
                        <option value="dimsum">Dimsum Mentai</option>
                        <option value="frozen">Frozen & Ready</option>
                        <option value="combo">Paket Combo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" required placeholder="18000" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">URL Gambar (Image Link)</label>
                    <input type="url" name="image_url" required placeholder="https://images.unsplash.com/..." class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Singkat</label>
                    <input type="text" name="description" required placeholder="Penjelasan kelezatan bahan..." class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="md:col-span-2 lg:col-span-3 flex justify-end">
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold px-6 py-3 rounded-2xl text-xs uppercase tracking-wider shadow-md transition-all">
                        <i class="fa-solid fa-save mr-1"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-black text-slate-900">Katalog Produk Terdaftar</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-extrabold uppercase border-b border-slate-200">
                        <tr>
                            <th class="p-4">Foto</th>
                            <th class="p-4">Nama Menu</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($productsList as $prod): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-4">
                                    <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" class="w-12 h-12 rounded-xl object-cover">
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    <?php echo htmlspecialchars($prod['name']); ?>
                                </td>
                                <td class="p-4 uppercase font-semibold text-slate-500">
                                    <?php echo htmlspecialchars($prod['category']); ?>
                                </td>
                                <td class="p-4 font-black text-slate-900">
                                    <?php echo formatRupiah($prod['price']); ?>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase <?php echo $prod['is_available'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'; ?>">
                                        <?php echo $prod['is_available'] ? 'Tersedia' : 'Habis'; ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                            <input type="hidden" name="status" value="<?php echo $prod['is_available'] ? 0 : 1; ?>">
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-200 hover:bg-slate-300 font-bold text-[11px] transition-colors">
                                                Toggle Status
                                            </button>
                                        </form>

                                        <form method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-700 font-bold text-[11px] transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
