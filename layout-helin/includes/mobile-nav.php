<?php
/**
 * Mobile Navigation Drawer
 * Menú lateral deslizable para dispositivos móviles
 */
?>
<!-- Mobile Navigation Drawer -->
<div id="mobile-nav" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" id="mobile-nav-overlay"></div>
    <div class="absolute left-0 top-0 h-full w-[280px] sm:w-80 bg-white shadow-xl transform -translate-x-full transition-transform duration-300" id="mobile-nav-panel">
        <div class="p-4 border-b flex items-center justify-between sticky top-0 bg-white z-10">
            <span class="text-xl font-bold text-turquesa">helin.</span>
            <button id="close-mobile-nav" class="p-2 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-times text-gray-600"></i>
            </button>
        </div>
        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100%-70px)]">
            <a href="index.php" class="flex items-center gap-3 py-3 px-4 text-gray-800 font-semibold hover:bg-gray-50 rounded-lg">
                <i class="fas fa-home text-turquesa"></i> Inicio
            </a>
            <a href="catalogo.php" class="flex items-center gap-3 py-3 px-4 text-gray-800 font-semibold hover:bg-gray-50 rounded-lg">
                <i class="fas fa-th-large text-turquesa"></i> Todos los Productos
            </a>
            <div class="border-t my-3"></div>
            <p class="px-4 py-2 text-xs text-gray-400 uppercase font-semibold tracking-wide">Categorías</p>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-tooth text-gray-400 w-5"></i> Implantología</span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-bone text-gray-400 w-5"></i> Regeneración Ósea</span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-layer-group text-gray-400 w-5"></i> Osteosíntesis</span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-smile text-gray-400 w-5"></i> Cuidado Bucal</span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-scissors text-gray-400 w-5"></i> Instrumentos</span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </a>
            <div class="border-t my-3"></div>
            <a href="https://wa.me/584127398580" target="_blank" class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fab fa-whatsapp text-green-500"></i> Escríbenos por WhatsApp
            </a>
            <a href="solicitud.php" class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-file-contract text-turquesa"></i> Solicitud Comercial
            </a>
        </nav>
    </div>
</div>
