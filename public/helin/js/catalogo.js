document.addEventListener('DOMContentLoaded', function () {
    const productsContent   = document.getElementById('productsContent');
    const loadingIndicator  = document.getElementById('catalogLoading');
    const clearFiltersBtn   = document.getElementById('clearFilters');
    const filterCheckboxes  = document.querySelectorAll('.filter-checkbox');
    const searchInput       = document.getElementById('catalogSearch');
    function getActiveFilters() { return document.getElementById('activeFilters'); }
    function getActiveFiltersChips() { return document.getElementById('activeFiltersChips'); }

    const filterLabelMap = {
        'tag': { 'featured': 'Destacados', 'on_sale': 'Ofertas', 'new': 'Nuevos' }
    };

    function getFilterLabel(type, value) {
        if (filterLabelMap[type] && filterLabelMap[type][value]) {
            return filterLabelMap[type][value];
        }
        const checkbox = document.querySelector(`.filter-checkbox[data-filter-type="${type}"][value="${value}"]`);
        if (checkbox) {
            const label = checkbox.closest('label')?.querySelector('span.text-helin-text, span:last-child')?.textContent;
            return label ? label.trim() : value;
        }
        return value;
    }

    function renderActiveFilters() {
        const activeFilters = getActiveFilters();
        const activeFiltersChips = getActiveFiltersChips();
        if (!activeFilters || !activeFiltersChips) return;
        activeFiltersChips.innerHTML = '';
        const chips = [];

        filterCheckboxes.forEach(cb => {
            if (!cb.checked) return;
            const type = cb.dataset.filterType;
            const value = cb.value;
            const label = getFilterLabel(type, value);
            const key = `${type}:${value}`;
            if (chips.some(c => c.key === key)) return;
            chips.push({ key, type, value, label });
        });

        if (searchInput && searchInput.value.trim()) {
            chips.push({ key: 'search', type: 'search', value: searchInput.value.trim(), label: 'Búsqueda: ' + searchInput.value.trim() });
        }

        if (chips.length === 0) {
            activeFilters.classList.add('hidden');
            return;
        }

        activeFilters.classList.remove('hidden');
        activeFilters.style.display = 'flex';
        chips.forEach(chip => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'inline-flex items-center gap-1.5 bg-white border border-turquesa text-turquesa text-xs px-3 py-1.5 rounded-full hover:bg-turquesa hover:text-white transition-colors';
            btn.innerHTML = `<span>${chip.label}</span><i class="fas fa-times"></i>`;
            btn.addEventListener('click', () => removeFilter(chip.type, chip.value));
            activeFiltersChips.appendChild(btn);
        });

        if (chips.length > 1) {
            const clearBtn = document.createElement('button');
            clearBtn.type = 'button';
            clearBtn.className = 'text-xs text-turquesa hover:underline ml-1';
            clearBtn.textContent = 'Limpiar todos';
            clearBtn.addEventListener('click', clearAll);
            activeFiltersChips.appendChild(clearBtn);
        }
    }

    function removeFilter(type, value) {
        if (type === 'search') {
            if (searchInput) searchInput.value = '';
        } else {
            const checkbox = document.querySelector(`.filter-checkbox[data-filter-type="${type}"][value="${value}"]`);
            if (checkbox) checkbox.checked = false;
        }
        updateClearButton();
        applyFilters();
    }

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
                // Guardar valor del sort antes de reemplazar HTML
                const prevSortVal = (document.getElementById('sortSelect') || {}).value || 'recent';

                productsContent.innerHTML = data.html;

                // Re-bind sortSelect después de reemplazar el HTML y restaurar valor
                const newSort = document.getElementById('sortSelect');
                if (newSort) {
                    newSort.value = prevSortVal;
                    newSort.addEventListener('change', applyFilters);
                }

                // Re-bind botón limpiar del estado vacío
                const clearEmpty = document.getElementById('clearFiltersEmpty');
                if (clearEmpty) clearEmpty.addEventListener('click', clearAll);

                // Actualizar URL sin recargar
                const qs = params.toString();
                window.history.pushState({}, '', window.location.pathname + (qs ? '?' + qs : ''));

                updateClearButton();
                renderActiveFilters();
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
        renderActiveFilters();
        applyFilters();
    }

    // --- Event Listeners ---

    // Autocompletado de búsqueda
    const searchWrapper = document.getElementById('catalogSearchWrapper');
    const autocompleteBox = document.getElementById('searchAutocomplete');
    let autocompleteTimer = null;
    let abortController = null;

    function normalizeText(text) {
        return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function highlightMatch(text, term) {
        if (!term) return escapeHtml(text);
        const lowerTerm = term.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
        const lowerText = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
        const nfdText = text.normalize('NFD');
        const matchIdx = lowerText.indexOf(lowerTerm);
        if (matchIdx === -1) return escapeHtml(text);
        const nfdBefore = nfdText.slice(0, matchIdx);
        const nfdMatch = nfdText.slice(matchIdx, matchIdx + lowerTerm.length);
        const nfdAfter = nfdText.slice(matchIdx + lowerTerm.length);
        const before = nfdBefore.normalize('NFC');
        const match = nfdMatch.normalize('NFC');
        const after = nfdAfter.normalize('NFC');
        return escapeHtml(before) +
               '<mark class="bg-turquesa/20 text-helin-heading font-semibold">' + escapeHtml(match) + '</mark>' +
               escapeHtml(after);
    }

    function hideAutocomplete() {
        if (autocompleteBox) {
            autocompleteBox.classList.add('hidden');
            autocompleteBox.innerHTML = '';
        }
    }

    function showAutocomplete(html) {
        if (autocompleteBox) {
            autocompleteBox.innerHTML = html;
            autocompleteBox.classList.remove('hidden');
        }
    }

    async function fetchAutocomplete(term) {
        if (abortController) abortController.abort();
        abortController = new AbortController();

        try {
            console.log('[Autocompletado] Buscando:', term);
            const response = await fetch('/api/products/autocomplete?q=' + encodeURIComponent(term), {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                signal: abortController.signal
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return await response.json();
        } catch (error) {
            if (error.name !== 'AbortError') console.error('[Autocompletado] Error:', error);
            return null;
        }
    }

    function renderAutocomplete(products, term) {
        if (!autocompleteBox) return;

        if (products.length === 0) {
            showAutocomplete('<div class="px-4 py-3 text-sm text-helin-text">No se encontraron coincidencias.</div>');
            return;
        }

        let html = '';
        products.forEach(product => {
            html += '<a href="' + product.url + '" class="autocomplete-item flex items-center gap-3 px-4 py-3 hover:bg-turquesa/10 cursor-pointer transition-colors border-b border-helin-border last:border-0">' +
                '<div class="w-12 h-12 flex-shrink-0 bg-white rounded-lg overflow-hidden border border-helin-border">' +
                    '<img src="' + product.image + '" alt="' + escapeHtml(product.name) + '" class="w-full h-full object-contain">' +
                '</div>' +
                '<div class="flex-1 min-w-0">' +
                    '<div class="text-sm font-semibold text-helin-heading truncate">' + highlightMatch(product.name, term) + '</div>' +
                    '<div class="text-xs text-helin-text truncate">' + escapeHtml(product.category || 'Helin') + '</div>' +
                '</div>' +
                '<div class="text-sm font-bold text-turquesa flex-shrink-0">' + product.formatted_price + '</div>' +
            '</a>';
        });

        html += '<button type="button" id="autocompleteViewAll" class="w-full text-center text-xs text-turquesa font-black uppercase py-2.5 hover:bg-turquesa/10 cursor-pointer transition-colors">Ver todos los resultados</button>';
        showAutocomplete(html);

        document.getElementById('autocompleteViewAll')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            hideAutocomplete();
            if (searchInput) {
                searchInput.focus();
                applyFilters();
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            clearTimeout(autocompleteTimer);
            updateClearButton();

            const term = searchInput.value.trim();
            if (term.length < 3) {
                hideAutocomplete();
                debounceTimer = setTimeout(applyFilters, 450);
                return;
            }

            debounceTimer = setTimeout(applyFilters, 600);
            autocompleteTimer = setTimeout(async () => {
                const data = await fetchAutocomplete(term);
                console.log('[Autocompletado] Respuesta:', data);
                if (data && data.success) {
                    renderAutocomplete(data.products, term);
                }
            }, 250);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                hideAutocomplete();
                applyFilters();
            }
        });

        searchInput.addEventListener('focus', function() {
            const term = searchInput.value.trim();
            if (term.length >= 3) {
                clearTimeout(autocompleteTimer);
                autocompleteTimer = setTimeout(async () => {
                    const data = await fetchAutocomplete(term);
                    if (data && data.success) renderAutocomplete(data.products, term);
                }, 150);
            }
        });
    }

    // Cerrar autocompletado al hacer clic fuera
    if (searchWrapper) {
        document.addEventListener('click', function(e) {
            if (!searchWrapper.contains(e.target)) hideAutocomplete();
        });
    }

    filterCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            applyFilters();
            updateClearButton();
            renderActiveFilters();
        });
    });

    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) sortSelect.addEventListener('change', applyFilters);

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearAll);
    }

    // Aplicar filtros iniciales si vienen de URL (ej: ?category=implantes)
    const urlParams = new URLSearchParams(window.location.search);
    let hasInitialFilters = false;

    if (urlParams.has('category')) {
        const slug = urlParams.get('category');
        const match = document.querySelector(`.filter-checkbox[data-filter-type="category"][value="${slug}"]`);
        if (match) {
            match.checked = true;
            hasInitialFilters = true;
        }
    }
    // Manejar tanto ?featured=1 como ?tag[]=featured
    if (urlParams.has('featured') || urlParams.has('tag[]')) {
        const match = document.querySelector(`.filter-checkbox[data-filter-type="tag"][value="featured"]`);
        if (match) {
            match.checked = true;
            hasInitialFilters = true;
        }
    }
    if (urlParams.has('search') && searchInput) {
        searchInput.value = urlParams.get('search');
        hasInitialFilters = true;
    }

    // Si hay filtros iniciales, aplicar AJAX para asegurar filtrado correcto
    if (hasInitialFilters) {
        setTimeout(() => {
            applyFilters();
        }, 100);
    }

    updateClearButton();
    renderActiveFilters();
});
