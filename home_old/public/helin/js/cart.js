/**
 * Helin Cart Module
 * Manages shopping cart state via localStorage.
 * Dispatches 'cart:updated' CustomEvent on every mutation.
 */
const Cart = (() => {
    const STORAGE_KEY = 'helin_cart';

    // ─── Private helpers ──────────────────────────────────────────────────────

    function load() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        } catch {
            return [];
        }
    }

    function save(items) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        dispatch(items);
    }

    function dispatch(items) {
        const count = items.reduce((sum, i) => sum + i.qty, 0);
        document.dispatchEvent(new CustomEvent('cart:updated', {
            detail: { items, count }
        }));
    }

    /**
     * Build the unique cart identity for a product + variant (dimension).
     * Products without a dimension use their slug as the identity.
     */
    function makeId(slug, dimension) {
        return dimension ? `${slug}::${dimension}` : slug;
    }

    function findIndex(items, id) {
        return items.findIndex(i => (i.id || i.slug) === id);
    }

    // ─── Public API ───────────────────────────────────────────────────────────

    /**
     * Add or increment a product variant.
     * @param {object} product - { slug, name, brand, price, image, qty, sku, dimension }
     */
    function add(product) {
        const items = load();
        const id    = makeId(product.slug, product.dimension);
        const idx   = findIndex(items, id);

        if (idx > -1) {
            items[idx].qty += (product.qty || 1);
            // Mantener precio/SKU actualizados por si la variante cambió
            items[idx].price = parseFloat(product.price) || items[idx].price;
            if (product.sku) items[idx].sku = product.sku;
            save(items);
            return { action: 'updated', item: items[idx] };
        }

        const item = {
            id,
            slug:      product.slug,
            name:      product.name,
            brand:     product.brand  || '',
            price:     parseFloat(product.price) || 0,
            image:     product.image  || '',
            qty:       parseInt(product.qty, 10) || 1,
            sku:       product.sku || '',
            dimension: product.dimension || '',
        };
        items.push(item);
        save(items);
        return { action: 'added', item };
    }

    /**
     * Set exact quantity for a product variant. qty <= 0 removes it.
     */
    function setQty(id, qty) {
        const items = load();
        const idx   = findIndex(items, id);
        if (idx === -1) return null;

        if (qty <= 0) {
            const removed = items.splice(idx, 1)[0];
            save(items);
            return { action: 'removed', item: removed };
        }

        items[idx].qty = parseInt(qty, 10);
        save(items);
        return { action: 'updated', item: items[idx] };
    }

    /**
     * Remove a product variant entirely.
     */
    function remove(id) {
        const items = load();
        const idx   = findIndex(items, id);
        if (idx === -1) return null;

        const removed = items.splice(idx, 1)[0];
        save(items);
        return { action: 'removed', item: removed };
    }

    /**
     * Empty the cart completely.
     */
    function clear() {
        save([]);
    }

    /**
     * Returns all items.
     */
    function getItems() {
        return load();
    }

    /**
     * Returns total item count (sum of quantities).
     */
    function getCount() {
        return load().reduce((sum, i) => sum + i.qty, 0);
    }

    /**
     * Returns the subtotal.
     */
    function getSubtotal() {
        return load().reduce((sum, i) => sum + (i.price * i.qty), 0);
    }

    // Bootstrap: fire initial event so badge renders on page load
    document.addEventListener('DOMContentLoaded', () => dispatch(load()));

    return { add, setQty, remove, clear, getItems, getCount, getSubtotal };
})();
