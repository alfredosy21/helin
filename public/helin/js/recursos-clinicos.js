document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('resourceSearchForm');
    const searchInput = document.getElementById('searchInput');
    const specialtySelect = document.getElementById('specialtySelect');
    const typeSelect = document.getElementById('typeSelect');
    const searchButton = document.getElementById('searchButton');
    const resourcesContent = document.getElementById('resourcesContent');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');

    let debounceTimer = null;

    console.log('[Recursos] Init:', {
        searchForm: !!searchForm,
        searchInput: !!searchInput,
        specialtySelect: !!specialtySelect,
        typeSelect: !!typeSelect,
        resourcesContent: !!resourcesContent,
        checkboxes: filterCheckboxes.length,
        csrfToken: !!document.querySelector('meta[name="csrf-token"]')
    });

    function showLoading() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        if (resourcesContent) resourcesContent.style.opacity = '0.5';
    }

    function hideLoading() {
        if (loadingIndicator) loadingIndicator.classList.add('hidden');
        if (resourcesContent) resourcesContent.style.opacity = '1';
    }

    function getFilters() {
        const params = new URLSearchParams();

        if (searchInput && searchInput.value.trim()) params.append('search', searchInput.value.trim());
        if (specialtySelect && specialtySelect.value) params.append('specialty', specialtySelect.value);
        if (typeSelect && typeSelect.value) params.append('type', typeSelect.value);

        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect && sortSelect.value) params.append('sort', sortSelect.value);

        filterCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                params.append(checkbox.dataset.filterType + '[]', checkbox.value);
            }
        });

        console.log('[Recursos] Filtros activos:', Object.fromEntries(params.entries()));
        return params.toString();
    }

    async function applyFilters() {
        const queryString = getFilters();
        console.log('[Recursos] Llamando AJAX con:', queryString);

        try {
            showLoading();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            console.log('[Recursos] CSRF token presente:', !!csrfToken);

            const response = await fetch('/api/resources/filter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: queryString
            });

            console.log('[Recursos] HTTP status:', response.status, response.ok);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('[Recursos] Error respuesta:', errorText);
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            console.log('[Recursos] Respuesta JSON:', { success: data.success, count: data.count });

            if (data.success && resourcesContent) {
                resourcesContent.innerHTML = data.html;
                console.log(`[Recursos] DOM actualizado con ${data.count} recursos`);

                const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
                window.history.pushState({}, '', newUrl);

                const newSortSelect = document.getElementById('sortSelect');
                if (newSortSelect) {
                    newSortSelect.addEventListener('change', applyFilters);
                    console.log('[Recursos] sortSelect re-vinculado');
                }
            } else {
                throw new Error(data.message || 'Respuesta sin success');
            }

        } catch (error) {
            console.error('[Recursos] ERROR en applyFilters:', error);
            if (resourcesContent) {
                resourcesContent.innerHTML = '<div class="error-message">Ocurrió un error al cargar los recursos. Revisa la consola.</div>';
            }
        } finally {
            hideLoading();
        }
    }

    function hasActiveFilters() {
        if (searchInput && searchInput.value.trim()) return true;
        if (specialtySelect && specialtySelect.value) return true;
        if (typeSelect && typeSelect.value) return true;
        return Array.from(filterCheckboxes).some(cb => cb.checked);
    }

    function updateClearButton() {
        const btn = document.getElementById('clearFilters');
        if (btn) btn.style.display = hasActiveFilters() ? 'inline-flex' : 'none';
    }

    // --- Event Listeners ---
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('[Recursos] Form submit interceptado');
            applyFilters();
        });
    }

    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applyFilters, 500);
            updateClearButton();
        });
    }

    [specialtySelect, typeSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                console.log('[Recursos] Select cambiado:', select.id, '=', select.value);
                applyFilters();
                updateClearButton();
            });
        }
    });

    console.log('[Recursos] Bindando', filterCheckboxes.length, 'checkboxes...');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            console.log('[Recursos] Checkbox cambiado:', {
                filterType: checkbox.dataset.filterType,
                value: checkbox.value,
                checked: checkbox.checked
            });
            applyFilters();
            updateClearButton();
        });
    });

    const clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            console.log('[Recursos] Limpiando todos los filtros');
            filterCheckboxes.forEach(cb => { cb.checked = false; });
            if (searchInput) searchInput.value = '';
            if (specialtySelect) specialtySelect.value = '';
            if (typeSelect) typeSelect.value = '';
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) sortSelect.value = 'position';
            updateClearButton();
            applyFilters();
        });
    }

    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', applyFilters);
    }
});
