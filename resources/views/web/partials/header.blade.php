<!-- Barra Informativa Superior -->
<div class="bg-turquesa text-white text-xs sm:text-sm border-b border-white/20 hidden sm:block">
    <div class="container mx-auto px-4 py-2 flex flex-wrap items-center justify-center gap-y-1.5 gap-x-3 sm:gap-4 text-center">
        @php
            $settings = \App\Models\Settings::getSettings();
        @endphp
        <span class="leading-snug">{{ $settings->tagline }}</span>
        <a href="{{ route('catalogo') }}" class="border border-white/30 rounded-full px-3 sm:px-4 py-1 hover:bg-white/10 transition text-xs sm:text-sm whitespace-nowrap flex-shrink-0">VER PRODUCTOS</a>
    </div>
</div>

<!-- Barra de Navegación Principal -->
<header class="bg-turquesa sticky top-0 z-50">
    <div class="w-full mx-auto px-3 sm:px-4 lg:max-w-[1200px] lg:px-10 py-2 sm:py-4">
        <div class="flex items-center gap-2 sm:gap-6">
            @php
                    $settings = \App\Models\Settings::getSettings();
                @endphp
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white flex-shrink-0">
                    <img src="{{ asset('images/logo_blanco_helin.png') }}" alt="Helin" class="h-12 sm:h-16 w-auto">
                </a>

            <!-- Buscador - Solo en tablet/desktop -->
            <div class="hidden sm:block flex-1 max-w-4xl lg:max-w-2xl mx-auto">
                <div class="header-search-wrapper">
                    <div class="bg-white rounded-full shadow-lg border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-1 flex items-center px-3 py-3">
                                <i class="fas fa-search text-turquesa mr-2 text-sm"></i>
                                <input type="text" id="header-search-input" placeholder="¿Qué producto estás buscando?" autocomplete="off" class="flex-1 outline-none text-helin-heading text-sm w-full bg-transparent">
                            </div>
                            <div class="border-l flex items-center px-3 hidden md:flex min-w-[140px] py-3">
                                @php
                                    $categories = \App\Models\Category::active()->ordered()->get();
                                @endphp
                                <select name="category" id="header-search-category" class="bg-transparent text-helin-heading text-xs outline-none cursor-pointer w-full font-semibold">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" id="header-search-submit" class="bg-turquesa hover:bg-turquesa-dark text-white w-10 h-10 flex items-center justify-center transition-colors flex-shrink-0 rounded-full mx-1">
                                <i class="fas fa-search text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div id="header-search-dropdown" class="hidden mt-2"></div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex items-center gap-2 sm:gap-3 ml-auto lg:ml-0 lg:flex-shrink-0">
                @php
                    $settings = \App\Models\Settings::getSettings();
                @endphp
                <!-- WhatsApp - solo desktop -->
                <a href="https://wa.me/584244669150?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="hidden lg:flex items-center gap-2 bg-turquesa/60 text-white px-4 h-11 rounded-full hover:bg-[#123F4A] transition text-sm">
                    <i class="fab fa-whatsapp text-2xl"></i>
                    <span>Escríbenos</span>
                </a>
                <!-- Carrito -->
                <a href="{{ route('carrito') }}" class="flex items-center gap-1 sm:gap-2 text-white hover:text-[#123F4A] transition p-1 sm:p-0">
                    <div class="relative">
                        <i class="fas fa-shopping-cart text-lg sm:text-xl"></i>
                        <span id="cart-badge" class="absolute -top-1.5 -right-1.5 sm:-top-2 sm:-right-2 bg-turquesa text-white text-[10px] sm:text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center border border-white" style="display:none;">0</span>
                    </div>
                    <span class="hidden sm:inline text-sm">Ver carrito</span>
                </a>
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-white p-2 hover:bg-white/10 rounded-lg transition">
                    <i class="fas fa-bars text-lg sm:text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Línea divisora mobile -->
        <hr class="sm:hidden w-full" style="border:none;border-top:1px solid rgba(255,255,255,0.15);margin-top:0.5rem;">

        <!-- Buscador Móvil - Siempre visible -->
        <div id="mobile-search" class="sm:hidden mt-2 pb-1">
            <div class="mobile-search-wrapper">
                <div class="bg-white rounded-full p-1 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-1 flex items-center px-3">
                            <i class="fas fa-search text-helin-text mr-2 text-sm"></i>
                            <input type="text" id="mobile-search-input" placeholder="Buscar productos..." autocomplete="off" class="flex-1 outline-none text-helin-heading text-sm w-full">
                        </div>
                        <button type="button" id="mobile-search-submit" class="bg-turquesa hover:bg-turquesa-dark text-white w-10 h-10 flex items-center justify-center transition-colors flex-shrink-0 rounded-full mx-0.5">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </div>
                </div>
                <div id="mobile-search-dropdown" class="hidden"></div>
            </div>
        </div>
    </div>

    <!-- Barra de Menú de Categorías con Mega Menú -->
    <div class="hidden lg:block bg-white border-t border-helin-border relative">
        <!-- Menú Desktop -->
        <div class="py-3">
            <div class="flex items-center w-full max-w-[1200px] mx-auto px-10">
                <!-- Productos - Posición fija izquierda -->
                <nav class="flex items-center text-sm">
                    <!-- Productos con Mega Menú -->
                    <div class="relative group">
                        <a href="{{ route('catalogo') }}" class="text-helin-heading hover:text-turquesa flex items-center gap-2 font-bold border-b-2 border-transparent py-1">
                            <i class="fas fa-bars"></i>
                            Productos
                        </a>
                        <!-- Mega Menú -->
                        <div class="absolute top-full left-0 w-[1200px] bg-white shadow-[0_10px_20px_rgba(0,0,0,0.1)] rounded-b-lg py-6 hidden group-hover:block z-50" style="box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                            <div class="grid grid-cols-6 gap-0">
                                <!-- Columna 1: Implantología -->
                                <div class="px-4 py-4 border-r border-helin-border cursor-pointer">
                                    <div class="border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-tooth text-sm" style="color: #6BC2C3;"></i>
                                            <a href="{{ route('catalogo', ['category' => 'implantologia']) }}" class="text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold">Implantología</a>
                                        </div>
                                        <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                        <div class="space-y-4">
                                            <div>
                                                <p class="text-turquesa font-semibold text-[10px] mb-2 uppercase tracking-wide">AB</p>
                                                <ul class="space-y-1">
                                                    <li><a href="{{ route('catalogo', ['category' => 'implantologia']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Implantes</a></li>
                                                    <li><a href="{{ route('catalogo', ['category' => 'aditamentos']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Aditamentos</a></li>
                                                    <li><a href="{{ route('catalogo', ['category' => 'kits-quirurgicos']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Kits</a></li>
                                                </ul>
                                            </div>
                                            <div>
                                                <p class="text-turquesa font-semibold text-[10px] mb-2 uppercase tracking-wide">GDT</p>
                                                <ul class="space-y-1">
                                                    <li><a href="{{ route('catalogo', ['category' => 'implantologia']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Implantes</a></li>
                                                    <li><a href="{{ route('catalogo', ['category' => 'aditamentos']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Aditamentos</a></li>
                                                    <li><a href="{{ route('catalogo', ['category' => 'kits-quirurgicos']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Kits</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna 2: Regeneración Ósea Guiada -->
                                <div class="px-4 py-4 border-r border-helin-border cursor-pointer">
                                    <div class="border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-bone text-turquesa text-sm"></i>
                                            <a href="{{ route('catalogo', ['category' => 'regeneracion-guiada-bucal']) }}" class="text-helin-text text-[12px] uppercase tracking-wide leading-tight hover:text-turquesa transition-colors font-semibold">Regeneración Ósea Guiada</a>
                                        </div>
                                        <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="{{ route('catalogo', ['tag' => 'biomaterial']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Biomateriales</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'regeneracion-guiada-bucal']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Regeneración Guiada Bucal</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'suturas']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Suturas</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Columna 3: Osteosíntesis -->
                                <div class="px-4 py-4 border-r border-helin-border cursor-pointer">
                                    <div class="border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-toolbox text-sm" style="color: #6BC2C3;"></i>
                                            <a href="{{ route('catalogo', ['category' => 'osteosintesis']) }}" class="text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold">Osteosíntesis</a>
                                        </div>
                                        <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="{{ route('catalogo', ['category' => 'placas-osteosintesis']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Placas</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'tornillos-osteosintesis']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Tornillos</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'cajetin-osteosintesis']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Cajetín</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Columna 4: Cuidado Bucal -->
                                <div class="px-4 py-4 border-r border-helin-border cursor-pointer">
                                    <div class="border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-face-smile text-sm" style="color: #6BC2C3;"></i>
                                            <a href="{{ route('catalogo', ['category' => 'cuidados-especiales']) }}" class="text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold">Cuidado Bucal</a>
                                        </div>
                                        <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="{{ route('catalogo', ['category' => 'cuidados-especiales']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Cuidados Especiales</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'cuidados-diarios']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Cuidados Diarios</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Columna 5: Instrumentos y Equipos -->
                                <div class="px-4 py-4 cursor-pointer">
                                    <div class="border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-tools text-sm" style="color: #6BC2C3;"></i>
                                            <a href="{{ route('catalogo', ['category' => 'instrumentos']) }}" class="text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold">Instrumentos</a>
                                        </div>
                                        <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                        <ul class="space-y-2 mb-6">
                                            <li><a href="{{ route('catalogo', ['category' => 'tijeras']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Tijeras</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'pinzas']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Pinzas</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'separadores']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Separadores</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'cinceles']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Cinceles</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'periostotomos']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Periostótomos</a></li>
                                        </ul>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-gears text-sm" style="color: #6BC2C3;"></i>
                                            <a href="{{ route('catalogo', ['category' => 'equipos-odontologicos']) }}" class="text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold">Equipos</a>
                                        </div>
                                        <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="{{ route('catalogo', ['category' => 'equipos-odontologicos']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Equipos odontológicos</a></li>
                                        </ul>
                                        <!-- Subdivisión de Equipos -->
                                        <div class="ml-4 pl-2 border-l border-helin-border/30">
                                            <ul class="space-y-2">
                                                <li><a href="{{ route('catalogo', ['category' => 'piezas-de-mano']) }}" class="text-helin-text text-[12px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">→</span> Piezas de mano</a></li>
                                                <li><a href="{{ route('catalogo', ['category' => 'motores-odontologicos']) }}" class="text-helin-text text-[12px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">→</span> Motores</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna 6: Planificación Digital -->
                                <div class="px-4 py-4 cursor-pointer">
                                    <div class="border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-cube text-sm" style="color: #6BC2C3;"></i>
                                            <a href="{{ route('catalogo', ['category' => 'planificacion-digital']) }}" class="text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold">Planificación Digital</a>
                                        </div>
                                        <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="{{ route('catalogo', ['category' => 'planificacion-digital']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Planificación Digital</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'impresion-3d']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Impresión 3D</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'escaneo-intraoral']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> Escaneo Intraoral</a></li>
                                            <li><a href="{{ route('catalogo', ['category' => 'pd-completa']) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> PD Completa</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $currentRoute = request()->route()->getName();
                        $currentCategory = request()->route('category') ?? request('category');
                        $currentTag = request('tag');
                    @endphp
                </nav>
                <!-- Inicio → Ofertas - Bloque central bajo el buscador -->
                <div class="flex-1 flex items-center justify-center gap-7 text-sm">
                    <a href="{{ route('home') }}" class="text-helin-heading hover:text-turquesa font-bold whitespace-nowrap border-b-2 border-transparent py-1 {{ $currentRoute === 'home' ? 'text-turquesa border-turquesa' : '' }}">Inicio</a>
                    <a href="{{ route('catalogo', ['category' => 'implantologia']) }}" class="text-helin-heading hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap border-b-2 border-transparent py-1 {{ $currentRoute === 'catalogo' && $currentCategory === 'implantologia' ? 'text-turquesa border-turquesa' : '' }}">Implantología <span class="text-xs">+</span></a>
                    <a href="{{ route('catalogo', ['category' => 'osteosintesis']) }}" class="text-helin-heading hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap border-b-2 border-transparent py-1 {{ $currentRoute === 'catalogo' && $currentCategory === 'osteosintesis' ? 'text-turquesa border-turquesa' : '' }}">Osteosíntesis <span class="text-xs">+</span></a>
                    <a href="{{ route('catalogo', ['category' => 'instrumentos']) }}" class="text-helin-heading hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap border-b-2 border-transparent py-1 {{ $currentRoute === 'catalogo' && $currentCategory === 'instrumentos' ? 'text-turquesa border-turquesa' : '' }}">Instrumentos <span class="text-xs">+</span></a>
                    <a href="{{ route('catalogo', ['category' => 'planificacion-digital']) }}" class="text-helin-heading hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap border-b-2 border-transparent py-1 {{ $currentRoute === 'catalogo' && $currentCategory === 'planificacion-digital' ? 'text-turquesa border-turquesa' : '' }}">Planificación digital <span class="text-xs">+</span></a>
                    <a href="{{ route('catalogo', ['tag' => 'on_sale']) }}" class="text-helin-heading hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap border-b-2 border-transparent py-1 {{ $currentRoute === 'catalogo' && $currentTag === 'on_sale' ? 'text-turquesa border-turquesa' : '' }}">Ofertas</a>
                </div>
                <!-- Recursos Clínicos - Posición fija derecha -->
                <div class="flex items-center gap-4 flex-shrink-0">
                    <a href="{{ route('recursos-clinicos') }}" class="bg-turquesa hover:bg-turquesa-dark text-white text-sm px-5 py-2.5 rounded-full flex items-center gap-2 transition-colors">
                        Recursos Clínicos
                        <i class="fas fa-cloud-download-alt"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>
