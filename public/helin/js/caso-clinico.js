document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tabs .tab');
    const panels = document.querySelectorAll('.tab-panel');

    function activateTab(targetId) {
        tabs.forEach(function (tab) {
            tab.classList.toggle('active', tab.getAttribute('href') === '#' + targetId);
        });
        panels.forEach(function (panel) {
            panel.classList.toggle('active', panel.id === targetId);
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            activateTab(this.getAttribute('href').substring(1));
        });
    });

    if (window.location.hash) {
        activateTab(window.location.hash.substring(1));
    }

    // Indicador de scroll horizontal de los tabs (solo móvil/tablet)
    const nav = document.getElementById('tabsNav');
    const hint = document.getElementById('tabsScrollHint');

    if (nav && hint) {
        const checkScroll = function () {
            const maxScroll = nav.scrollWidth - nav.clientWidth;
            hint.classList.toggle('hidden', maxScroll <= 2 || nav.scrollLeft >= maxScroll - 4);
        };

        nav.addEventListener('scroll', checkScroll, { passive: true });
        window.addEventListener('resize', checkScroll);
        checkScroll();
    }
});

function copyPageLink(button) {
    navigator.clipboard.writeText(window.location.href).then(function () {
        const tooltip = button.querySelector('.tooltip');
        tooltip.classList.add('show');
        setTimeout(function () {
            tooltip.classList.remove('show');
        }, 2000);
    });
}
