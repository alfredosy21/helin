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
console.log('Dashboard JS initialized');
initializeInactivitySystem();
initializeFullscreen();
initializeLogoutAlert();
initializeToastListeners();

// Toast notification system
function initializeToastListeners() {
    const setup = () => {
        if (!window.Livewire) {
            console.warn('Livewire not available for toast setup');
            return;
        }
        console.log('Livewire initialized in dashboard, setting up toast listeners');

        Livewire.on('toast', ({ message, type }) => {
            console.log('Toast received in dashboard:', { message, type });

            if (window.Toastify) {
                window.Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: type === 'error' ? 'linear-gradient(to right, #ff5f56, #ff3b30)' :
                                   type === 'success' ? 'linear-gradient(to right, #00b09b, #96c93d)' :
                                   type === 'warning' ? 'linear-gradient(to right, #ff9500, #ff6200)' :
                                   'linear-gradient(to right, #007aff, #0051d5)',
                    stopOnFocus: true
                }).showToast();
            } else {
                console.warn('No toast system available in dashboard, using alert');
                alert(`${type.toUpperCase()}: ${message}`);
            }
        });
    };

    if (window.Livewire) {
        setup();
    } else {
        document.addEventListener('livewire:init', setup);
    }

    // Fallback: si por alguna razón no se capturó el evento, reintentar
    setTimeout(() => {
        if (window.Livewire && !window._dashboardToastSetup) {
            window._dashboardToastSetup = true;
            setup();
        }
    }, 500);
}

// Inactivity System
function initializeInactivitySystem() {
    let inactivityTimer;
    let warningTimer;
    let lastActivity = Date.now();
    let warningShown = false;

    console.log('Inactivity system initialized');
    console.log('Warning time:', window.inactivityConfig.warningTime, 'ms');
    console.log('Logout time:', window.inactivityConfig.logoutTime, 'ms');

    function resetTimers() {
        const now = Date.now();
        lastActivity = now;
        warningShown = false;

        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);

        console.log('Timers reset at:', new Date().toLocaleTimeString());

        // Timer para mostrar advertencia (desde server config: 10 min por defecto)
        const warningTime = window.inactivityConfig.warningTime;
        warningTimer = setTimeout(() => {
            if (!warningShown && (Date.now() - lastActivity) >= warningTime) {
                console.log('Showing inactivity warning');
                showInactivityWarning();
            }
        }, warningTime);

        // Timer para logout automático (desde server config: 11 min por defecto)
        const logoutTime = window.inactivityConfig.logoutTime;
        inactivityTimer = setTimeout(() => {
            console.log('Performing automatic logout');
            performLogout();
        }, logoutTime);
    }

    function showInactivityWarning() {
        warningShown = true;
        console.log('Showing inactivity SweetAlert2');

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
            console.error('SweetAlert2 not found');
            alert('Tu sesión está a punto de expirar. Haz clic para continuar.');
        }
    }

    function performLogout() {
        console.log('Executing logout...');
        warningShown = true;

        if (typeof window.confirmLogout === 'function') {
            window.confirmLogout();
        } else {
            console.error('confirmLogout function not found');
            window.location.href = window.inactivityConfig.logoutUrl;
        }
    }

    function continueSession() {
        console.log('Session continued by user');
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
    console.log('Starting initial timers');
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
            console.error('Fullscreen toggle button not found');
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
                console.log('Entered fullscreen mode');
            }).catch(err => {
                console.error('Error attempting to enable fullscreen:', err);
            });
        } else {
            // Salir de fullscreen
            document.exitFullscreen().then(() => {
                // Cambiar icono fullscreen
                minimizeIcon.classList.add('hidden');
                minimizeIcon.classList.remove('block');
                maximizeIcon.classList.remove('hidden');
                maximizeIcon.classList.add('block');
                console.log('Exited fullscreen mode');
            }).catch(err => {
                console.error('Error attempting to exit fullscreen:', err);
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
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#09b6a2',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Cerrar Sesión',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
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

// Update configuration from server
window.updateDashboardConfig = function(config) {
    window.inactivityConfig = {
        ...window.inactivityConfig,
        ...config
    };
    console.log('Dashboard config updated:', window.inactivityConfig);
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
