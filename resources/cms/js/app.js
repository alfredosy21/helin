import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"

// Para que esté disponible globalmente si lo necesitas
window.Toastify = Toastify;

/**
 * Helin Latam CMS Application JavaScript
 *
 * This file contains the main JavaScript functionality for the Helin Latam CMS.
 * It includes utility functions, component initialization, and global event handlers.
 *
 * @author Helin Latam Development Team
 * @version 1.0.0
 */

// Global CMS Object
window.HelinCMS = {
    // Configuration
    config: {
        apiBase: '/api',
        toastDuration: 3000,
        debounceDelay: 300,
        animationDuration: 300
    },

    // State
    state: {
        isAuthenticated: false,
        currentUser: null,
        theme: 'light',
        sidebarOpen: false
    },

    // Initialize the application
    init() {
        this.initTheme();
        this.initSidebar();
        this.initTooltips();
        this.initModals();
        this.initForms();
        this.initTables();
        this.initFileUploads();
        this.initKeyboardShortcuts();
        this.initAutoSave();
        this.initGlobalSearch();
        this.initNotifications();

        console.log('Helin CMS initialized');
    },

    // Theme Management
    initTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        this.setTheme(savedTheme);

        // Theme toggle button
        const themeToggle = document.querySelector('[data-theme-toggle]');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const newTheme = this.state.theme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
            });
        }
    },

    setTheme(theme) {
        this.state.theme = theme;
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('theme', theme);

        // Update theme toggle icon
        const themeIcon = document.querySelector('[data-theme-icon]');
        if (themeIcon) {
            themeIcon.setAttribute('name', theme === 'light' ? 'moon' : 'sun');
        }
    },

    // Sidebar Management
    initSidebar() {
        const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.querySelector('[data-sidebar]');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                this.toggleSidebar();
            });
        }

        // Close sidebar on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.state.sidebarOpen) {
                this.closeSidebar();
            }
        });
    },

    toggleSidebar() {
        this.state.sidebarOpen = !this.state.sidebarOpen;
        const sidebar = document.querySelector('[data-sidebar]');

        if (sidebar) {
            sidebar.classList.toggle('translate-x-0', this.state.sidebarOpen);
            sidebar.classList.toggle('-translate-x-full', !this.state.sidebarOpen);
        }

        // Update body scroll
        document.body.classList.toggle('overflow-hidden', this.state.sidebarOpen);
    },

    closeSidebar() {
        this.state.sidebarOpen = false;
        const sidebar = document.querySelector('[data-sidebar]');

        if (sidebar) {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
        }

        document.body.classList.remove('overflow-hidden');
    },

    // Toast Notifications
    showToast(message, type = 'success', duration = null) {
        const toast = document.createElement('div');
        const toastDuration = duration || this.config.toastDuration;

        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-current rounded-full flex items-center justify-center">
                    ${this.getToastIcon(type)}
                </div>
                <div>
                    <p class="font-medium">${message}</p>
                </div>
                <button class="ml-auto text-current hover:opacity-70" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, 100);

        // Auto remove
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }, toastDuration);
    },

    getToastIcon(type) {
        const icons = {
            success: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
            error: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
            warning: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.24 2.502-2.5V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
            info: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        };

        return icons[type] || icons.info;
    },

    // Form Enhancements
    initForms() {
        // Auto-resize textareas
        const textareas = document.querySelectorAll('textarea[data-auto-resize]');
        textareas.forEach(textarea => {
            this.autoResizeTextarea(textarea);
            textarea.addEventListener('input', () => this.autoResizeTextarea(textarea));
        });

        // Character counters
        const charCounters = document.querySelectorAll('[data-char-counter]');
        charCounters.forEach(counter => {
            this.initCharCounter(counter);
        });

        // Form validation
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            this.initFormValidation(form);
        });
    },

    autoResizeTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    },

    initCharCounter(element) {
        const input = document.querySelector(element.dataset.charCounter);
        const counter = element;

        if (input && counter) {
            const updateCounter = () => {
                const length = input.value.length;
            const maxLength = input.getAttribute('maxlength') || Infinity;
            counter.textContent = `${length}/${maxLength}`;
            counter.classList.toggle('text-red-500', length > maxLength * 0.9);
        };

            input.addEventListener('input', updateCounter);
            updateCounter();
        }
    },

    initFormValidation(form) {
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) {
                e.preventDefault();
                this.showFormErrors(form);
            }
        });

        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
        });
    },

    validateField(field) {
        field.classList.remove('border-red-500', 'border-green-500');

        if (field.validity.valid) {
            field.classList.add('border-green-500');
        } else if (!field.validity.valid && field.value) {
            field.classList.add('border-red-500');
        }
    },

    showFormErrors(form) {
        const inputs = form.querySelectorAll('input:invalid, textarea:invalid, select:invalid');
        inputs.forEach(input => {
            input.classList.add('border-red-500');
            this.showToast(input.validationMessage, 'error');
        });
    },

    // Table Enhancements
    initTables() {
        const tables = document.querySelectorAll('[data-sortable]');
        tables.forEach(table => {
            this.initSortableTable(table);
        });

        const searchables = document.querySelectorAll('[data-searchable]');
        searchables.forEach(table => {
            this.initSearchableTable(table);
        });
    },

    initSortableTable(table) {
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.addEventListener('click', () => {
                this.sortTable(table, header.dataset.sort);
            });
        });
    },

    initSearchableTable(table) {
        const searchInput = document.querySelector(table.dataset.searchable);
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.searchTable(table, e.target.value);
            }, this.config.debounceDelay));
        }
    },

    sortTable(table, column) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const isAscending = table.dataset.sortOrder !== 'asc';

        rows.sort((a, b) => {
            const aValue = a.querySelector(`td[data-column="${column}"]`)?.textContent || '';
            const bValue = b.querySelector(`td[data-column="${column}"]`)?.textContent || '';

            return isAscending
                ? aValue.localeCompare(bValue)
                : bValue.localeCompare(aValue);
        });

        rows.forEach(row => tbody.appendChild(row));
        table.dataset.sortOrder = isAscending ? 'asc' : 'desc';
    },

    searchTable(table, query) {
        const rows = table.querySelectorAll('tbody tr');
        const lowerQuery = query.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(lowerQuery) ? '' : 'none';
        });
    },

    // File Upload Enhancements
    initFileUploads() {
        const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
        fileInputs.forEach(input => {
            this.initFilePreview(input);
        });
    },

    initFilePreview(input) {
        const preview = document.querySelector(input.dataset.preview);

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file && preview) {
                this.showFilePreview(file, preview);
            }
        });
    },

    showFilePreview(file, preview) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">`;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = `
                <div class="flex items-center justify-center w-full h-32 bg-gray-100 rounded-lg">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.707.293L19.414 15H17a2 2 0 002-2v-6a2 2 0 00-2-2H7a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm text-gray-600">${file.name}</p>
                    </div>
                </div>
            `;
        }
    },

    // Keyboard Shortcuts
    initKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K for global search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                this.openGlobalSearch();
            }

            // Ctrl/Cmd + S for save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.saveCurrentForm();
            }

            // Ctrl/Cmd + / for help
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                this.showHelp();
            }
        });
    },

    openGlobalSearch() {
        const searchModal = document.querySelector('[data-global-search]');
        if (searchModal) {
            searchModal.classList.remove('hidden');
            const input = searchModal.querySelector('input');
            if (input) {
                setTimeout(() => input.focus(), 100);
            }
        }
    },

    saveCurrentForm() {
        const activeForm = document.querySelector('form:focus-within');
        if (activeForm) {
            const submitButton = activeForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.click();
            }
        }
    },

    showHelp() {
        this.showToast('Presiona Ctrl+K para buscar, Ctrl+S para guardar', 'info');
    },

    // Auto-save functionality
    initAutoSave() {
        const forms = document.querySelectorAll('form[data-auto-save]');
        forms.forEach(form => {
            this.initAutoSaveForm(form);
        });
    },

    initAutoSaveForm(form) {
        let saveTimeout;
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    this.autoSaveForm(form);
                }, 2000);
            });
        });
    },

    autoSaveForm(form) {
        const formData = new FormData(form);
        const action = form.action || window.location.href;

        fetch(action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showToast('Cambios guardados automáticamente', 'success');
            }
        })
        .catch(error => {
            console.error('Auto-save failed:', error);
        });
    },

    // Global Search
    initGlobalSearch() {
        const searchInput = document.querySelector('[data-global-search-input]');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                    this.performGlobalSearch(e.target.value);
                }, this.config.debounceDelay));
        }
    },

    performGlobalSearch(query) {
        if (!query) return;

        fetch(`${this.config.apiBase}/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                this.displaySearchResults(data);
            })
            .catch(error => {
                console.error('Search failed:', error);
                this.showToast('Error en la búsqueda', 'error');
            });
    },

    displaySearchResults(results) {
        const resultsContainer = document.querySelector('[data-search-results]');
        if (resultsContainer) {
            resultsContainer.innerHTML = this.renderSearchResults(results);
        }
    },

    renderSearchResults(results) {
        if (results.length === 0) {
            return '<div class="text-center py-8 text-gray-500">No se encontraron resultados</div>';
        }

        return results.map(result => `
            <div class="p-4 border-b hover:bg-gray-50 cursor-pointer" data-search-result>
                <div class="font-medium">${result.title}</div>
                <div class="text-sm text-gray-600">${result.description}</div>
                <div class="text-xs text-gray-500">${result.type}</div>
            </div>
        `).join('');
    },

    // Notifications
    initNotifications() {
        // Check for notifications every 30 seconds
        setInterval(() => {
            this.checkNotifications();
        }, 30000);

        // Initialize notification dropdown
        const notificationButton = document.querySelector('[data-notifications]');
        if (notificationButton) {
            notificationButton.addEventListener('click', () => {
                this.toggleNotificationDropdown();
            });
        }
    },

    checkNotifications() {
        // Skip notifications check to avoid endpoint errors
        // fetch(`${this.config.apiBase}/notifications`)
        //     .then(response => response.json())
        //     .then(data => {
        //         this.updateNotificationBadge(data.unread);
        //     })
        //     .catch(error => {
        //         console.error('Failed to check notifications:', error);
        //     });

        // Temporarily disable notifications to avoid errors
        console.log('Notifications check disabled');
    },

    updateNotificationBadge(count) {
        const badge = document.querySelector('[data-notification-badge]');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'block' : 'none';
        }
    },

    toggleNotificationDropdown() {
        const dropdown = document.querySelector('[data-notification-dropdown]');
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    },

    // Tooltips
    initTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(element => {
            this.initTooltip(element);
        });
    },

    initTooltip(element) {
        element.addEventListener('mouseenter', (e) => {
            this.showTooltip(e.target, element.dataset.tooltip);
        });

        element.addEventListener('mouseleave', () => {
            this.hideTooltip();
        });
    },

    showTooltip(element, text) {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg';
        tooltip.textContent = text;
        tooltip.id = 'tooltip';

        document.body.appendChild(tooltip);

        const rect = element.getBoundingClientRect();
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;
        tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px`;
    },

    hideTooltip() {
        const tooltip = document.getElementById('tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    },

    // Modals
    initModals() {
        const modals = document.querySelectorAll('[data-modal]');
        modals.forEach(modal => {
            this.initModal(modal);
        });
    },

    initModal(modal) {
        const trigger = document.querySelector(`[data-modal-trigger="${modal.id}"]`);
        const close = modal.querySelector('[data-modal-close]');

        if (trigger) {
            trigger.addEventListener('click', () => {
                this.openModal(modal.id);
            });
        }

        if (close) {
            close.addEventListener('click', () => {
                this.closeModal(modal.id);
            });
        }

        // Close on backdrop click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeModal(modal.id);
            }
        });

        // Close on escape
        modal.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal(modal.id);
            }
        });
    },

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Focus first input
            const firstInput = modal.querySelector('input, textarea, button');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    },

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    },

    // Utility Functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },

    formatDate(date, options = {}) {
        return new Intl.DateTimeFormat('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            ...options
        }).format(new Date(date));
    },

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },

    // API helpers
    async apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        };

        const response = await fetch(url, { ...defaultOptions, ...options });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    },

    // Livewire integration
    livewire: {
        // Custom Livewire events
        on(component, method, callback) {
            if (window.Livewire) {
                window.Livewire.on(component, method, callback);
            }
        },

        // Emit custom events
        emit(event, data = {}) {
            if (window.Livewire) {
                window.Livewire.emit(event, data);
            }
        },

        // Show loading state
        loading(component, state = true) {
            if (window.Livewire) {
                window.Livewire.find(component).loading = state;
            }
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    HelinCMS.init();
});

// Export for external use
window.HelinCMS = HelinCMS;
