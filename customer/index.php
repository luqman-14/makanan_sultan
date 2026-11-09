<?php
require_once dirname(__DIR__) . '/auth.php';
$currentUser = currentUser();

$products = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM products WHERE is_available = 1 ORDER BY id DESC");
    $products = $stmt->fetchAll();
} else {
    $products = [
        ['id' => 1, 'name' => 'Risol Mayo Smoked Beef Lumer', 'category' => 'risol', 'price' => 18000, 'rating' => 4.9, 'description' => 'Risol gurih isi smoked beef premium, telur, dan racikan keju mayones melimpah.', 'image_url' => 'https://images.unsplash.com/photo-1541529086526-db283c563270?auto=format&fit=crop&w=600&q=80'],
        ['id' => 2, 'name' => 'Tahu Kocek Rawit Setan (Porsi Jumbo)', 'category' => 'tahu', 'price' => 15000, 'rating' => 4.8, 'description' => 'Tahu goreng krispi dikocek dengan ulekan cabai rawit merah asli dan bumbu gurih sultan.', 'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80'],
        ['id' => 3, 'name' => 'Es Teler Sultan Durian Musang King', 'category' => 'esteler', 'price' => 25000, 'rating' => 5.0, 'description' => 'Es teler racikan krimer premium dengan nangka, kelapa muda, alpukat, dan toping durian asli.', 'image_url' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=600&q=80'],
        ['id' => 4, 'name' => 'Dimsum Mentai Flame-Torched (4 pcs)', 'category' => 'dimsum', 'price' => 22000, 'rating' => 4.9, 'description' => 'Dimsum ayam udang tebal disiram saus mentai gurih pedas dan dibakar dengan torch.', 'image_url' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=600&q=80'],
        ['id' => 5, 'name' => 'Paket Frozen Risol Mayo (Isi 10)', 'category' => 'frozen', 'price' => 45000, 'rating' => 4.9, 'description' => 'Paket mentah siap goreng kapan saja di rumah. Tahan 1 bulan di freezer.', 'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=600&q=80'],
        ['id' => 6, 'name' => 'Combo Sultan Kenyang (Risol + Dimsum + Es Teler)', 'category' => 'combo', 'price' => 55000, 'rating' => 5.0, 'description' => 'Paket komplit 1 Risol Mayo + 1 Dimsum Mentai + 1 Es Teler Durian.', 'image_url' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=600&q=80']
    ];
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuliner Sultan | Storefront</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#FDFBF7', 100: '#F5EFE6', 500: '#F59E0B', 600: '#D97706', 700: '#B45309' },
                        spicy: { 100: '#FEE2E2', 500: '#EF4444', 600: '#DC2626', 700: '#B91C1C' },
                        charcoal: { 800: '#262322', 900: '#1C1917' }
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-50 text-charcoal-900 font-sans antialiased min-h-screen flex flex-col selection:bg-spicy-600 selection:text-white">

    <header class="sticky top-0 z-40 glass-header border-b border-amber-200/60 transition-all shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-spicy-600 via-brand-500 to-amber-400 flex items-center justify-center text-white text-xl shadow-md shadow-spicy-600/30 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
                <div>
                    <span class="font-black text-xl sm:text-2xl tracking-tight block leading-none text-charcoal-900">
                        Kuliner<span class="text-spicy-600">Sultan</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700">Customer Storefront</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 font-bold text-xs uppercase tracking-wider text-stone-700">
                <a href="#hero" class="hover:text-spicy-600 transition-colors">Beranda</a>
                <a href="#katalog" class="hover:text-spicy-600 transition-colors">Menu Sultan</a>
                <a href="#testimoni" class="hover:text-spicy-600 transition-colors">Ulasan</a>
                <a href="#faq" class="hover:text-spicy-600 transition-colors">FAQ</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="https://wa.me/6281234567890" target="_blank" class="hidden sm:flex items-center gap-2 text-xs font-extrabold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/80 px-3.5 py-2.5 rounded-2xl transition-colors">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Tanya Admin</span>
                </a>
                <?php if ($currentUser && $currentUser['role'] === 'customer'): ?>
                    <span class="hidden lg:inline text-xs font-bold text-stone-600">Halo, <?php echo htmlspecialchars($currentUser['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <a href="logout.php" class="text-xs font-extrabold text-spicy-600 hover:text-spicy-700">Keluar</a>
                <?php else: ?>
                    <a href="login.php" class="text-xs font-extrabold text-charcoal-900 hover:text-spicy-600">Masuk</a>
                    <a href="register.php" class="text-xs font-extrabold bg-spicy-600 hover:bg-spicy-700 text-white px-3 py-2 rounded-xl">Daftar</a>
                <?php endif; ?>
                
                <button onclick="toggleCartDrawer(true)" class="relative bg-charcoal-900 hover:bg-black text-white px-4 py-2.5 rounded-2xl shadow-md transition-all active:scale-95 flex items-center gap-2.5">
                    <i class="fa-solid fa-bag-shopping text-amber-400 text-base"></i>
                    <span class="hidden sm:inline text-xs font-bold uppercase">Keranjang</span>
                    <span id="cart-count-badge" class="bg-spicy-600 text-white font-black text-xs w-5 h-5 rounded-full flex items-center justify-center border border-white">0</span>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <section id="hero" class="relative overflow-hidden pt-8 pb-16 lg:py-20 bg-gradient-to-b from-brand-100/60 via-brand-50 to-brand-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 border border-amber-300 text-brand-700 text-xs font-extrabold">
                            <span class="w-2 h-2 rounded-full bg-spicy-600 animate-ping"></span>
                            <span>Freshly Made & Torched Daily</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-charcoal-900 tracking-tight leading-tight">
                            Sensasi Jajanan Sultan: <br class="hidden sm:block">
                            <span class="bg-gradient-to-r from-spicy-600 via-brand-600 to-amber-500 bg-clip-text text-transparent">
                                Risol Mayo, Tahu Kocek, Es Teler & Dimsum Mentai!
                            </span>
                        </h1>

                        <p class="text-stone-600 text-base sm:text-lg max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                            Rasakan kombinasi sempurna gurihnya <b>Risol Mayo Lumer</b>, pedas nampol <b>Tahu Kocek Rawit</b>, creamy-nya <b>Dimsum Mentai Flame-Torched</b>, dan segarnya <b>Es Teler Sultan Durian</b>. 100% Halal & Bahan Premium!
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                            <a href="#katalog" class="w-full sm:w-auto text-center bg-gradient-to-r from-spicy-600 to-spicy-700 hover:from-spicy-700 hover:to-spicy-600 text-white font-extrabold px-8 py-4 rounded-2xl shadow-xl shadow-spicy-600/30 transition-all hover:scale-105 active:scale-95 flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                                <i class="fa-solid fa-utensils"></i> Pesan Menu Sekarang
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5 relative flex justify-center">
                        <div class="relative w-full max-w-md aspect-square rounded-3xl bg-gradient-to-tr from-brand-500 via-spicy-500 to-amber-400 p-3 shadow-2xl animate-pulse-gentle">
                            <div class="w-full h-full rounded-2xl bg-charcoal-900 overflow-hidden relative group">
                                <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=800&q=80" alt="Kuliner Sultan Combo" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-85">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent flex flex-col justify-end p-6 text-white">
                                    <div class="flex items-center gap-2 mb-1 text-amber-400 text-xs">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                        <span class="text-xs font-black text-amber-300">4.9 / 5.0 (3,800+ Ulasan)</span>
                                    </div>
                                    <h3 class="font-black text-xl text-white">Dimsum Mentai & Risol Mayo Sultan</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="katalog" class="py-16 bg-white border-y border-amber-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                    <div>
                        <span class="text-spicy-600 text-xs font-black uppercase tracking-widest block mb-1">Menu Pilihan Sultan</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-charcoal-900">Katalog Sajian Favorit</h2>
                    </div>
                    <p class="text-stone-500 text-sm max-w-sm font-medium">Klik pada produk untuk memilih opsi level pedas, es/kemanisan, atau opsi frozen siap simpan.</p>
                </div>

                <div class="flex items-center gap-2 overflow-x-auto pb-4 hide-scrollbar mb-8">
                    <button onclick="filterCategory('all')" id="tab-all" class="category-btn active px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all bg-charcoal-900 text-white shadow-md">
                        <i class="fa-solid fa-border-all mr-1.5"></i> Semua Menu
                    </button>
                    <button onclick="filterCategory('risol')" id="tab-risol" class="category-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all bg-stone-100 hover:bg-amber-100 text-stone-700">
                        <i class="fa-solid fa-cookie-bite text-amber-600 mr-1.5"></i> Risol Mayo
                    </button>
                    <button onclick="filterCategory('tahu')" id="tab-tahu" class="category-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all bg-stone-100 hover:bg-amber-100 text-stone-700">
                        <i class="fa-solid fa-pepper-hot text-spicy-600 mr-1.5"></i> Tahu Kocek
                    </button>
                    <button onclick="filterCategory('esteler')" id="tab-esteler" class="category-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all bg-stone-100 hover:bg-amber-100 text-stone-700">
                        <i class="fa-solid fa-ice-cream text-sky-500 mr-1.5"></i> Es Teler Sultan
                    </button>
                    <button onclick="filterCategory('dimsum')" id="tab-dimsum" class="category-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all bg-stone-100 hover:bg-amber-100 text-stone-700">
                        <i class="fa-solid fa-fire text-orange-500 mr-1.5"></i> Dimsum Mentai
                    </button>
                    <button onclick="filterCategory('frozen')" id="tab-frozen" class="category-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all bg-stone-100 hover:bg-amber-100 text-stone-700">
                        <i class="fa-solid fa-snowflake text-indigo-500 mr-1.5"></i> Frozen & Ready
                    </button>
                    <button onclick="filterCategory('combo')" id="tab-combo" class="category-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all bg-stone-100 hover:bg-amber-100 text-stone-700">
                        <i class="fa-solid fa-box-open text-purple-600 mr-1.5"></i> Paket Combo
                    </button>
                </div>

                <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
            </div>
        </section>
    </main>

    <div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm hidden transition-all">
        <div class="bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl transform transition-all flex flex-col max-h-[90vh]">
            <div class="relative h-60 bg-stone-900 flex-shrink-0">
                <img id="modal-img" src="" alt="" class="w-full h-full object-cover">
                <button onclick="closeProductModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/60 text-white hover:bg-black flex items-center justify-center transition-colors shadow-md">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="absolute bottom-3 left-4 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-amber-300 text-xs font-black uppercase tracking-wider" id="modal-category"></div>
            </div>

            <div class="p-6 overflow-y-auto flex-grow space-y-5">
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <h3 id="modal-title" class="text-xl font-black text-charcoal-900"></h3>
                        <div class="flex items-center gap-2 mt-1">
                            <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                            <span id="modal-rating" class="text-xs font-extrabold text-stone-700"></span>
                        </div>
                    </div>
                    <span id="modal-price" class="text-xl font-black text-spicy-600"></span>
                </div>

                <p id="modal-desc" class="text-stone-600 text-xs sm:text-sm leading-relaxed"></p>

                <div class="space-y-4 pt-2 border-t border-stone-100">
                    <div id="spice-level-option" class="space-y-2">
                        <label class="block text-xs font-black uppercase text-stone-800">Tingkat Pedas Cabai Rawit:</label>
                        <select id="option-spice" class="w-full bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs font-bold focus:ring-2 focus:ring-spicy-600 outline-none">
                            <option value="Level 0 (Tanpa Pedas Extra)">Level 0 (Tanpa Pedas Extra)</option>
                            <option value="Level 1 (Pedas Manis Santai)">Level 1 (Pedas Manis Santai)</option>
                            <option value="Level 3 (Pedas Nampol Cabai Rawit)" selected>Level 3 (Pedas Nampol Cabai Rawit)</option>
                            <option value="Level 5 (Pedas Ekstrem Halilintar)">Level 5 (Pedas Ekstrem Halilintar)</option>
                        </select>
                    </div>

                    <div id="sweet-level-option" class="space-y-2 hidden">
                        <label class="block text-xs font-black uppercase text-stone-800">Opsi Es & Kemanisan:</label>
                        <select id="option-sweet" class="w-full bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs font-bold focus:ring-2 focus:ring-amber-500 outline-none">
                            <option value="Normal Es & Gula Pasir/Krimer" selected>Normal Es & Gula Pasir/Krimer</option>
                            <option value="Es Sedikit (Less Ice)">Es Sedikit (Less Ice)</option>
                            <option value="Pisah Es Batu (Bungkus Terpisah)">Pisah Es Batu (Bungkus Terpisah)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-stone-800">Catatan Khusus (Optional):</label>
                        <input type="text" id="option-note" placeholder="Contoh: Saus mentai dipisah..." class="w-full bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs font-medium focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="p-4 bg-stone-50 border-t border-stone-200 flex items-center justify-between gap-4">
                <div class="flex items-center border border-stone-300 rounded-2xl bg-white overflow-hidden">
                    <button onclick="adjustModalQty(-1)" class="w-10 h-10 font-bold hover:bg-stone-100 text-stone-700">-</button>
                    <span id="modal-qty" class="w-10 text-center font-black text-sm text-charcoal-900">1</span>
                    <button onclick="adjustModalQty(1)" class="w-10 h-10 font-bold hover:bg-stone-100 text-stone-700">+</button>
                </div>
                <button onclick="confirmAddToCart()" class="flex-1 bg-spicy-600 hover:bg-spicy-700 text-white font-extrabold py-3.5 px-6 rounded-2xl text-xs uppercase tracking-wider shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambahkan Ke Keranjang
                </button>
            </div>
        </div>
    </div>

    <div id="cart-drawer-overlay" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden transition-opacity" onclick="toggleCartDrawer(false)"></div>
    <div id="cart-drawer" class="fixed top-0 right-0 bottom-0 z-50 w-full max-w-md bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        <div class="p-6 border-b border-stone-200 flex items-center justify-between bg-brand-50">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-bag-shopping text-spicy-600 text-xl"></i>
                <h3 class="font-black text-lg text-charcoal-900">Keranjang Sultan</h3>
            </div>
            <button onclick="toggleCartDrawer(false)" class="w-8 h-8 rounded-full bg-stone-200 text-stone-600 hover:bg-stone-300 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div id="cart-items-list" class="flex-grow p-6 overflow-y-auto space-y-4"></div>

        <div class="p-6 border-t border-stone-200 bg-stone-50 space-y-4">
            <div class="space-y-2">
                <input type="text" id="cust-name" placeholder="Nama Lengkap Pemesan" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-spicy-600">
                <input type="tel" id="cust-phone" placeholder="Nomor WhatsApp (08xxxxxxxx)" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-spicy-600">
                <textarea id="cust-address" placeholder="Alamat Pengiriman / Catatan Patokan Lokasi" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 text-xs outline-none focus:ring-2 focus:ring-spicy-600 h-16 resize-none"></textarea>
            </div>

            <div class="flex items-center justify-between text-stone-700 font-extrabold text-sm pt-2 border-t border-stone-200">
                <span>Total Pembayaran:</span>
                <span id="cart-total-price" class="text-lg font-black text-spicy-600">Rp 0</span>
            </div>

            <button onclick="processCheckout()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 rounded-2xl text-xs uppercase tracking-wider shadow-lg flex items-center justify-center gap-2">
                <i class="fa-brands fa-whatsapp text-base"></i> Kirim Pesanan via WhatsApp
            </button>
        </div>
    </div>

    <script>
        const initialProducts = <?php echo json_encode($products); ?>;
        const customerLoggedIn = <?php echo $currentUser && $currentUser['role'] === 'customer' ? 'true' : 'false'; ?>;
        const customerDisplayName = <?php echo json_encode($currentUser && $currentUser['role'] === 'customer' ? $currentUser['name'] : ''); ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>
