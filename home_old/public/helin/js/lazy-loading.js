/**
 * Lazy Loading para imágenes - Helin Medical Solutions
 * Implementación moderna y eficiente con Intersection Observer
 */

class HelinLazyLoading {
    constructor() {
        this.init();
    }

    init() {
        // Configuración del Intersection Observer
        this.observerOptions = {
            root: null, // Usa el viewport
            rootMargin: '50px 0px', // Carga 50px antes de que sea visible
            threshold: 0.1 // Carga cuando 10% del elemento es visible
        };

        // Crear el observer
        this.imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Verificar que la imagen aún tenga data-src antes de procesar
                if (entry.isIntersecting && entry.target.dataset.src) {
                    this.loadImage(entry.target);
                }
            });
        }, this.observerOptions);

        // Iniciar observación
        this.observeImages();
        
        // Manejar imágenes ya visibles
        this.handleAlreadyVisible();
    }

    observeImages() {
        // Buscar todas las imágenes con data-src
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            // Solo observar si tiene data-src válido
            const src = img.dataset.src;
            if (src && src !== '' && src !== 'undefined') {
                this.imageObserver.observe(img);
            }
        });
    }

    handleAlreadyVisible() {
        // Para imágenes que ya están visibles en el primer load
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            const src = img.dataset.src;
            if (src && src !== '' && src !== 'undefined') {
                const rect = img.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    this.loadImage(img);
                }
            }
        });
    }

    loadImage(img) {
        const src = img.dataset.src;
        const currentSrc = img.src;

        // Si no hay data-src válido, no hacer nada
        if (!src || src === '' || src === 'undefined') {
            this.imageObserver.unobserve(img);
            return;
        }

        // Si el src actual es igual al data-src, ya está cargado
        if (src === currentSrc) {
            this.imageObserver.unobserve(img);
            return;
        }

        // Marcar la imagen como cargando para evitar procesamiento duplicado
        if (img.classList.contains('lazy-loading')) {
            return;
        }
        img.classList.add('lazy-loading');

        // Aplicar efecto de fade-in
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease-in-out';

        // Crear nueva imagen para precargar
        const newImg = new Image();

        newImg.onload = () => {
            // Cuando la imagen se carga, actualizar el src
            img.src = src;
            img.removeAttribute('data-src');
            img.classList.remove('lazy-loading');

            // Remover del observer
            this.imageObserver.unobserve(img);

            // Aplicar fade-in
            setTimeout(() => {
                img.style.opacity = '1';
            }, 50);
        };

        newImg.onerror = () => {
            // En caso de error, usar el fallback o mantener el src actual
            const fallback = img.dataset.fallback;
            if (fallback && fallback !== 'undefined' && fallback !== '') {
                img.src = fallback;
            }
            // Siempre remover data-src para evitar reintentos
            img.removeAttribute('data-src');
            img.classList.remove('lazy-loading');
            this.imageObserver.unobserve(img);
            img.style.opacity = '1';
        };

        // Iniciar carga
        newImg.src = src;
    }

    // Método para actualizar dinámicamente (para AJAX)
    update() {
        this.observeImages();
        this.handleAlreadyVisible();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.helinLazyLoading = new HelinLazyLoading();
});

// Para actualizaciones después de AJAX
window.updateLazyLoading = () => {
    if (window.helinLazyLoading) {
        window.helinLazyLoading.update();
    }
};
