/**
 * Dashboard JavaScript - Helin CMS
 *
 * Contains all dashboard-specific functionality including:
 * - Inactivity system
 * - Fullscreen toggle
 * - Logout handling
 * - Modal management
 * - Toast notifications
 */

// Global configuration
window.inactivityConfig = {
    warningTime: 600000,   // 10 min - Will be updated by server config
    logoutTime: 660000,    // 11 min - Will be updated by server config
    logoutUrl: '/cms/logout',
    csrfToken: ''
};

// Initialize dashboard functionality
// Módulo ES6 (Vite): ya se ejecuta tras el DOM parseado
initializeInactivitySystem();
initializeFullscreen();
initializeLogoutAlert();
initializeToastListeners();

// Toast notification system
function initializeToastListeners() {
    const setup = () => {
        // Evita que se ejecute y duplique si ya se configuró previamente
        if (window._dashboardToastSetup) return;

        if (!window.Livewire) {
            return;
        }

        // Marcamos como configurado inmediatamente
        window._dashboardToastSetup = true;

        Livewire.on('toast', ({ message, type }) => {

            if (window.Toastify) {
                // Definición de colores de borde e iconos según el tipo (Estilo moderno / Shadcn)
                const configMap = {
                    success: {
                        borderColor: 'border-emerald-500',
                        icon: '<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    },
                    error: {
                        borderColor: 'border-rose-500',
                        icon: '<svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    },
                    warning: {
                        borderColor: 'border-amber-500',
                        icon: '<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'
                    },
                    info: {
                        borderColor: 'border-blue-500',
                        icon: '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    }
                };

                const currentConfig = configMap[type] || configMap.info;

                // Estructura HTML en un único nodo contenedor para que Toastify lo monte de forma nativa
                const container = document.createElement('div');
                container.className = 'flex items-center gap-3';
                container.innerHTML = `
                    <div class="flex-shrink-0">${currentConfig.icon}</div>
                    <div class="text-xs font-medium text-slate-700 pr-2">${message}</div>
                `;

                window.Toastify({
                    node: container,   // Pasamos el nodo DOM real en lugar de un string en 'text'
                    duration: 4000,    // Un poco más de tiempo para lectura cómoda
                    gravity: "top",
                    position: "right",
                    // Integración limpia con tus estilos basados en Tailwind
                    className: `!bg-white !border-l-4 ${currentConfig.borderColor} !shadow-xl !rounded-xl !p-4 !max-w-sm !font-sans border border-slate-100`,
                    style: {
                        background: 'transparent', // Elimina el degradado por defecto de Toastify
                        boxShadow: 'none'         // Cede el control de la sombra a Tailwind
                    },
                    stopOnFocus: true
                }).showToast();
            } else {
                alert(`${type.toUpperCase()}: ${message}`);
            }
        });
    };

    if (window.Livewire) {
        setup();
    } else {
        document.addEventListener('livewire:init', setup);
    }

    // Fallback seguro: reintento sin riesgo de duplicidad gracias al flag de control
    setTimeout(() => {
        setup();
    }, 500);
}

// Inactivity System
function initializeInactivitySystem() {
    let inactivityTimer;
    let warningTimer;
    let lastActivity = Date.now();
    let warningShown = false;

    function resetTimers() {
        const now = Date.now();
        lastActivity = now;
        warningShown = false;

        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);

        // Timer para mostrar advertencia (desde server config: 10 min por defecto)
        const warningTime = window.inactivityConfig.warningTime;
        warningTimer = setTimeout(() => {
            if (!warningShown && (Date.now() - lastActivity) >= warningTime) {
                showInactivityWarning();
            }
        }, warningTime);

        // Timer para logout automático (desde server config: 11 min por defecto)
        const logoutTime = window.inactivityConfig.logoutTime;
        inactivityTimer = setTimeout(() => {
            performLogout();
        }, logoutTime);
    }

    function showInactivityWarning() {
        warningShown = true;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Sigue ahí?',
                text: 'Tu sesión está a punto de expirar por inactividad.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#09b6a2',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Continuar Sesión',
                cancelButtonText: 'Cerrar Sesión',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    continueSession();
                } else {
                    performLogout();
                }
            });
        } else {
            alert('Tu sesión está a punto de expirar. Haz clic para continuar.');
        }
    }

    function performLogout() {
        warningShown = true;

        if (typeof window.confirmLogout === 'function') {
            window.confirmLogout();
        } else {
            window.location.href = window.inactivityConfig.logoutUrl;
        }
    }

    function continueSession() {
        warningShown = false;
        resetTimers();
    }

    // Eventos que resetean el timer de inactividad
    const activityEvents = [
        'mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'
    ];

    activityEvents.forEach(event => {
        document.addEventListener(event, function(e) {
            resetTimers();
        }, true);
    });

    // Iniciar los timers cuando la página carga
    resetTimers();

    // Función global para continuar sesión
    window.continueSession = continueSession;

    // Limpiar timers cuando la página se cierra
    window.addEventListener('beforeunload', function() {
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);
    });
}

// Fullscreen functionality
function initializeFullscreen() {
    window.toggleFullscreen = function() {
        const fullscreenToggle = document.getElementById('fullscreen-toggle');
        if (!fullscreenToggle) {
            return;
        }

        const maximizeIcon = fullscreenToggle.querySelector('.block');
        const minimizeIcon = fullscreenToggle.querySelector('.hidden');

        if (!document.fullscreenElement) {
            // Entrar en fullscreen
            document.documentElement.requestFullscreen().then(() => {
                // Cambiar icono fullscreen
                maximizeIcon.classList.add('hidden');
                maximizeIcon.classList.remove('block');
                minimizeIcon.classList.remove('hidden');
                minimizeIcon.classList.add('block');
            }).catch(err => {
            });
        } else {
            // Salir de fullscreen
            document.exitFullscreen().then(() => {
                // Cambiar icono fullscreen
                minimizeIcon.classList.add('hidden');
                minimizeIcon.classList.remove('block');
                maximizeIcon.classList.remove('hidden');
                maximizeIcon.classList.add('block');
            }).catch(err => {
            });
        }
    };
}

// Logout Alert (SweetAlert2)
function initializeLogoutAlert() {
    window.confirmLogout = function() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = window.inactivityConfig.logoutUrl;

        document.body.appendChild(form);
        form.submit();
    };

    window.showLogoutAlert = function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que deseas cerrar tu sesión actual?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#09b6a2',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Cerrar Sesión',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.confirmLogout();
                }
            });
        } else {
            if (confirm('¿Estás seguro de que deseas cerrar tu sesión actual?')) {
                window.confirmLogout();
            }
        }
    };
}

// Generic confirmation dialog using SweetAlert2
window.confirmAction = function(config) {
    const {
        title = '¿Eliminar?',
        text = 'Esta acción no se puede deshacer.',
        confirmButtonText = 'Eliminar',
        cancelButtonText = 'Cancelar',
        confirmButtonColor = '#09B6A2',
        cancelButtonColor= '#ef4444',
        onConfirm = () => {},
        onCancel = null
    } = config;

    if (typeof Swal === 'undefined') {
        if (confirm(text)) onConfirm();
        return;
    }

    Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor,
        cancelButtonColor,
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirm();
        } else if (onCancel) {
            onCancel();
        }
    });
};

// Backward-compatible alias
window.confirmDelete = window.confirmAction;

// Update configuration from server
window.updateDashboardConfig = function(config) {
    window.inactivityConfig = {
        ...window.inactivityConfig,
        ...config
    };
};

// Export for external use
window.DashboardJS = {
    resetInactivityTimers: function() {
        if (typeof resetTimers !== 'undefined') {
            resetTimers();
        }
    },
    showInactivityWarning: function() {
        if (typeof showInactivityWarning !== 'undefined') {
            showInactivityWarning();
        }
    },
    toggleFullscreen: window.toggleFullscreen,
    updateConfig: window.updateDashboardConfig
};
