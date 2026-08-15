/**
 * Home Page JavaScript
 * Manejo de funcionalidades específicas de la página principal
 */

document.addEventListener('DOMContentLoaded', function() {
    // Ocultar skeleton y mostrar contenido cuando carga
    const skeletonElements = document.querySelectorAll('.skeleton-loader');
    const contentElements = document.querySelectorAll('.category-card, .featured-products');

    // Simular tiempo de carga
    setTimeout(() => {
        skeletonElements.forEach(el => el.style.display = 'none');
        contentElements.forEach(el => el.style.opacity = '1');
    }, 500);

    // Animación de entrada para tarjetas
    const cards = document.querySelectorAll('.category-card, .product-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Lazy loading para imágenes del home
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const src = img.dataset.src;
                // Solo asignar si data-src es válido
                if (src && src !== '' && src !== 'undefined') {
                    img.src = src;
                }
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => {
        const src = img.dataset.src;
        // Solo observar si tiene data-src válido
        if (src && src !== '' && src !== 'undefined') {
            imageObserver.observe(img);
        }
    });

    // Smooth scroll para botones "Ver más"
    const viewMoreButtons = document.querySelectorAll('[data-scroll-to]');
    viewMoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.scrollTo;
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Carousel de productos destacados (si existe)
    const carousel = document.querySelector('.featured-carousel');
    if (carousel) {
        initFeaturedCarousel(carousel);
    }
});

/**
 * Inicializar carousel de productos destacados
 */
function initFeaturedCarousel(carousel) {
    const items = carousel.querySelectorAll('.carousel-item');
    const prevBtn = carousel.querySelector('.carousel-prev');
    const nextBtn = carousel.querySelector('.carousel-next');
    const indicators = carousel.querySelectorAll('.carousel-indicator');

    let currentIndex = 0;
    const totalItems = items.length;

    function goToSlide(index) {
        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });

        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });

        currentIndex = index;
    }

    function nextSlide() {
        const nextIndex = (currentIndex + 1) % totalItems;
        goToSlide(nextIndex);
    }

    function prevSlide() {
        const prevIndex = (currentIndex - 1 + totalItems) % totalItems;
        goToSlide(prevIndex);
    }

    // Event listeners
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => goToSlide(index));
    });

    // Auto-play
    setInterval(nextSlide, 5000);
}

/**
 * Animación de números (para estadísticas si existen)
 */
function animateNumbers() {
    const numbers = document.querySelectorAll('[data-animate-number]');

    numbers.forEach(number => {
        const target = parseInt(number.dataset.animateNumber);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            number.textContent = Math.floor(current).toLocaleString();
        }, 16);
    });
}

// Exportar funciones para uso global
window.HomeJS = {
    animateNumbers,
    initFeaturedCarousel
};

// Ocultar skeleton y mostrar categorías cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const skeleton = document.getElementById('categoriesSkeleton');
        const grid = document.getElementById('categoriesGrid');

        if (skeleton && grid) {
            skeleton.style.display = 'none';
            grid.classList.remove('hidden');
        }
    }, 800); // Delay para simular carga inicial

    // Carrusel de testimonios (infinito)
    const track = document.getElementById('testimonialTrack');
    const prevBtn = document.getElementById('testimonialPrev');
    const nextBtn = document.getElementById('testimonialNext');

    if (track && prevBtn && nextBtn) {
        const realSlides = Array.from(track.querySelectorAll('.testimonial-slide'));
        if (realSlides.length === 0) return;

        function getItemsVisible() {
            if (window.innerWidth >= 1024) return 3;
            if (window.innerWidth >= 768) return 2;
            return 1;
        }

        // Construir carrusel infinito clonando slides
        let itemsVisible = getItemsVisible();
        function buildInfiniteTrack() {
            track.innerHTML = '';
            // Clonar últimos N al inicio
            realSlides.slice(-itemsVisible).forEach(slide => {
                const clone = slide.cloneNode(true);
                clone.dataset.clone = 'start';
                track.appendChild(clone);
            });
            // Slides reales
            realSlides.forEach(slide => track.appendChild(slide.cloneNode(true)));
            // Clonar primeros N al final
            realSlides.slice(0, itemsVisible).forEach(slide => {
                const clone = slide.cloneNode(true);
                clone.dataset.clone = 'end';
                track.appendChild(clone);
            });
        }
        buildInfiniteTrack();

        let currentIndex = itemsVisible;
        let isTransitioning = false;

        function getSlideWidth() {
            return 100 / itemsVisible;
        }

        function moveTo(index, animate = true) {
            if (animate) {
                track.style.transition = 'transform 0.5s ease-in-out';
            } else {
                track.style.transition = 'none';
            }
            const slideWidth = getSlideWidth();
            track.style.transform = `translateX(-${index * slideWidth}%)`;
        }

        function handleNext() {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex++;
            moveTo(currentIndex);
        }

        function handlePrev() {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex--;
            moveTo(currentIndex);
        }

        function onTransitionEnd() {
            const totalSlides = track.children.length;
            // Si estamos en los clones del final, saltar al inicio real
            if (currentIndex >= totalSlides - itemsVisible) {
                currentIndex = itemsVisible;
                moveTo(currentIndex, false);
            }
            // Si estamos en los clones del inicio, saltar al final real
            if (currentIndex < itemsVisible) {
                currentIndex = totalSlides - itemsVisible - itemsVisible;
                moveTo(currentIndex, false);
            }
            setTimeout(() => { isTransitioning = false; }, 20);
        }

        track.addEventListener('transitionend', onTransitionEnd);
        track.addEventListener('webkitTransitionEnd', onTransitionEnd);

        nextBtn.addEventListener('click', handleNext);
        prevBtn.addEventListener('click', handlePrev);

        function onResize() {
            const newItemsVisible = getItemsVisible();
            if (newItemsVisible !== itemsVisible) {
                itemsVisible = newItemsVisible;
                buildInfiniteTrack();
                currentIndex = itemsVisible;
                moveTo(currentIndex, false);
            }
        }

        window.addEventListener('resize', onResize);
        moveTo(currentIndex, false);
    }
});
