/**
 * Auth JavaScript - Helin CMS
 *
 * Contains all authentication-specific functionality including:
 * - Toast notifications
 * - Livewire integration
 * - Password toggle
 * - Form enhancements
 */

// Initialize auth functionality
// Módulo ES6 (Vite): ya se ejecuta tras el DOM parseado
initializeToastListeners();
initializePasswordToggle();

// Toast notification system
function initializeToastListeners() {
    const setup = () => {
        // Evita que se ejecute y duplique si ya se configuró previamente
        if (window._authToastSetup) return;

        if (!window.Livewire) {
            return;
        }

        // Marcamos como configurado inmediatamente
        window._authToastSetup = true;

        Livewire.on('toast', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            const message = data?.message ?? data?.detail?.message ?? 'Mensaje no disponible';
            const type    = data?.type    ?? data?.detail?.type    ?? 'info';

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

                // Estructura HTML en un único nodo contenedor para renderizado nativo
                const container = document.createElement('div');
                container.className = 'flex items-center gap-3';
                container.innerHTML = `
                    <div class="flex-shrink-0">${currentConfig.icon}</div>
                    <div class="text-xs font-medium text-slate-700 pr-2">${message}</div>
                `;

                window.Toastify({
                    node: container,   // Pasamos el nodo DOM real en lugar de un string en 'text'
                    duration: 4000,    // Duración óptima de lectura
                    gravity: "top",
                    position: "right",
                    // Integración limpia con tus clases de Tailwind CSS
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

// Password toggle functionality
function initializePasswordToggle() {
    // La función togglePassword ya está definida inline
    // pero podemos agregar mejoras aquí si es necesario
}

// Password visibility toggle (función global para compatibilidad)
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.type = field.type === 'password' ? 'text' : 'password';

        // Actualizar icono si existe
        const button = field.nextElementSibling;
        if (button && button.querySelector('svg')) {
            const icon = button.querySelector('svg');
            if (icon) {
                // Cambiar entre eye y eye-off
                const isPassword = field.type === 'password';
                // Aquí podrías cambiar el icono si tienes diferentes SVGs
            }
        }
    } else {
        // Field not found - silently fail
    }
};

// Form validation enhancements
window.validateAuthForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) {
        return false;
    }

    const email = form.querySelector('input[type="email"]');
    const password = form.querySelector('input[type="password"]');

    let isValid = true;

    // Validación de email
    if (email && !email.value.trim()) {
        showError(email, 'El email es requerido');
        isValid = false;
    } else if (email && !isValidEmail(email.value)) {
        showError(email, 'El email no es válido');
        isValid = false;
    }

    // Validación de password
    if (password && !password.value.trim()) {
        showError(password, 'La contraseña es requerida');
        isValid = false;
    } else if (password && password.value.length < 8) {
        showError(password, 'La contraseña debe tener al menos 8 caracteres');
        isValid = false;
    }

    return isValid;
};

// Helper function for email validation
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show field error
function showError(field, message) {
    // Remover error anterior si existe
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }

    // Agregar clase de error al campo
    field.classList.add('border-red-500');

    // Crear mensaje de error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error mt-1 text-sm text-red-600';
    errorDiv.textContent = message;

    field.parentNode.appendChild(errorDiv);

    // Remover error después de 5 segundos
    setTimeout(() => {
        errorDiv.remove();
        field.classList.remove('border-red-500');
    }, 5000);
}

// Clear all field errors
window.clearAuthErrors = function() {
    document.querySelectorAll('.field-error').forEach(error => error.remove());
    document.querySelectorAll('.border-red-500').forEach(field => {
        field.classList.remove('border-red-500');
    });
};

// Enhanced login form submission
window.submitAuthForm = function(formId, action) {
    if (validateAuthForm(formId)) {
        const form = document.getElementById(formId);

        // Mostrar loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Procesando...
        `;

        // Enviar formulario
        if (action === 'login') {
            // Para Livewire login
            window.livewire.find('app.http.controllers.cms.authenticated-session-controller')
                .call('login');
        } else {
            // Para submit tradicional
            form.submit();
        }
    }
};

// Función para encriptar usando Web Crypto API (más seguro)
async function encryptData(data) {
    try {
        // Generar una clave aleatoria para esta sesión
        const key = await crypto.subtle.generateKey(
            { name: 'AES-GCM', length: 256 },
            true,
            ['encrypt']
        );

        // Generar IV aleatorio
        const iv = crypto.getRandomValues(new Uint8Array(12));

        // Convertir datos a bytes
        const encoder = new TextEncoder();
        const dataBytes = encoder.encode(data);

        // Encriptar
        const encrypted = await crypto.subtle.encrypt(
            { name: 'AES-GCM', iv: iv },
            key,
            dataBytes
        );

        // Combinar IV + datos encriptados y codificar en base64
        const combined = new Uint8Array(iv.length + encrypted.byteLength);
        combined.set(iv);
        combined.set(new Uint8Array(encrypted), iv.length);

        return btoa(String.fromCharCode.apply(null, combined));
    } catch (error) {
        // Fallback a base64 simple si Web Crypto API no está disponible
        return btoa(data);
    }
}

// Export para uso externo
window.AuthJS = {
    togglePassword: window.togglePassword,
    validateForm: window.validateAuthForm,
    clearErrors: window.clearAuthErrors,
    submitForm: window.submitAuthForm,
    encryptData: encryptData
};
