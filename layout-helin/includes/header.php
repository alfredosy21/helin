<?php
/**
 * Header Include - Navegación principal
 * Incluye: barra informativa, buscador, menú de categorías
 */
?>
<!-- Barra Informativa Superior -->
<div class="bg-turquesa text-white text-xs sm:text-sm border-b border-white/20">
    <div class="container mx-auto px-4 py-2 flex items-center justify-center gap-2 sm:gap-4">
        <span class="truncate">Todo para cirugía odontológica especializada</span>
        <a href="#" class="border border-white/30 rounded-full px-3 sm:px-4 py-1 hover:bg-white/10 transition text-xs sm:text-sm whitespace-nowrap">VER PRODUCTOS</a>
    </div>
</div>

<!-- Barra de Navegación Principal -->
<header class="bg-turquesa sticky top-0 z-50">
    <div class="container mx-auto px-3 sm:px-4 py-2 sm:py-4">
        <div class="flex items-center gap-2 sm:gap-6">
            <!-- Logo -->
            <a href="index.php" class="text-white text-xl sm:text-3xl font-bold lowercase tracking-tight flex-shrink-0">helin.</a>

            <!-- Buscador - Solo en tablet/desktop -->
            <div class="hidden sm:block flex-1 max-w-xl mx-auto">
                <div class="bg-white rounded-full p-1.5 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-1 flex items-center px-3">
                            <i class="fas fa-search text-gray-400 mr-2 text-sm"></i>
                            <input type="text" placeholder="¿Qué producto estás buscando?" class="flex-1 outline-none text-gray-700 text-sm w-full">
                        </div>
                        <div class="border-l flex items-center px-3 hidden md:flex min-w-[140px]">
                            <select class="bg-transparent text-gray-700 text-xs outline-none cursor-pointer w-full font-semibold">
                                <option>Todas las categorías</option>
                                <option>Cirugía</option>
                                <option>Periodoncia</option>
                                <option>Endodoncia</option>
                            </select>
                        </div>
                        <button class="bg-turquesa hover:bg-turquesa-dark text-white w-10 h-10 flex items-center justify-center transition-colors flex-shrink-0 rounded-full mx-1">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex items-center gap-2 sm:gap-3 ml-auto">
                <!-- WhatsApp - solo desktop -->
                <a href="https://wa.me/584127398580" target="_blank" class="hidden lg:flex items-center gap-2 bg-[#4DD4D1]/60 text-white px-4 h-11 rounded-full hover:bg-[#4DD4D1]/80 transition text-sm">
                    <i class="fab fa-whatsapp text-2xl"></i>
                    <span>Escríbenos</span>
                </a>
                <!-- Carrito -->
                <a href="carrito.php" class="flex items-center gap-1 sm:gap-2 text-white hover:text-turquesa-light transition p-1 sm:p-0">
                    <div class="relative">
                        <i class="fas fa-shopping-cart text-lg sm:text-xl"></i>
                        <span class="absolute -top-1.5 -right-1.5 sm:-top-2 sm:-right-2 bg-turquesa text-white text-[10px] sm:text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center border border-white">3</span>
                    </div>
                    <span class="hidden sm:inline text-sm">Ver carrito</span>
                </a>
                <!-- Mobile Search Button -->
                <button id="mobile-search-btn" class="sm:hidden text-white p-2 hover:bg-white/10 rounded-lg transition">
                    <i class="fas fa-search text-lg"></i>
                </button>
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-white p-2 hover:bg-white/10 rounded-lg transition">
                    <i class="fas fa-bars text-lg sm:text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Buscador Móvil - Expandible -->
        <div id="mobile-search" class="hidden sm:hidden mt-2 pb-1">
            <div class="bg-white rounded-full p-1 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-1 flex items-center px-3">
                        <i class="fas fa-search text-gray-400 mr-2 text-sm"></i>
                        <input type="text" placeholder="Buscar productos..." class="flex-1 outline-none text-gray-700 text-sm w-full">
                    </div>
                    <button class="bg-turquesa hover:bg-turquesa-dark text-white w-10 h-10 flex items-center justify-center transition-colors flex-shrink-0 rounded-full mx-0.5">
                        <i class="fas fa-search text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Menú de Categorías con Mega Menú -->
    <div class="bg-white border-t border-gray-200 relative">
        <!-- Menú Desktop -->
        <div class="hidden lg:block container mx-auto px-4 py-3">
            <div class="flex items-center justify-between gap-4">
                <nav class="flex items-center gap-4 xl:gap-6 text-sm">
                    <!-- Productos con Mega Menú -->
                    <div class="relative group">
                        <button class="text-gray-800 hover:text-turquesa flex items-center gap-2 font-bold">
                            <i class="fas fa-bars"></i>
                            Productos
                        </button>
                        <!-- Mega Menú -->
                        <div class="absolute top-full left-0 w-[1200px] bg-white shadow-[0_10px_20px_rgba(0,0,0,0.1)] rounded-b-lg py-6 hidden group-hover:block z-50" style="box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                            <div class="grid grid-cols-6 gap-0">
                                <!-- Columna 1: Implantología -->
                                <div class="px-4 py-4 border-r border-[#f0f0f0] cursor-pointer">
                                    <div class="border border-[#e2e8f0] rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-[#15aabf]/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-tooth text-[#15aabf] text-sm"></i>
                                            <h4 class="font-bold text-[#6b7280] text-[11px] uppercase tracking-wide hover:text-[#15aabf] transition-colors">Implantología</h4>
                                        </div>
                                        <div class="h-0.5 w-12 bg-[#15aabf] mb-5 ml-6"></div>
                                        <div class="space-y-4">
                                            <div>
                                                <p class="text-[#15aabf] font-semibold text-[10px] mb-2 uppercase tracking-wide">AB</p>
                                                <ul class="space-y-1">
                                                    <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Implantes</a></li>
                                                    <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Aditamentos</a></li>
                                                    <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Kits</a></li>
                                                </ul>
                                            </div>
                                            <div>
                                                <p class="text-[#15aabf] font-semibold text-[10px] mb-2 uppercase tracking-wide">GDT</p>
                                                <ul class="space-y-1">
                                                    <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Implantes</a></li>
                                                    <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Aditamentos</a></li>
                                                    <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Kits</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna 2: Regeneración Ósea Guiada -->
                                <div class="px-4 py-4 border-r border-[#f0f0f0] cursor-pointer">
                                    <div class="border border-[#e2e8f0] rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-[#15aabf]/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-bone text-[#15aabf] text-sm"></i>
                                            <h4 class="font-bold text-[#6b7280] text-[11px] uppercase tracking-wide leading-tight hover:text-[#15aabf] transition-colors">Regeneración Ósea Guiada</h4>
                                        </div>
                                        <div class="h-0.5 w-12 bg-[#15aabf] mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Biomateriales</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Regeneración Guiada Bucal</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Suturas</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Columna 3: Osteosíntesis -->
                                <div class="px-4 py-4 border-r border-[#f0f0f0] cursor-pointer">
                                    <div class="border border-[#e2e8f0] rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-[#15aabf]/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-layer-group text-[#15aabf] text-sm"></i>
                                            <h4 class="font-bold text-[#6b7280] text-[11px] uppercase tracking-wide hover:text-[#15aabf] transition-colors">Osteosíntesis</h4>
                                        </div>
                                        <div class="h-0.5 w-12 bg-[#15aabf] mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Placas</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Tornillos</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Cajetín</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Columna 4: Cuidado Bucal -->
                                <div class="px-4 py-4 border-r border-[#f0f0f0] cursor-pointer">
                                    <div class="border border-[#e2e8f0] rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-[#15aabf]/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-smile text-[#15aabf] text-sm"></i>
                                            <h4 class="font-bold text-[#6b7280] text-[11px] uppercase tracking-wide hover:text-[#15aabf] transition-colors">Cuidado Bucal</h4>
                                        </div>
                                        <div class="h-0.5 w-12 bg-[#15aabf] mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Cuidados Especiales</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Cuidados Diarios</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Columna 5: Instrumentos -->
                                <div class="px-4 py-4 border-r border-[#f0f0f0] cursor-pointer">
                                    <div class="border border-[#e2e8f0] rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-[#15aabf]/30 transition-colors">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-scissors text-[#15aabf] text-sm"></i>
                                            <h4 class="font-bold text-[#6b7280] text-[11px] uppercase tracking-wide hover:text-[#15aabf] transition-colors">Instrumentos</h4>
                                        </div>
                                        <div class="h-0.5 w-12 bg-[#15aabf] mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Tijeras</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Pinzas</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Separadores</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Cinceles</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Periostótomos</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Columna 6: Planificación Digital y Equipos -->
                                <div class="px-4 py-4 cursor-pointer">
                                    <div class="border border-[#e2e8f0] rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-[#15aabf]/30 transition-colors">
                                        <!-- Planificación Digital -->
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-digital-tachograph text-[#15aabf] text-sm"></i>
                                            <h4 class="font-bold text-[#6b7280] text-[11px] uppercase tracking-wide hover:text-[#15aabf] transition-colors">Planificación Digital</h4>
                                        </div>
                                        <div class="h-0.5 w-12 bg-[#15aabf] mb-5 ml-6"></div>
                                        <ul class="space-y-2 mb-6">
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Planificación Digital</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Impresión 3D</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Escaneo Intraoral</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> PD Completa</a></li>
                                        </ul>
                                        <!-- Equipos -->
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-cog text-[#15aabf] text-sm"></i>
                                            <h4 class="font-bold text-[#6b7280] text-[11px] uppercase tracking-wide hover:text-[#15aabf] transition-colors">Equipos</h4>
                                        </div>
                                        <div class="h-0.5 w-12 bg-[#15aabf] mb-5 ml-6"></div>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Equipos odontológicos</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Piezas de mano</a></li>
                                            <li><a href="#" class="text-[#718096] text-[11px] hover:text-[#15aabf] flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-[#15aabf] text-[10px]">></span> Motores</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Inicio -->
                    <a href="index.php" class="text-gray-800 hover:text-turquesa font-bold whitespace-nowrap ml-16">Inicio</a>
                    <!-- Categorías con dropdown + -->
                    <a href="#" class="text-gray-800 hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap">Cirugía Bucal <span class="text-xs">+</span></a>
                    <a href="#" class="text-gray-800 hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap">Maxilofacial <span class="text-xs">+</span></a>
                    <a href="#" class="text-gray-800 hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap">Periodoncia <span class="text-xs">+</span></a>
                    <a href="#" class="text-gray-800 hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap">Ortodoncia <span class="text-xs">+</span></a>
                    <a href="#" class="text-gray-800 hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap">Endodoncia <span class="text-xs">+</span></a>
                </nav>
                <div class="flex items-center gap-4 ml-auto">
                    <a href="tel:+584244669150" class="hidden lg:flex items-center gap-3 text-gray-800 text-sm hover:text-turquesa">
                        <i class="fas fa-phone text-turquesa text-2xl"></i>
                        <div class="flex flex-col">
                            <span class="font-medium">Contáctanos</span>
                            <span class="text-red-500 font-bold">+58 4244669150</span>
                        </div>
                    </a>
                    <a href="#" class="bg-turquesa hover:bg-turquesa-dark text-white text-sm px-5 py-2.5 rounded-full flex items-center gap-2 transition-colors">
                        Recursos Clínicos
                        <i class="fas fa-cloud-download-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- Menú Mobile - Scrollable -->
        <div class="lg:hidden border-t border-gray-100">
            <div class="flex overflow-x-auto scrollbar-hide py-2 px-4 gap-4 text-sm whitespace-nowrap">
                <a href="catalogo.php" class="text-gray-800 hover:text-turquesa font-bold flex-shrink-0">Productos</a>
                <a href="index.php" class="text-gray-800 hover:text-turquesa font-bold flex-shrink-0">Inicio</a>
                <a href="#" class="text-gray-800 hover:text-turquesa font-bold flex-shrink-0">Cirugía Bucal</a>
                <a href="#" class="text-gray-800 hover:text-turquesa font-bold flex-shrink-0">Periodoncia</a>
                <a href="#" class="text-gray-800 hover:text-turquesa font-bold flex-shrink-0">Ortodoncia</a>
                <a href="#" class="text-gray-800 hover:text-turquesa font-bold flex-shrink-0">Endodoncia</a>
            </div>
        </div>
    </div>
</header>
