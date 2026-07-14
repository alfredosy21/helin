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
                if (entry.isIntersecting) {
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
            this.imageObserver.observe(img);
        });
    }

    handleAlreadyVisible() {
        // Para imágenes que ya están visibles en el primer load
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            const rect = img.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                this.loadImage(img);
            }
        });
    }

    loadImage(img) {
        const src = img.dataset.src;
        if (!src) return;

        // Aplicar efecto de fade-in
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease-in-out';

        // Crear nueva imagen para precargar
        const newImg = new Image();
        
        newImg.onload = () => {
            // Cuando la imagen se carga, actualizar el src
            img.src = src;
            img.removeAttribute('data-src');
            
            // Remover del observer
            this.imageObserver.unobserve(img);
            
            // Aplicar fade-in
            setTimeout(() => {
                img.style.opacity = '1';
            }, 50);
        };

        newImg.onerror = () => {
            // Manejo de error - usar imagen por defecto
            img.src = img.dataset.fallback || '/images/placeholder-product.webp';
            img.removeAttribute('data-src');
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
