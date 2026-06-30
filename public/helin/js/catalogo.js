document.addEventListener('DOMContentLoaded', function () {
    const productsContent   = document.getElementById('productsContent');
    const loadingIndicator  = document.getElementById('catalogLoading');
    const clearFiltersBtn   = document.getElementById('clearFilters');
    const filterCheckboxes  = document.querySelectorAll('.filter-checkbox');
    const searchInput       = document.getElementById('catalogSearch');

    let debounceTimer = null;

    function showLoading() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        if (productsContent)  productsContent.style.opacity = '0.4';
    }

    function hideLoading() {
        if (loadingIndicator) loadingIndicator.classList.add('hidden');
        if (productsContent)  productsContent.style.opacity = '1';
    }

    function getFilters() {
        const params = new URLSearchParams();

        if (searchInput && searchInput.value.trim()) {
            params.append('search', searchInput.value.trim());
        }

        filterCheckboxes.forEach(cb => {
            if (cb.checked) {
                params.append(cb.dataset.filterType + '[]', cb.value);
            }
        });

        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect && sortSelect.value) {
            params.append('sort', sortSelect.value);
        }

        return params;
    }

    function hasActiveFilters() {
        if (searchInput && searchInput.value.trim()) return true;
        return Array.from(filterCheckboxes).some(cb => cb.checked);
    }

    function updateClearButton() {
        if (clearFiltersBtn) {
            clearFiltersBtn.style.display = hasActiveFilters() ? 'inline-flex' : 'none';
        }
    }

    async function applyFilters() {
        const params = getFilters();

        try {
            showLoading();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const response = await fetch('/api/products/filter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: params.toString()
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            if (data.success && productsContent) {
                productsContent.innerHTML = data.html;

                // Re-bind sortSelect después de reemplazar el HTML
                const newSort = document.getElementById('sortSelect');
                if (newSort) newSort.addEventListener('change', applyFilters);

                // Re-bind botón limpiar del estado vacío
                const clearEmpty = document.getElementById('clearFiltersEmpty');
                if (clearEmpty) clearEmpty.addEventListener('click', clearAll);

                // Actualizar URL sin recargar
                const qs = params.toString();
                window.history.pushState({}, '', window.location.pathname + (qs ? '?' + qs : ''));

                updateClearButton();
            }

        } catch (error) {
            console.error('[Catálogo] Error en filtro:', error);
        } finally {
            hideLoading();
        }
    }

    function clearAll() {
        filterCheckboxes.forEach(cb => { cb.checked = false; });
        if (searchInput) searchInput.value = '';
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) sortSelect.value = 'recent';
        updateClearButton();
        applyFilters();
    }

    // --- Event Listeners ---

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applyFilters, 450);
            updateClearButton();
        });
    }

    filterCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            applyFilters();
            updateClearButton();
        });
    });

    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) sortSelect.addEventListener('change', applyFilters);

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearAll);
    }

    // Aplicar filtros iniciales si vienen de URL (ej: ?category=implantes)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('category')) {
        const slug = urlParams.get('category');
        const match = document.querySelector(`.filter-checkbox[data-filter-type="category"][value="${slug}"]`);
        if (match) {
            match.checked = true;
            updateClearButton();
            // No aplicar filtros AJAX al cargar, ya que el contenido ya viene del servidor
        }
    }
    if (urlParams.has('search') && searchInput) {
        searchInput.value = urlParams.get('search');
        updateClearButton();
        // No aplicar filtros AJAX al cargar, ya que el contenido ya viene del servidor
    }
});
