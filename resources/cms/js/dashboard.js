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
    warningTime: 60000,    // Will be updated by server config
    logoutTime: 120000,    // Will be updated by server config
    logoutUrl: '/cms/logout',
    csrfToken: ''
};

// Initialize dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JS initialized');
    initializeInactivitySystem();
    initializeFullscreen();
    initializeModalHandlers();
    initializeToastListeners();
});

// Toast notification system
function initializeToastListeners() {
    // Escuchar eventos de Toast desde Livewire
    document.addEventListener('livewire:init', () => {
        console.log('Livewire initialized in dashboard, setting up toast listeners');

        Livewire.on('toast', ({ message, type }) => {
            console.log('Toast received in dashboard:', { message, type });

            if(window.showToast) {
                window.showToast(message, type);
            } else if(window.Toastify) {
                // Usar Toastify si está disponible
                console.log('Using Toastify for notification in dashboard');
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
                // Fallback: alert simple
                console.warn('No toast system available in dashboard, using alert');
                alert(`${type.toUpperCase()}: ${message}`);
            }
        });
    });
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

        // Timer para mostrar advertencia (30 segundos para testing)
        const warningTime = 30000; // 30 segundos para testing
        warningTimer = setTimeout(() => {
            if (!warningShown && (Date.now() - lastActivity) >= warningTime) {
                console.log('Showing inactivity warning');
                showInactivityWarning();
            }
        }, warningTime);

        // Timer para logout automático (60 segundos para testing)
        const logoutTime = 60000; // 60 segundos para testing
        inactivityTimer = setTimeout(() => {
            console.log('Performing automatic logout');
            performLogout();
        }, logoutTime);
    }

    function showInactivityWarning() {
        warningShown = true;
        console.log('Opening inactivity modal');

        const modal = document.getElementById('inactivityModal');
        if (modal) {
            if (typeof openModal === 'function') {
                openModal('inactivityModal');
                console.log('Modal opened successfully');
            } else {
                console.error('openModal function not found');
                // Fallback: mostrar alert simple
                alert('Tu sesión está a punto de expirar. Haz clic para continuar.');
            }
        } else {
            console.error('Inactivity modal not found');
        }
    }

    function performLogout() {
        console.log('Executing logout...');
        warningShown = true;

        if (typeof window.confirmLogout === 'function') {
            window.confirmLogout();
        } else {
            console.error('confirmLogout function not found');
            // Fallback: redirect directo
            window.location.href = window.inactivityConfig.logoutUrl;
        }
    }

    function continueSession() {
        console.log('Session continued by user');
        warningShown = false;
        resetTimers();

        if (typeof closeModal === 'function') {
            closeModal('inactivityModal');
        }
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

    // Timer de debug cada 10 segundos
    setInterval(() => {
        const inactive = Date.now() - lastActivity;
        console.log('Inactive for:', Math.round(inactive / 1000), 'seconds');
    }, 10000);

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

// Modal handlers
function initializeModalHandlers() {
    // Logout modal handler
    window.confirmLogout = function() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = window.inactivityConfig.logoutUrl;

        document.body.appendChild(form);
        form.submit();
    };

    // Modal event listeners
    document.addEventListener('modalConfirmed', function(event) {
        if (event.detail.modalId === 'logoutModal') {
            window.confirmLogout();
        } else if (event.detail.modalId === 'inactivityModal') {
            window.continueSession();
        }
    });
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
