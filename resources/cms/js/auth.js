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
document.addEventListener('DOMContentLoaded', function() {
    console.log('Auth JS initialized');
    initializeToastListeners();
    initializePasswordToggle();
});

// Toast notification system
function initializeToastListeners() {
    // Escuchar eventos de Toast desde Livewire
    document.addEventListener('livewire:init', () => {
        console.log('Livewire initialized, setting up toast listeners');

        Livewire.on('toast', ({ message, type }) => {
            console.log('Toast received:', { message, type });

            if(window.showToast) {
                window.showToast(message, type);
            } else if(window.Toastify) {
                // Usar Toastify si está disponible
                console.log('Using Toastify for notification');
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
                console.warn('No toast system available, using alert');
                alert(`${type.toUpperCase()}: ${message}`);
            }
        });
    });
}

// Password toggle functionality
function initializePasswordToggle() {
    // La función togglePassword ya está definida inline
    // pero podemos agregar mejoras aquí si es necesario
    console.log('Password toggle initialized');
}

// Password visibility toggle (función global para compatibilidad)
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.type = field.type === 'password' ? 'text' : 'password';
        console.log('Password toggled for field:', fieldId);

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
        console.error('Field not found:', fieldId);
    }
};

// Form validation enhancements
window.validateAuthForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) {
        console.error('Form not found:', formId);
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
        console.error('Encryption error:', error);
        // Fallback a base64 simple si Web Crypto API no está disponible
        return btoa(data);
    }
}

// Interceptar el envío del formulario de login
function initializeLoginEncryption() {
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('form[wire\\:submit]');
        if (loginForm) {
            loginForm.addEventListener('submit', async function(e) {
                if (!e.detail) {
                    // Prevenir envío normal
                    e.preventDefault();

                    const emailField = document.querySelector('input[name="email"]');
                    const passwordField = document.querySelector('input[name="password"]');

                    if (emailField && passwordField) {
                        // Combinar credenciales
                        const credentials = JSON.stringify({
                            email: emailField.value,
                            password: passwordField.value,
                            timestamp: Date.now()
                        });

                        // Encriptar credenciales
                        const encryptedCredentials = await encryptData(credentials);

                        // Agregar timestamp para evitar replay attacks
                        const timestamp = Date.now();

                        // Enviar con Livewire usando wire:model
                        window.livewire.find('app.http.controllers.cms.authenticated-session-controller')
                            .set('email', emailField.value)
                            .set('password', passwordField.value)
                            .set('encrypted_data', encryptedCredentials)
                            .set('login_timestamp', timestamp)
                            .call('login');
                    }
                }
            });
        }
    });
}

// Inicializar encriptación de login
initializeLoginEncryption();

// Export para uso externo
window.AuthJS = {
    togglePassword: window.togglePassword,
    validateForm: window.validateAuthForm,
    clearErrors: window.clearAuthErrors,
    submitForm: window.submitAuthForm,
    encryptData: encryptData
};
