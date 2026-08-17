<!-- Barra Informativa Superior -->
<div class="bg-turquesa text-white text-xs sm:text-sm border-b border-white/20 hidden sm:block">
    <div class='container mx-auto px-4 py-2 flex flex-wrap items-center justify-center gap-y-1.5 gap-x-3 sm:gap-4 text-center'>
        @php
            $settings = \App\Models\Settings::getSettings();
        @endphp
        <span class='leading-snug'>{{ $settings->tagline }}</span>
        <a href='{{ route("catalogo") }}' class='border border-white/30 rounded-full px-3 sm:px-4 py-1 hover:bg-white/10 transition text-xs sm:text-sm whitespace-nowrap flex-shrink-0'>VER PRODUCTOS</a>
    </div>
</div>

<!-- Barra de Navegación Principal -->
<header class='bg-turquesa sticky top-0 z-50'>
    <div class="w-full mx-auto px-3 sm:px-4 lg:max-w-[1200px] lg:px-10 py-2 sm:py-4">
        <div class='flex items-center gap-2 sm:gap-6'>
            @php
                $settings = \App\Models\Settings::getSettings();
                $headerOffices = $settings && is_array($settings->offices) ? $settings->offices : [];
                $headerWhatsApp = null;
                foreach ($headerOffices as $office) {
                    if (!empty($office['whatsapp'])) {
                        $headerWhatsApp = preg_replace('/[^0-9]/', '', $office['whatsapp']);
                        break;
                    }
                }
            @endphp
            <!-- Logo -->
            <a href='{{ route("home") }}' class='flex items-center gap-2 text-white flex-shrink-0'>
                <img src='{{ asset("images/logo_blanco_helin.png") }}' alt='Helin' class='h-12 sm:h-16 w-auto'>
            </a>

            <!-- Buscador - Solo en tablet/desktop -->
            <div class='hidden sm:block flex-1 max-w-4xl lg:max-w-2xl mx-auto'>
                <div class='header-search-wrapper'>
                    <div class='bg-white rounded-full shadow-lg border border-gray-100'>
                        <div class='flex items-center'>
                            <div class='flex-1 flex items-center px-3 py-3'>
                                <i class='fas fa-search text-turquesa mr-2 text-sm'></i>
                                <input type='text' id='header-search-input' placeholder='¿Qué producto estás buscando?' autocomplete='off' class='flex-1 outline-none text-helin-heading text-sm w-full bg-transparent'>
                            </div>
                            <div class='border-l flex items-center px-3 hidden md:flex min-w-[140px] py-3'>
                                @php
                                    $categories = \App\Models\Category::active()->ordered()->get();
                                @endphp
                                <select name='category' id='header-category-select' class='bg-transparent text-helin-heading text-xs outline-none cursor-pointer w-full font-semibold'>
                                    <option value=''>Todas las categorías</option>
                                    @foreach($categories as $category)
                                        <option value='{{ $category->slug }}' {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type='button' id='header-search-submit' class='bg-turquesa hover:bg-turquesa-dark text-white w-10 h-10 flex items-center justify-center transition-colors flex-shrink-0 rounded-full mx-1'>
                                <i class='fas fa-search text-sm'></i>
                            </button>
                        </div>
                    </div>
                    <div id='header-search-dropdown' class='hidden mt-2'></div>
                </div>
            </div>

            <!-- Acciones -->
            <div class='flex items-center gap-2 sm:gap-3 ml-auto lg:ml-0 lg:flex-shrink-0'>
                <!-- WhatsApp - solo desktop -->
                @if($headerWhatsApp)
                <a href='https://wa.me/{{ $headerWhatsApp }}?text={{ urlencode("Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.") }}' target='_blank' class='hidden lg:flex items-center gap-2 bg-turquesa/60 text-white px-4 h-11 rounded-full hover:bg-[#123F4A] transition text-sm'>
                    <i class='fab fa-whatsapp text-2xl'></i>
                    <span>Escríbenos</span>
                </a>
                @endif
                <!-- Carrito -->
                <a href='{{ route("carrito") }}' class='flex items-center gap-1 sm:gap-2 text-white hover:text-[#123F4A] transition p-1 sm:p-0'>
                    <div class='relative'>
                        <i class='fas fa-shopping-cart text-lg sm:text-xl'></i>
                        <span id='cart-badge' class='absolute -top-1.5 -right-1.5 sm:-top-2 sm:-right-2 bg-turquesa text-white text-[10px] sm:text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center border border-white' style='display:none;'>0</span>
                    </div>
                    <span class='hidden sm:inline text-sm'>Ver carrito</span>
                </a>
                <!-- Mobile Menu Button -->
                <button id='mobile-menu-btn' class='lg:hidden text-white p-2 hover:bg-white/10 rounded-lg transition'>
                    <i class='fas fa-bars text-lg sm:text-xl'></i>
                </button>
            </div>
        </div>

        <!-- Línea divisora mobile -->
        <hr class='sm:hidden w-full' style='border:none;border-top:1px solid rgba(255,255,255,0.15);margin-top:0.5rem;'>

        <!-- Buscador Móvil - Siempre visible -->
        <div id='mobile-search' class='sm:hidden mt-2 pb-1'>
            <div class='mobile-search-wrapper'>
                <div class='bg-white rounded-full p-1 shadow-sm'>
                    <div class='flex items-center'>
                        <div class='flex-1 flex items-center px-3'>
                            <i class='fas fa-search text-helin-text mr-2 text-sm'></i>
                            <input type='text' id='mobile-search-input' placeholder='Buscar productos...' autocomplete='off' class='flex-1 outline-none text-helin-heading text-sm w-full'>
                        </div>
                        <button type='button' id='mobile-search-submit' class='bg-turquesa hover:bg-turquesa-dark text-white w-10 h-10 flex items-center justify-center transition-colors flex-shrink-0 rounded-full mx-0.5'>
                            <i class='fas fa-search text-sm'></i>
                        </button>
                    </div>
                </div>
                <div id='mobile-search-dropdown' class='hidden'></div>
            </div>
        </div>
    </div>

    <!-- Barra de Menú de Categorías con Mega Menú -->
    <div class='hidden lg:block bg-white border-t border-helin-border relative'>
        <!-- Menú Desktop -->
        <div class='py-3'>
            <div class="flex items-center w-full max-w-[1200px] mx-auto px-10">
                <!-- Productos - Posición fija izquierda -->
                <nav class='flex items-center text-sm'>
                    <!-- Productos con Mega Menú -->
                    <div class='relative group'>
                        <a href='{{ route("catalogo") }}' class='text-helin-heading hover:text-turquesa flex items-center gap-2 font-bold border-b-2 border-transparent py-1'>
                            <i class='fas fa-bars'></i>
                            Productos
                        </a>
                        <!-- Mega Menú -->
                        @php
                            $megaMenuColumns = \App\Models\Menus::getMegaMenuItems();
                            $headerMenuItems = \App\Models\Menus::getHeaderItems();
                            $currentRoute = request()->route()->getName();
                            $currentCategory = request()->route('category') ?? request('category');
                            $currentCategory = is_array($currentCategory) ? implode(',', $currentCategory) : $currentCategory;
                            $currentTag = request('tag');
                        @endphp
                        <div class='absolute top-full left-0 w-[1200px] bg-white shadow-[0_10px_20px_rgba(0,0,0,0.1)] rounded-b-lg py-6 hidden group-hover:block z-50' style='box-shadow: 0 10px 20px rgba(0,0,0,0.1);'>
                            <div class='grid grid-cols-6 gap-0'>
                                @foreach($megaMenuColumns as $column)
                                    <div class='px-4 py-4 border-r border-helin-border cursor-pointer {{ $loop->last ? 'border-r-0' : '' }}'>
                                        <div class='border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors'>
                                            <div class='flex items-center gap-2 mb-2'>
                                                <i class='fas {{ $column->icon }} text-sm' style='color: #6BC2C3;'></i>
                                                <a href='{{ $column->getFullUrlAttribute() }}' class='text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold'>{{ $column->title }}</a>
                                            </div>
                                            <div class='h-0.5 w-12 bg-turquesa mb-5 ml-6'></div>
                                            @foreach($column->children as $child)
                                                @if($child->children->count())
                                                    @if($child->icon)
                                                        <div class='flex items-center gap-2 mb-2 {{ $loop->first ? '' : 'mt-6' }}'>
                                                            <i class='fas {{ $child->icon }} text-sm' style='color: #6BC2C3;'></i>
                                                            <a href='{{ $child->getFullUrlAttribute() }}' class='text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold'>{{ $child->title }}</a>
                                                        </div>
                                                        <div class='h-0.5 w-12 bg-turquesa mb-5 ml-6'></div>
                                                        @if($child->children->isNotEmpty())
                                                            <ul class='space-y-2 mb-2'>
                                                                <li><a href='{{ $child->children->first()->getFullUrlAttribute() }}' class='text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors'><span class='text-turquesa text-[10px]'>></span> {{ $child->children->first()->title }}</a></li>
                                                            </ul>
                                                            @if($child->children->count() > 1)
                                                                <div class='ml-4 pl-2 border-l border-helin-border/30'>
                                                                    <ul class='space-y-2'>
                                                                        @foreach($child->children->slice(1) as $grandchild)
                                                                            <li><a href='{{ $grandchild->getFullUrlAttribute() }}' class='text-helin-text text-[12px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors'><span class='text-turquesa text-[10px]'>→</span> {{ $grandchild->title }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <div class='mt-4 first:mt-0'>
                                                            <p class='text-turquesa font-semibold text-[10px] mb-2 uppercase tracking-wide'>{{ $child->title }}</p>
                                                            <ul class='space-y-1'>
                                                                @foreach($child->children as $grandchild)
                                                                    <li><a href='{{ $grandchild->getFullUrlAttribute() }}' class='text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors'><span class='text-turquesa text-[10px]'>></span> {{ $grandchild->title }}</a></li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @else
                                                    <ul class='space-y-2 mb-2'>
                                                        <li><a href='{{ $child->getFullUrlAttribute() }}' class='text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors'><span class='text-turquesa text-[10px]'>></span> {{ $child->title }}</a></li>
                                                    </ul>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- Inicio → Ofertas - Bloque central bajo el buscador -->
                <div class='flex-1 flex items-center justify-center gap-7 text-sm'>
                    @foreach($headerMenuItems as $menuItem)
                        @php
                            $menuUrl = $menuItem->getFullUrlAttribute();
                            $menuIsActive = false;
                            if ($menuItem->url === '/' || $menuUrl === route('home')) {
                                $menuIsActive = $currentRoute === 'home';
                            } elseif (str_contains($menuUrl, 'tag=on_sale')) {
                                $menuIsActive = $currentTag === 'on_sale';
                            } elseif ($currentCategory && str_contains($menuUrl, $currentCategory)) {
                                $menuIsActive = true;
                            }
                            $hasDropdown = $menuItem->children->count() > 0;
                        @endphp
                        <a href='{{ $menuUrl }}' class='text-helin-heading hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap border-b-2 border-transparent py-1 {{ $menuIsActive ? 'text-turquesa border-turquesa' : '' }}'>
                            {{ $menuItem->title }}
                            @if($hasDropdown)
                                <span class='text-xs'>+</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                <!-- Recursos Clínicos - Posición fija derecha -->
                <div class='flex items-center gap-4 flex-shrink-0'>
                    <a href='{{ route("recursos-clinicos") }}' class='bg-turquesa hover:bg-turquesa-dark text-white text-sm px-5 py-2.5 rounded-full flex items-center gap-2 transition-colors'>
                        Recursos Clínicos
                        <i class='fas fa-cloud-download-alt'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
