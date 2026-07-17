/**
 * Helen Theme - Shared JavaScript Components
 * Used across all pages: catalogo.html, carrito.html, producto.html, solicitud.html
 */

document.addEventListener('DOMContentLoaded', function() {
    initMobileSearch();
    initMobileNavigation();
    initMobileFilters();
    initScrollToTop();
});

/**
 * Mobile Search Toggle
 * Toggles the mobile search bar visibility
 */
function initMobileSearch() {
    const mobileSearchBtn = document.getElementById('mobile-search-btn');
    const mobileSearch = document.getElementById('mobile-search');

    if (mobileSearchBtn && mobileSearch) {
        mobileSearchBtn.addEventListener('click', () => {
            mobileSearch.classList.toggle('hidden');
            if (!mobileSearch.classList.contains('hidden')) {
                const input = mobileSearch.querySelector('input');
                if (input) input.focus();
            }
        });
    }
}

/**
 * Mobile Navigation Drawer
 * Handles the slide-in navigation menu for mobile devices
 */
function initMobileNavigation() {
    const mobileNavBtn = document.getElementById('mobile-menu-btn');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileNavPanel = document.getElementById('mobile-nav-panel');
    const closeMobileNav = document.getElementById('close-mobile-nav');
    const mobileNavOverlay = document.getElementById('mobile-nav-overlay');

    if (mobileNavBtn && mobileNav) {
        // Open navigation
        mobileNavBtn.addEventListener('click', () => {
            mobileNav.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                if (mobileNavPanel) {
                    mobileNavPanel.classList.remove('-translate-x-full');
                }
            }, 10);
        });

        // Close navigation function
        const hideMobileNav = () => {
            if (mobileNavPanel) {
                mobileNavPanel.classList.add('-translate-x-full');
            }
            setTimeout(() => {
                mobileNav.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        };

        // Close on button click
        if (closeMobileNav) {
            closeMobileNav.addEventListener('click', hideMobileNav);
        }

        // Close on overlay click
        if (mobileNavOverlay) {
            mobileNavOverlay.addEventListener('click', hideMobileNav);
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileNav.classList.contains('hidden')) {
                hideMobileNav();
            }
        });
    }
}

/**
 * Mobile Filters Drawer
 * Handles the slide-in filters panel for mobile devices
 */
function initMobileFilters() {
    const mobileFilterBtn = document.getElementById('mobile-filter-btn');
    const mobileFilters = document.getElementById('mobile-filters');
    const mobileFiltersPanel = document.getElementById('mobile-filters-panel');
    const closeMobileFilters = document.getElementById('close-mobile-filters');
    const mobileFiltersOverlay = document.getElementById('mobile-filters-overlay');

    if (mobileFilterBtn && mobileFilters) {
        // Open filters
        mobileFilterBtn.addEventListener('click', () => {
            mobileFilters.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                if (mobileFiltersPanel) {
                    mobileFiltersPanel.classList.remove('translate-x-full');
                }
            }, 10);
        });

        // Close filters function
        const hideMobileFilters = () => {
            if (mobileFiltersPanel) {
                mobileFiltersPanel.classList.add('translate-x-full');
            }
            setTimeout(() => {
                mobileFilters.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        };

        // Close on button click
        if (closeMobileFilters) {
            closeMobileFilters.addEventListener('click', hideMobileFilters);
        }

        // Close on overlay click
        if (mobileFiltersOverlay) {
            mobileFiltersOverlay.addEventListener('click', hideMobileFilters);
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileFilters.classList.contains('hidden')) {
                hideMobileFilters();
            }
        });
    }
}

/**
 * Scroll to Top Button
 * Shows/hides the scroll to top button based on scroll position
 */
function initScrollToTop() {
    const scrollToTopBtn = document.querySelector('.scroll-to-top') || 
                           document.querySelector('[onclick*="scrollTo({top: 0"]');
    
    if (scrollToTopBtn) {
        // Show/hide based on scroll position
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollToTopBtn.style.opacity = '1';
                scrollToTopBtn.style.visibility = 'visible';
            } else {
                scrollToTopBtn.style.opacity = '0';
                scrollToTopBtn.style.visibility = 'hidden';
            }
        });

        // Initial state
        scrollToTopBtn.style.opacity = '0';
        scrollToTopBtn.style.visibility = 'hidden';
        scrollToTopBtn.style.transition = 'opacity 0.3s ease, visibility 0.3s ease';
    }
}

/**
 * Utility function to format prices
 */
function formatPrice(price) {
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
}

/**
 * Utility function to debounce function calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Lazy load images
 */
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const src = img.dataset.src;
                // Solo asignar si data-src es válido
                if (src && src !== '' && src !== 'undefined') {
                    img.src = src;
                }
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => {
        const src = img.dataset.src;
        // Solo observar si tiene data-src válido
        if (src && src !== '' && src !== 'undefined') {
            imageObserver.observe(img);
        }
    });
}

// Export functions for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initMobileSearch,
        initMobileNavigation,
        initMobileFilters,
        initScrollToTop,
        formatPrice,
        debounce,
        initLazyLoading
    };
}
