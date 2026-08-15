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
