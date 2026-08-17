/**
 * CmsToast - Sistema de notificaciones toast del CMS
 * Mismo estilo visual que el toast del carrito de la web pública.
 * Uso: CmsToast.show({ message: '...', type: 'success'|'error'|'warning'|'info' })
 */
const CmsToast = (() => {
    let container = null;

    const ICONS = {
        success: '<svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        error:   '<svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        warning: '<svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
        info:    '<svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };

    function getContainer() {
        if (!container) {
            container = document.createElement('div');
            container.id = 'cms-toast-container';
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

    function show({ message, type = 'info', duration = 4000 }) {
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

// Expose globally for use in Blade scripts
window.CmsToast = CmsToast;
