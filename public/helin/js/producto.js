/**
 * Producto - Actualización de precio/SKU por dimensión
 *
 * Requiere que la vista inyecte window.HelinProduct = {
 *   sku: '',           // SKU base del producto
 *   prices: {},        // Mapa label -> { base, sale, sku }
 * }
 */
function updatePriceBySize(size) {
    var data = window.HelinProduct || {};
    var sizePrices = data.prices || {};

    var priceInfo = sizePrices[size] || Object.values(sizePrices)[0];
    if (!priceInfo) return;

    var displayPrice = priceInfo.sale != null ? priceInfo.sale : priceInfo.base;
    var currentPriceEl = document.getElementById('currentPrice');
    var oldPriceEl = document.getElementById('oldPrice');
    var skuEl = document.getElementById('productSkuValue');
    var cartButton = document.querySelector('[data-cart-add]');

    // Actualizar precios con animación
    currentPriceEl.style.opacity = '0.5';
    if (oldPriceEl) oldPriceEl.style.opacity = '0.5';

    setTimeout(function () {
        // Actualizar precio actual
        currentPriceEl.textContent = '$' + displayPrice.toFixed(2);

        // Actualizar precio anterior si hay oferta
        if (priceInfo.sale && priceInfo.sale < priceInfo.base) {
            if (!oldPriceEl) {
                // Crear elemento de precio anterior si no existe
                var priceDisplay = document.getElementById('priceDisplay');
                var newOldPrice = document.createElement('span');
                newOldPrice.id = 'oldPrice';
                newOldPrice.className = 'text-lg text-helin-text line-through opacity-70';
                priceDisplay.insertBefore(newOldPrice, currentPriceEl);
                oldPriceEl = newOldPrice;
            }
            oldPriceEl.textContent = '$' + priceInfo.base.toFixed(2);
            oldPriceEl.style.display = 'inline';
        } else if (oldPriceEl) {
            // Ocultar precio anterior si no hay oferta
            oldPriceEl.style.display = 'none';
        }

        // Actualizar SKU mostrado según la dimensión seleccionada
        if (skuEl && priceInfo.sku) {
            skuEl.textContent = priceInfo.sku;
        }

        // Actualizar datos del botón de carrito (precio, SKU y dimensión)
        if (cartButton) {
            cartButton.setAttribute('data-price', displayPrice.toFixed(2));
            if (priceInfo.sku) cartButton.setAttribute('data-sku', priceInfo.sku);
            cartButton.setAttribute('data-dimension', size);
        }

        // Restaurar opacidad con animación
        currentPriceEl.style.opacity = '1';
        if (oldPriceEl) oldPriceEl.style.opacity = '0.7';
    }, 200);
}
