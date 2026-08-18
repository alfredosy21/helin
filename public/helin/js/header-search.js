/**
 * Header Search Autocomplete - Helin
 * Búsqueda inteligente con autocompletado moderno
 */
class HelinHeaderSearch {
    constructor() {
        this.searchInput = null;
        this.searchDropdown = null;
        this.searchTimeout = null;
        this.minQueryLength = 3;
        this.maxResults = 8;
        this.currentResults = [];
        this.selectedIndex = -1;

        this.init();
    }

    init() {
        // Buscador Desktop
        this.setupSearch('desktop', '#header-search-input', '#header-search-dropdown', '#header-search-submit');

        // Buscador Móvil
        this.setupSearch('mobile', '#mobile-search-input', '#mobile-search-dropdown', '#mobile-search-submit');

        // Selector de categoría del buscador: filtrar catálogo al elegir
        const categorySelect = document.getElementById('header-category-select');
        if (categorySelect) {
            categorySelect.addEventListener('change', () => this.executeCategoryNavigation());
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', this.handleOutsideClick.bind(this));

        // Navegación con teclado
        document.addEventListener('keydown', this.handleKeyboardNavigation.bind(this));
    }

    /**
     * Construye la URL del catálogo combinando búsqueda y categoría.
     */
    buildCatalogUrl(extraQuery) {
        const params = new URLSearchParams();
        const query = extraQuery !== undefined ? extraQuery : this.getActiveQuery();
        if (query) params.set('search', query);
        const categorySelect = document.getElementById('header-category-select');
        if (categorySelect && categorySelect.value) params.set('category', categorySelect.value);
        const qs = params.toString();
        return '/catalogo' + (qs ? '?' + qs : '');
    }

    getActiveQuery() {
        const desktop = document.querySelector('#header-search-input')?.value.trim() || '';
        const mobile = document.querySelector('#mobile-search-input')?.value.trim() || '';
        return desktop || mobile;
    }

    /**
     * Navega al catálogo filtrado por la categoría seleccionada
     * (incluye el término de búsqueda si hay uno escrito).
     */
    executeCategoryNavigation() {
        window.location.href = this.buildCatalogUrl();
    }

    setupSearch(type, inputSelector, dropdownSelector, submitSelector) {
        const input = document.querySelector(inputSelector);
        const dropdown = document.querySelector(dropdownSelector);
        const submitBtn = submitSelector ? document.querySelector(submitSelector) : null;

        if (!input || !dropdown) return;

        // Botón de lupa
        if (submitBtn) {
            submitBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.executeSearch(type);
            });
        }

        input.addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            const query = e.target.value.trim();

            if (query.length < this.minQueryLength) {
                this.hideDropdown(dropdown);
                return;
            }

            // Debounce para evitar muchas peticiones
            this.searchTimeout = setTimeout(() => {
                this.performSearch(query, dropdown, type);
            }, 300);
        });

        input.addEventListener('focus', (e) => {
            const query = e.target.value.trim();
            if (query.length >= this.minQueryLength && this.currentResults.length > 0) {
                this.showDropdown(dropdown);
            }
        });

        input.addEventListener('keydown', (e) => {
            this.handleInputKeydown(e, dropdown, type);
        });
    }

    executeSearch(type) {
        const input = document.querySelector(type === 'mobile' ? '#mobile-search-input' : '#header-search-input');
        const query = input?.value.trim() || '';

        window.location.href = this.buildCatalogUrl(query);
    }

    async performSearch(query, dropdown, type) {
        try {
            this.showLoading(dropdown);

            const response = await fetch(`/api/search/products?q=${encodeURIComponent(query)}&limit=${this.maxResults}`);
            const products = await response.json();

            this.currentResults = products;
            this.renderResults(products, dropdown, type);

            if (products.length > 0) {
                this.showDropdown(dropdown);
            } else {
                this.showNoResults(dropdown);
            }
        } catch (error) {
            console.error('Error en búsqueda:', error);
            this.showError(dropdown);
        }
    }

    renderResults(products, dropdown, type) {
        const isMobile = type === 'mobile';
        const dropdownClass = isMobile ? 'mobile-search-dropdown' : 'header-search-dropdown';
        const inputSelector = isMobile ? '#mobile-search-input' : '#header-search-input';
        const searchValue = document.querySelector(inputSelector)?.value || '';

        dropdown.innerHTML = `
            <div class="${dropdownClass}">
                <div class="search-results-header">
                    <span class="results-count">${products.length} productos encontrados</span>
                    <button class="view-all-btn" data-type="${type}">
                        Ver todos <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <div class="search-results-list">
                    ${products.map((product, index) => this.renderProduct(product, index, isMobile)).join('')}
                </div>
            </div>
        `;

        // Agregar event listeners a los resultados
        const viewAllBtn = dropdown.querySelector('.view-all-btn');
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.executeSearch(type);
            });
        }

        dropdown.querySelectorAll('.search-result-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                if (products[index] && products[index].url) {
                    window.location.href = products[index].url;
                }
            });

            item.addEventListener('mouseenter', () => {
                this.selectedIndex = index;
                this.updateActiveItem(dropdown);
            });
        });
    }

    renderProduct(product, index, isMobile) {
        const price = product.is_on_sale && product.sale_price ? product.sale_price : product.price;
        const oldPrice = product.is_on_sale ? product.price : null;
        const badge = product.is_new ? 'Nuevo' : (product.is_on_sale ? 'Oferta' : '');

        // Validar que el producto tenga los datos necesarios
        if (!product || !product.name) {
            return '';
        }

        const safeName = product.name || '';
        const safeImage = product.image || '';
        const safeCategory = product.category || 'Sin categoría';
        const safeBrand = product.brand || 'Helin';
        const safeUrl = product.url || '#';

        return `
            <div class="search-result-item ${index === 0 ? 'first' : ''}" data-index="${index}">
                <div class="search-result-image">
                    <img src="${safeImage}" alt="${safeName}" loading="lazy">
                    ${badge ? `<span class="search-result-badge">${badge}</span>` : ''}
                </div>
                <div class="search-result-content">
                    <div class="search-result-title">${this.highlightMatch(safeName)}</div>
                    <div class="search-result-meta">
                        <span class="search-result-category">${safeCategory}</span>
                        <span class="search-result-brand">${safeBrand}</span>
                    </div>
                    <div class="search-result-price">
                        ${oldPrice ? `<span class="old-price">$${parseFloat(oldPrice).toFixed(2)}</span>` : ''}
                        <span class="current-price">$${parseFloat(price).toFixed(2)}</span>
                    </div>
                </div>
            </div>
        `;
    }

    highlightMatch(text) {
        if (!text) return '';

        const query = document.querySelector('#header-search-input')?.value.trim() ||
                     document.querySelector('#mobile-search-input')?.value.trim() || '';

        if (!query) return text;

        // Escapar caracteres especiales del regex
        const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp(`(${escapedQuery})`, 'gi');

        return text.replace(regex, '<mark>$1</mark>');
    }

    showLoading(dropdown) {
        dropdown.innerHTML = `
            <div class="search-loading">
                <div class="search-spinner"></div>
                <span>Buscando productos...</span>
            </div>
        `;
        this.showDropdown(dropdown);
    }

    showNoResults(dropdown) {
        dropdown.innerHTML = `
            <div class="search-no-results">
                <i class="fas fa-search"></i>
                <span>No se encontraron productos</span>
                <small>Intenta con otros términos</small>
            </div>
        `;
        this.showDropdown(dropdown);
    }

    showError(dropdown) {
        dropdown.innerHTML = `
            <div class="search-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Error en la búsqueda</span>
                <small>Intenta nuevamente</small>
            </div>
        `;
        this.showDropdown(dropdown);
    }

    showDropdown(dropdown) {
        dropdown.classList.remove('hidden');
        dropdown.classList.add('show');
    }

    hideDropdown(dropdown) {
        dropdown.classList.remove('show');
        dropdown.classList.add('hidden');
        this.selectedIndex = -1;
    }

    handleOutsideClick(e) {
        const desktopSearch = document.querySelector('#header-search-input');
        const mobileSearch = document.querySelector('#mobile-search-input');
        const desktopDropdown = document.querySelector('#header-search-dropdown');
        const mobileDropdown = document.querySelector('#mobile-search-dropdown');

        if (desktopSearch && !desktopSearch.contains(e.target) && desktopDropdown) {
            this.hideDropdown(desktopDropdown);
        }

        if (mobileSearch && !mobileSearch.contains(e.target) && mobileDropdown) {
            this.hideDropdown(mobileDropdown);
        }
    }

    handleInputKeydown(e, dropdown, type) {
        const items = dropdown.querySelectorAll('.search-result-item');

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectedIndex = Math.min(this.selectedIndex + 1, items.length - 1);
                this.updateActiveItem(dropdown);
                break;

            case 'ArrowUp':
                e.preventDefault();
                this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
                this.updateActiveItem(dropdown);
                break;

            case 'Enter':
                e.preventDefault();
                if (this.selectedIndex >= 0 && items[this.selectedIndex]) {
                    items[this.selectedIndex].click();
                } else {
                    this.executeSearch(type);
                }
                break;

            case 'Escape':
                this.hideDropdown(dropdown);
                e.target.blur();
                break;
        }
    }

    handleKeyboardNavigation(e) {
        // Manejar navegación global si está activo el buscador
        if (e.defaultPrevented) return;

        const activeElement = document.activeElement;
        const isSearchInput = activeElement?.id === 'header-search-input' || activeElement?.id === 'mobile-search-input';

        if (!isSearchInput) return;

        const dropdownSelector = activeElement?.id === 'header-search-input' ?
                               '#header-search-dropdown' : '#mobile-search-dropdown';
        const dropdown = document.querySelector(dropdownSelector);

        if (dropdown) {
            const type = activeElement?.id === 'header-search-input' ? 'desktop' : 'mobile';
            this.handleInputKeydown(e, dropdown, type);
        }
    }

    updateActiveItem(dropdown) {
        const items = dropdown.querySelectorAll('.search-result-item');

        items.forEach((item, index) => {
            if (index === this.selectedIndex) {
                item.classList.add('active');
                // Hacer scroll si es necesario
                item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            } else {
                item.classList.remove('active');
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new HelinHeaderSearch();
});

// Exponer para uso global
window.HelinHeaderSearch = HelinHeaderSearch;
