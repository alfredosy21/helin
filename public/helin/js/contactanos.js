/**
 * Helin Contact Form
 * Handles AJAX submission, client-side validation feedback,
 * and toast notifications using the global Toast module.
 */
document.addEventListener('DOMContentLoaded', () => {
    const form      = document.getElementById('contact-form');
    const submitBtn = document.getElementById('contact-submit');
    if (!form) return;

    const fields = {
        nombre:          form.querySelector('[name="nombre"]'),
        email:           form.querySelector('[name="email"]'),
        asunto:          form.querySelector('[name="asunto"]'),
        mensaje:         form.querySelector('[name="mensaje"]'),
        telefono:        form.querySelector('[name="telefono"]'),
        privacy_accepted: form.querySelector('[name="privacy_accepted"]'),
    };

    // ─── Inline error helpers ─────────────────────────────────────────────────

    function showFieldError(name, message) {
        const input = fields[name];
        if (!input) return;
        input.classList.add('field-error');
        let hint = input.parentElement.querySelector('.field-hint');
        if (!hint) {
            hint = document.createElement('span');
            hint.className = 'field-hint';
            input.after(hint);
        }
        hint.textContent = message;
    }

    function clearFieldError(name) {
        const input = fields[name];
        if (!input) return;
        input.classList.remove('field-error');
        input.parentElement.querySelector('.field-hint')?.remove();
    }

    function clearAllErrors() {
        Object.keys(fields).forEach(clearFieldError);
    }

    // Clear error on input
    Object.entries(fields).forEach(([name, el]) => {
        if (!el) return;
        el.addEventListener('input', () => clearFieldError(name));
        el.addEventListener('change', () => clearFieldError(name));
    });

    // ─── Submit ───────────────────────────────────────────────────────────────

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearAllErrors();

        if (fields.privacy_accepted && !fields.privacy_accepted.checked) {
            showFieldError('privacy_accepted', 'Debes aceptar la Política de Privacidad para continuar.');
            return;
        }

        setLoading(true);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const body      = new FormData(form);

        try {
            const response = await fetch('/contactanos/send', {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN':     csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept':           'application/json',
                },
                body,
            });

            const data = await response.json();

            if (response.status === 422) {
                // Server validation errors — show inline
                Object.entries(data.errors || {}).forEach(([field, messages]) => {
                    showFieldError(field, messages[0]);
                });
                Toast.show({ type: 'error', message: 'Por favor corrige los campos marcados.' });
                return;
            }

            if (!response.ok || !data.success) {
                Toast.show({ type: 'error', message: data.message || 'Error al enviar el mensaje.' });
                return;
            }

            // Success
            Toast.show({ type: 'success', message: data.message, duration: 5000 });
            form.reset();

        } catch {
            Toast.show({ type: 'error', message: 'Error de conexión. Inténtalo de nuevo.' });
        } finally {
            setLoading(false);
        }
    });

    // ─── Loading state ────────────────────────────────────────────────────────

    function setLoading(loading) {
        if (!submitBtn) return;
        submitBtn.disabled = loading;
        submitBtn.textContent = loading ? '⟳ Enviando...' : '➤ Contactar a Helin';
        submitBtn.style.opacity = loading ? '0.7' : '1';
    }
});
