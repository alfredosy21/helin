/**
 * Helin Toast System
 * Lightweight, accessible toast notifications.
 * Usage: Toast.show({ type: 'success'|'error'|'info', message: '...' })
 */
const Toast = (() => {
    let container = null;

    const ICONS = {
        success: '<i class="fas fa-check-circle text-emerald-500 text-lg flex-shrink-0"></i>',
        error:   '<i class="fas fa-times-circle text-red-500 text-lg flex-shrink-0"></i>',
        info:    '<i class="fas fa-info-circle text-turquesa text-lg flex-shrink-0"></i>',
        cart:    '<i class="fas fa-shopping-cart text-turquesa text-lg flex-shrink-0"></i>',
    };

    function getContainer() {
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.setAttribute('aria-live', 'polite');
            container.setAttribute('aria-atomic', 'false');
            container.style.cssText = [
                'position:fixed',
                'bottom:24px',
                'right:24px',
                'z-index:9999',
                'display:flex',
                'flex-direction:column',
                'gap:10px',
                'pointer-events:none',
            ].join(';');
            document.body.appendChild(container);
        }
        return container;
    }

    /**
     * Show a toast notification.
     * @param {object} opts
     * @param {string} opts.message
     * @param {'success'|'error'|'info'|'cart'} [opts.type='info']
     * @param {number} [opts.duration=3500]
     */
    function show({ message, type = 'info', duration = 3500 }) {
        const c    = getContainer();
        const icon = ICONS[type] || ICONS.info;

        const toast = document.createElement('div');
        toast.setAttribute('role', 'alert');
        toast.style.cssText = [
            'pointer-events:auto',
            'display:flex',
            'align-items:center',
            'gap:12px',
            'background:#fff',
            'border:1px solid #e8f0f0',
            'border-radius:14px',
            'padding:14px 18px',
            'box-shadow:0 8px 30px rgba(15,47,67,.13)',
            'max-width:340px',
            'min-width:240px',
            'opacity:0',
            'transform:translateX(30px)',
            'transition:opacity .25s ease, transform .25s ease',
        ].join(';');

        toast.innerHTML = `
            ${icon}
            <span style="font-size:13px;color:#1a3040;line-height:1.4;flex:1;">${message}</span>
            <button aria-label="Cerrar" style="background:none;border:none;cursor:pointer;color:#8fa8b0;padding:0;font-size:16px;line-height:1;">&times;</button>
        `;

        toast.querySelector('button').addEventListener('click', () => dismiss(toast));

        c.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.style.opacity  = '1';
                toast.style.transform = 'translateX(0)';
            });
        });

        const timer = setTimeout(() => dismiss(toast), duration);
        toast._timer = timer;

        // Pause on hover
        toast.addEventListener('mouseenter', () => clearTimeout(toast._timer));
        toast.addEventListener('mouseleave', () => {
            toast._timer = setTimeout(() => dismiss(toast), 1500);
        });
    }

    function dismiss(toast) {
        clearTimeout(toast._timer);
        toast.style.opacity   = '0';
        toast.style.transform = 'translateX(30px)';
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }

    return { show };
})();
