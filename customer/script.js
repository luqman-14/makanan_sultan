let allProducts = typeof initialProducts !== 'undefined' ? initialProducts : [];
let cart = JSON.parse(localStorage.getItem('kuliner_sultan_cart')) || [];
let selectedProductForModal = null;
let currentModalQty = 1;

document.addEventListener('DOMContentLoaded', () => {
    renderProducts(allProducts);
    updateCartUI();
    const nameInput = document.getElementById('cust-name');
    if (customerLoggedIn && nameInput && customerDisplayName) nameInput.value = customerDisplayName;
});

function renderProducts(products) {
    const grid = document.getElementById('product-grid');
    if (!grid) return;
    if (products.length === 0) {
        grid.innerHTML = `<div class="col-span-full text-center py-12 text-stone-400 font-bold">Menu tidak ditemukan.</div>`;
        return;
    }
    grid.innerHTML = products.map(prod => `
        <div class="bg-white rounded-3xl border border-amber-200/60 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
            <div>
                <div class="relative h-48 overflow-hidden bg-stone-900">
                    <img src="${prod.image_url}" alt="${prod.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-amber-500 text-xs font-black flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-star"></i> ${prod.rating}
                    </div>
                </div>
                <div class="p-5 space-y-2">
                    <h3 class="font-black text-base text-charcoal-900 group-hover:text-spicy-600 transition-colors">${prod.name}</h3>
                    <p class="text-stone-500 text-xs line-clamp-2 leading-relaxed">${prod.description}</p>
                </div>
            </div>
            <div class="px-5 pb-5 pt-2 flex items-center justify-between border-t border-stone-100 mt-2">
                <div>
                    <span class="text-[10px] font-bold text-stone-400 block uppercase">Harga</span>
                    <span class="text-base font-black text-spicy-600">Rp ${Number(prod.price).toLocaleString('id-ID')}</span>
                </div>
                <button onclick="openProductModal(${prod.id})" class="bg-charcoal-900 hover:bg-black text-white px-4 py-2.5 rounded-2xl text-xs font-extrabold uppercase tracking-wider transition-all active:scale-95 flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-cart-plus text-amber-400"></i> Pilih
                </button>
            </div>
        </div>
    `).join('');
}

function filterCategory(category) {
    document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active', 'bg-charcoal-900', 'text-white'));
    const targetTab = document.getElementById(`tab-${category}`);
    if (targetTab) targetTab.classList.add('active', 'bg-charcoal-900', 'text-white');
    if (category === 'all') { renderProducts(allProducts); } else { renderProducts(allProducts.filter(p => p.category === category)); }
}

function openProductModal(productId) {
    selectedProductForModal = allProducts.find(p => p.id === productId);
    if (!selectedProductForModal) return;
    currentModalQty = 1;
    document.getElementById('modal-qty').innerText = currentModalQty;
    document.getElementById('modal-img').src = selectedProductForModal.image_url;
    document.getElementById('modal-title').innerText = selectedProductForModal.name;
    document.getElementById('modal-rating').innerText = `${selectedProductForModal.rating} / 5.0 Rating Pelanggan`;
    document.getElementById('modal-price').innerText = `Rp ${Number(selectedProductForModal.price).toLocaleString('id-ID')}`;
    document.getElementById('modal-desc').innerText = selectedProductForModal.description;
    document.getElementById('modal-category').innerText = selectedProductForModal.category.toUpperCase();

    const spiceOpt = document.getElementById('spice-level-option');
    const sweetOpt = document.getElementById('sweet-level-option');
    if (selectedProductForModal.category === 'esteler') {
        spiceOpt.classList.add('hidden'); sweetOpt.classList.remove('hidden');
    } else {
        spiceOpt.classList.remove('hidden'); sweetOpt.classList.add('hidden');
    }
    document.getElementById('product-modal').classList.remove('hidden');
}

function closeProductModal() { document.getElementById('product-modal').classList.add('hidden'); }
function adjustModalQty(delta) { currentModalQty = Math.max(1, currentModalQty + delta); document.getElementById('modal-qty').innerText = currentModalQty; }

function confirmAddToCart() {
    if (!selectedProductForModal) return;
    let optionDetail = selectedProductForModal.category === 'esteler' ? document.getElementById('option-sweet').value : document.getElementById('option-spice').value;
    const note = document.getElementById('option-note').value.trim();
    cart.push({ cartId: Date.now(), id: selectedProductForModal.id, name: selectedProductForModal.name, price: selectedProductForModal.price, image_url: selectedProductForModal.image_url, qty: currentModalQty, option: optionDetail, note: note });
    saveCart(); closeProductModal(); toggleCartDrawer(true);
}

function toggleCartDrawer(show) {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-drawer-overlay');
    if (show) { overlay.classList.remove('hidden'); drawer.classList.remove('translate-x-full'); } else { overlay.classList.add('hidden'); drawer.classList.add('translate-x-full'); }
}

function saveCart() { localStorage.setItem('kuliner_sultan_cart', JSON.stringify(cart)); updateCartUI(); }

function updateCartUI() {
    const badge = document.getElementById('cart-count-badge');
    if (badge) badge.innerText = cart.reduce((sum, item) => sum + item.qty, 0);
    const list = document.getElementById('cart-items-list');
    const totalPriceEl = document.getElementById('cart-total-price');
    if (!list) return;
    if (cart.length === 0) { list.innerHTML = `<div class="text-center py-12 text-stone-400 font-bold text-xs">Keranjang Anda masih kosong.</div>`; if (totalPriceEl) totalPriceEl.innerText = 'Rp 0'; return; }
    let grandTotal = 0;
    list.innerHTML = cart.map((item, index) => {
        const itemTotal = item.price * item.qty; grandTotal += itemTotal;
        return `
            <div class="flex items-start gap-3 p-3 bg-stone-50 rounded-2xl border border-stone-200">
                <img src="${item.image_url}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                <div class="flex-grow min-w-0">
                    <h4 class="font-extrabold text-xs text-charcoal-900 truncate">${item.name}</h4>
                    <p class="text-[10px] text-spicy-600 font-bold">${item.option}</p>
                    ${item.note ? `<p class="text-[10px] text-stone-500 italic">Catatan: "${item.note}"</p>` : ''}
                    <div class="flex items-center justify-between mt-2">
                        <span class="font-black text-xs text-stone-800">Rp ${Number(itemTotal).toLocaleString('id-ID')}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-stone-600">Qty: ${item.qty}</span>
                            <button onclick="removeCartItem(${index})" class="text-red-500 hover:text-red-700 text-xs px-1.5"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>`;
    }).join('');
    if (totalPriceEl) totalPriceEl.innerText = `Rp ${Number(grandTotal).toLocaleString('id-ID')}`;
}

function removeCartItem(index) { cart.splice(index, 1); saveCart(); }

function processCheckout() {
    if (!customerLoggedIn) {
        alert('Silakan masuk ke akun customer sebelum mengirim pesanan.');
        window.location.href = 'login.php?next=checkout';
        return;
    }
    if (cart.length === 0) { alert('Keranjang belanja Anda kosong!'); return; }
    const name = document.getElementById('cust-name').value.trim();
    const phone = document.getElementById('cust-phone').value.trim();
    const address = document.getElementById('cust-address').value.trim();
    if (!name || !phone || !address) { alert('Mohon lengkapi Nama, Nomor WhatsApp, dan Alamat Pengiriman.'); return; }
    let textMessage = `*PESANAN BARU - KULINER SULTAN*\n==============================\n*Nama:* ${name}\n*WhatsApp:* ${phone}\n*Alamat:* ${address}\n==============================\n\n*RINCIAN MENU:*\n`;
    let total = 0;
    cart.forEach((item, i) => {
        const subtotal = item.price * item.qty; total += subtotal;
        textMessage += `${i + 1}. *${item.name}* (x${item.qty})\n   - Opsi: ${item.option}\n${item.note ? `   - Catatan: ${item.note}\n` : ''}   - Subtotal: Rp ${Number(subtotal).toLocaleString('id-ID')}\n\n`;
    });
    textMessage += `==============================\n*TOTAL PEMBAYARAN:* Rp ${Number(total).toLocaleString('id-ID')}\n\nMohon konfirmasi pesanan dan informasi rekening pembayaran. Terima kasih!`;
    const waUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(textMessage)}`;
    cart = []; saveCart(); toggleCartDrawer(false); window.open(waUrl, '_blank');
}
