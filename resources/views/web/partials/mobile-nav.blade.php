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
                <i class="fas fa-home text-turquesa w-5 text-center"></i> Inicio
            </a>
            <a href="{{ route('catalogo') }}" class="flex items-center gap-3 py-3 px-4 text-helin-heading font-semibold hover:bg-helin-soft rounded-lg">
                <i class="fas fa-th-large text-turquesa w-5 text-center"></i> Todos los productos
            </a>
            <div class="border-t border-helin-border my-3"></div>
            <a href="{{ route('catalogo', ['category' => 'implantologia']) }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-tooth text-turquesa w-5 text-center"></i> Implantología
            </a>
            <a href="{{ route('catalogo', ['category' => 'osteosintesis']) }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-toolbox text-turquesa w-5 text-center"></i> Osteosíntesis
            </a>
            <a href="{{ route('catalogo', ['category' => 'instrumentos']) }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-tools text-turquesa w-5 text-center"></i> Instrumentos
            </a>
            <a href="{{ route('catalogo', ['category' => 'planificacion-digital']) }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-cube text-turquesa w-5 text-center"></i> Planificación Digital
            </a>
            <a href="{{ route('catalogo', ['tag' => 'on_sale']) }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-tags text-turquesa w-5 text-center"></i> Ofertas
            </a>
            <a href="{{ route('recursos-clinicos') }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-cloud-download-alt text-turquesa w-5 text-center"></i> Recursos Clínicos
            </a>
            <div class="border-t border-helin-border my-3"></div>
            <a href="{{ route('carrito') }}" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fas fa-shopping-cart text-turquesa w-5 text-center"></i> Ir a carrito
            </a>
            <a href="https://wa.me/584244669150?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="flex items-center gap-3 py-3 px-4 text-helin-text hover:bg-helin-soft rounded-lg">
                <i class="fab fa-whatsapp text-green-500 w-5 text-center"></i> Escríbenos por WhatsApp
            </a>
        </nav>
    </div>
</div>
