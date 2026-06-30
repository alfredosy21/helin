/**
 * Helin Cart UI
 * Wires up cart.js + cart-toast.js to the DOM:
 *  - Updates header badge on every cart:updated event
 *  - Delegates [data-cart-add] button clicks → Cart.add()
 *  - Renders the cart page (#cart-page-root) if present
 */
document.addEventListener('DOMContentLoaded', () => {

    // ─── Header badge ─────────────────────────────────────────────────────────
    const badge = document.getElementById('cart-badge');

    function updateBadge(count) {
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }

    // Initialize badge with current cart count
    updateBadge(Cart.getCount());

    document.addEventListener('cart:updated', e => {
        updateBadge(e.detail.count);
        if (document.getElementById('cart-page-root')) {
            renderCartPage(e.detail.items);
        }
        if (document.getElementById('cart-summary')) {
            renderCartSummary(e.detail.items);
        }
    });

    // ─── Add-to-cart button delegation ───────────────────────────────────────
    document.addEventListener('click', e => {
        const btn = e.target.closest('[data-cart-add]');
        if (!btn) return;

        const { slug, name, brand, price, image } = btn.dataset;
        const qtyInput = btn.closest('[data-cart-context]')?.querySelector('[data-cart-qty]');
        const qty = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;

        const result = Cart.add({ slug, name, brand, price: parseFloat(price), image, qty });

        if (result.action === 'added') {
            Toast.show({ type: 'cart', message: `<strong>${name}</strong> agregado al carrito.` });
        } else {
            Toast.show({ type: 'success', message: `Cantidad actualizada en el carrito.` });
        }
    });

    // ─── Cart page rendering ──────────────────────────────────────────────────
    const cartRoot = document.getElementById('cart-page-root');
    if (cartRoot) {
        renderCartPage(Cart.getItems());
    }

    function fmt(n) {
        return '$' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function renderCartPage(items) {
        const root = document.getElementById('cart-page-root');
        if (!root) return;

        if (items.length === 0) {
            root.innerHTML = renderEmpty();
            return;
        }

        root.innerHTML = `
            <div class="flex flex-col lg:flex-row gap-8">
                ${renderItemsTable(items)}
                ${renderSummary(items)}
            </div>`;

        bindCartPageEvents();
    }

    function renderEmpty() {
        return `
            <div class="text-center py-20">
                <i class="fas fa-shopping-cart text-5xl text-helin-border mb-6 block"></i>
                <h2 class="text-2xl text-helin-heading mb-3">Tu carrito está vacío</h2>
                <p class="text-helin-text mb-6">Agrega productos desde el catálogo para comenzar tu solicitud.</p>
                <a href="/catalogo" class="inline-block bg-turquesa hover:bg-turquesa-dark text-white font-bold px-8 py-3 rounded-full transition-colors">
                    Ver catálogo
                </a>
            </div>`;
    }

    function renderItemsTable(items) {
        const rows = items.map(item => `
            <div class="cart-row p-4 md:p-0 border-b border-helin-border" data-slug="${item.slug}">
                <!-- Mobile -->
                <div class="md:hidden flex gap-4">
                    <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-contain rounded-lg flex-shrink-0 bg-helin-soft">
                    <div class="flex-1">
                        <h3 class="font-semibold text-turquesa text-sm mb-1">${item.name}</h3>
                        <p class="text-helin-text text-xs mb-2">${item.brand}</p>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-turquesa text-sm font-bold">${fmt(item.price)}</span>
                            ${renderQtyControl(item, 'sm')}
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-turquesa">${fmt(item.price * item.qty)}</span>
                            <button class="cart-remove text-helin-text hover:text-red-500 transition" data-slug="${item.slug}" aria-label="Eliminar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Desktop -->
                <div class="hidden md:grid md:grid-cols-12 items-center">
                    <div class="col-span-5 px-6 py-5 flex items-center gap-4">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-contain rounded-lg bg-helin-soft">
                        <div>
                            <h3 class="font-semibold text-turquesa text-sm">${item.name}</h3>
                            <p class="text-helin-text text-xs">${item.brand}</p>
                        </div>
                    </div>
                    <div class="col-span-2 px-4 py-5 text-center">
                        <span class="text-turquesa font-medium">${fmt(item.price)}</span>
                    </div>
                    <div class="col-span-3 px-4 py-5 text-center flex justify-center">
                        ${renderQtyControl(item, 'md')}
                    </div>
                    <div class="col-span-2 px-6 py-5 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <span class="font-bold text-turquesa cart-row-subtotal">${fmt(item.price * item.qty)}</span>
                            <button class="cart-remove text-helin-text hover:text-red-500 transition" data-slug="${item.slug}" aria-label="Eliminar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>`).join('');

        return `
            <div class="lg:w-[65%] xl:w-[70%]">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="hidden md:grid md:grid-cols-12 bg-turquesa text-white font-semibold text-sm">
                        <div class="col-span-5 px-6 py-4">Producto</div>
                        <div class="col-span-2 px-4 py-4 text-center">Precio</div>
                        <div class="col-span-3 px-4 py-4 text-center">Cantidad</div>
                        <div class="col-span-2 px-6 py-4 text-right">Subtotal</div>
                    </div>
                    ${rows}
                </div>
            </div>`;
    }

    function renderQtyControl(item, size) {
        const w  = size === 'sm' ? 'w-8 h-8 text-sm'  : 'w-10 h-10';
        const iw = size === 'sm' ? 'w-14 text-sm' : 'w-20';
        return `
            <div class="flex items-center rounded-full bg-turquesa/10 overflow-hidden" style="padding:5px 10px;">
                <button class="cart-qty-dec ${w} bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" data-slug="${item.slug}">-</button>
                <input type="number" value="${item.qty}" min="1"
                       class="cart-qty-input ${iw} text-center font-medium bg-transparent outline-none"
                       data-slug="${item.slug}" style="padding:5px 6px;">
                <button class="cart-qty-inc ${w} bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" data-slug="${item.slug}">+</button>
            </div>`;
    }

    function renderSummary(items) {
        const subtotal = items.reduce((s, i) => s + i.price * i.qty, 0);
        const bsRate   = 36.50;
        const totalBs  = (subtotal * bsRate).toLocaleString('es-VE', { minimumFractionDigits: 2 });

        return `
            <div class="lg:w-[35%] xl:w-[30%]">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h2 class="text-xl text-helin-heading mb-6">Resumen</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-helin-text">
                            <span>Subtotal</span>
                            <span id="cart-summary-subtotal">${fmt(subtotal)}</span>
                        </div>
                    </div>
                    <div class="border-t border-helin-border pt-4 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-semibold text-helin-heading">Total</span>
                            <span class="text-2xl font-bold text-turquesa" id="cart-summary-total">${fmt(subtotal)}</span>
                        </div>
                    </div>
                    <div class="bg-helin-soft rounded-lg p-4 mb-6">
                        <p class="text-xs text-helin-text mb-1">Tasa de conversión a Bs.</p>
                        <p class="text-sm text-helin-text mb-2">1 USD = ${bsRate} Bs.</p>
                        <div class="flex justify-between items-center border-t border-helin-border pt-2">
                            <span class="font-semibold text-helin-heading">Total en Bs.</span>
                            <span class="text-lg font-bold text-turquesa" id="cart-summary-bs">${totalBs} Bs.</span>
                        </div>
                    </div>
                    <a href="/solicitud" class="block w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold py-4 rounded-full uppercase transition-colors text-center">
                        Continuar Solicitud
                    </a>
                    <button id="cart-clear-btn" class="mt-3 w-full text-xs text-helin-text hover:text-red-500 transition text-center py-2">
                        Vaciar carrito
                    </button>
                </div>
            </div>`;
    }

    function bindCartPageEvents() {
        // Remove buttons
        document.querySelectorAll('.cart-remove').forEach(btn => {
            btn.addEventListener('click', () => {
                const result = Cart.remove(btn.dataset.slug);
                if (result) {
                    Toast.show({ type: 'error', message: `<strong>${result.item.name}</strong> eliminado del carrito.` });
                }
            });
        });

        // Qty increment / decrement buttons
        document.querySelectorAll('.cart-qty-dec').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.querySelector(`.cart-qty-input[data-slug="${btn.dataset.slug}"]`);
                const newQty = parseInt(input?.value || 1, 10) - 1;
                if (newQty <= 0) {
                    const result = Cart.remove(btn.dataset.slug);
                    if (result) Toast.show({ type: 'error', message: `<strong>${result.item.name}</strong> eliminado del carrito.` });
                } else {
                    Cart.setQty(btn.dataset.slug, newQty);
                }
            });
        });

        document.querySelectorAll('.cart-qty-inc').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.querySelector(`.cart-qty-input[data-slug="${btn.dataset.slug}"]`);
                const newQty = parseInt(input?.value || 1, 10) + 1;
                Cart.setQty(btn.dataset.slug, newQty);
            });
        });

        // Qty direct input change (debounced)
        let qtyTimer;
        document.querySelectorAll('.cart-qty-input').forEach(input => {
            input.addEventListener('change', () => {
                clearTimeout(qtyTimer);
                qtyTimer = setTimeout(() => {
                    const qty = parseInt(input.value, 10);
                    if (qty <= 0 || isNaN(qty)) {
                        const result = Cart.remove(input.dataset.slug);
                        if (result) Toast.show({ type: 'error', message: `<strong>${result.item.name}</strong> eliminado del carrito.` });
                    } else {
                        Cart.setQty(input.dataset.slug, qty);
                        Toast.show({ type: 'info', message: 'Cantidad actualizada.' });
                    }
                }, 400);
            });
        });

        // Clear cart button
        const clearBtn = document.getElementById('cart-clear-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                Cart.clear();
                Toast.show({ type: 'info', message: 'El carrito ha sido vaciado.' });
            });
        }
    }

    /**
     * Render cart summary for solicitud page
     */
    function renderCartSummary(items) {
        const summaryRoot = document.getElementById('cart-summary');
        if (!summaryRoot) return;

        if (items.length === 0) {
            summaryRoot.innerHTML = `
                <div class="text-center py-8 text-helin-text">
                    <i class="fas fa-shopping-cart text-turquesa text-2xl mb-2"></i>
                    <p class="text-sm">Tu carrito está vacío</p>
                    <a href="${window.location.origin}/catalogo" class="text-turquesa text-sm hover:underline mt-2 inline-block">Ver productos →</a>
                </div>
            `;
            return;
        }

        const subtotal = items.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const discount = subtotal > 500 ? subtotal * 0.05 : 0; // 5% descuento si subtotal > 500
        const total = subtotal - discount;

        const itemsHtml = items.map(item => `
            <div class="flex justify-between text-sm">
                <span class="text-helin-text">${item.name} x ${item.qty}</span>
                <span class="font-medium">$${(item.price * item.qty).toFixed(2)}</span>
            </div>
        `).join('');

        summaryRoot.innerHTML = `
            ${itemsHtml}
            <div class="border-t border-helin-border pt-3 mt-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-helin-text">Subtotal</span>
                    <span>$${subtotal.toFixed(2)}</span>
                </div>
                ${discount > 0 ? `
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-helin-text">Descuento (5%)</span>
                        <span class="text-green-500">-$${discount.toFixed(2)}</span>
                    </div>
                ` : ''}
                <div class="flex justify-between text-base font-bold">
                    <span class="text-helin-heading">Total</span>
                    <span class="text-turquesa">$${total.toFixed(2)}</span>
                </div>
            </div>
        `;
    }
});
