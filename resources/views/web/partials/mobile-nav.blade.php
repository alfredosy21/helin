<!-- Mobile Navigation Drawer -->
<div id="mobile-nav" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" id="mobile-nav-overlay"></div>
    <div class="absolute left-0 top-0 h-full w-[280px] sm:w-80 bg-white shadow-xl transform -translate-x-full transition-transform duration-300" id="mobile-nav-panel">
        <div class="p-4 border-b flex items-center justify-between sticky top-0 bg-white z-10">
            <span class="text-xl font-bold text-turquesa">helin.</span>
            <button id="close-mobile-nav" class="p-2 hover:bg-helin-soft rounded-lg">
                <i class="fas fa-times text-helin-text"></i>
            </button>
        </div>
        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100%-70px)]">
            <a href="{{ route('home') }}" class="flex items-center gap-3 py-3 px-4 text-helin-heading font-semibold hover:bg-helin-soft rounded-lg">
                <i class="fas fa-home text-turquesa"></i> Inicio
            </a>
            <a href="{{ route('catalogo') }}" class="flex items-center gap-3 py-3 px-4 text-helin-heading font-semibold hover:bg-helin-soft rounded-lg">
                <i class="fas fa-th-large text-turquesa"></i> Todos los Productos
            </a>
            <div class="border-t border-helin-border my-3"></div>
            <p class="px-4 py-2 text-xs text-helin-text uppercase font-semibold tracking-wide">Categorías</p>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-tooth text-helin-text w-5"></i> Implantología</span>
                <i class="fas fa-chevron-right text-xs text-helin-text"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-bone text-helin-text w-5"></i> Regeneración Ósea</span>
                <i class="fas fa-chevron-right text-xs text-helin-text"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-layer-group text-helin-text w-5"></i> Osteosíntesis</span>
                <i class="fas fa-chevron-right text-xs text-helin-text"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-smile text-helin-text w-5"></i> Cuidado Bucal</span>
                <i class="fas fa-chevron-right text-xs text-helin-text"></i>
            </a>
            <a href="#" class="flex items-center justify-between py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <span class="flex items-center gap-3"><i class="fas fa-scissors text-helin-text w-5"></i> Instrumentos</span>
                <i class="fas fa-chevron-right text-xs text-helin-text"></i>
            </a>
            <div class="border-t border-helin-border my-3"></div>
            <a href="https://wa.me/584127398580" target="_blank" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fab fa-whatsapp text-green-500"></i> Escríbenos por WhatsApp
            </a>
            <a href="{{ route('solicitud') }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-file-contract text-turquesa"></i> Solicitud Comercial
            </a>
        </nav>
    </div>
</div>
