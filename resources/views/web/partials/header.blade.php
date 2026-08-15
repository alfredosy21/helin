<!-- Barra Informativa Superior -->
<div class="bg-turquesa text-white text-xs sm:text-sm border-b border-white/20">
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
    <div class="container mx-auto px-3 sm:px-4 py-2 sm:py-4">
        <div class="flex items-center gap-2 sm:gap-6">
            @php
                    $settings = \App\Models\Settings::getSettings();
                @endphp
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white flex-shrink-0">
                    <img src="{{ asset('images/logo_blanco_helin.png') }}" alt="Helin" class="h-12 sm:h-16 w-auto">
                </a>

            <!-- Buscador - Solo en tablet/desktop -->
            <div class="hidden sm:block flex-1 max-w-4xl mx-auto">
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
                                <select name="category" onchange="this.form.submit()" class="bg-transparent text-helin-heading text-xs outline-none cursor-pointer w-full font-semibold">
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
            <div class="flex items-center gap-2 sm:gap-3 ml-auto">
                @php
                    $settings = \App\Models\Settings::getSettings();
                @endphp
                <!-- WhatsApp - solo desktop -->
                @php
                    $headerOffices = $settings && is_array($settings->offices) ? $settings->offices : [];
                    $headerWhatsApp = null;
                    foreach ($headerOffices as $office) {
                        if (!empty($office['whatsapp'])) {
                            $headerWhatsApp = preg_replace('/[^0-9]/', '', $office['whatsapp']);
                            break;
                        }
                    }
                @endphp
                @if($headerWhatsApp)
                <a href="https://wa.me/{{ $headerWhatsApp }}?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="hidden lg:flex items-center gap-2 bg-turquesa/60 text-white px-4 h-11 rounded-full hover:bg-[#123F4A] transition text-sm">
                    <i class="fab fa-whatsapp text-2xl"></i>
                    <span>Escríbenos</span>
                </a>
                @endif
                <!-- Carrito -->
                <a href="{{ route('carrito') }}" class="flex items-center gap-1 sm:gap-2 text-white hover:text-[#123F4A] transition p-1 sm:p-0">
                    <div class="relative">
                        <i class="fas fa-shopping-cart text-lg sm:text-xl"></i>
                        <span id="cart-badge" class="absolute -top-1.5 -right-1.5 sm:-top-2 sm:-right-2 bg-turquesa text-white text-[10px] sm:text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center border border-white" style="display:none;">0</span>
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
    <div class="bg-white border-t border-helin-border relative">
        <!-- Menú Desktop -->
        <div class="hidden lg:block container mx-auto px-4 py-3">
            <div class="flex items-center justify-between gap-4">
                <nav class="flex items-center gap-6 xl:gap-8 text-sm">
                    <!-- Productos con Mega Menú -->
                    <div class="relative group">
                        <a href="{{ route('catalogo') }}" class="text-helin-heading hover:text-turquesa flex items-center gap-2 font-bold border-b-2 border-transparent pb-1">
                            <i class="fas fa-bars"></i>
                            Productos
                        </a>
                        <!-- Mega Menú -->
                        @php
                            $megaCategories = \App\Models\Category::active()->ordered()->with('activeChildren')->get();
                        @endphp
                        <div class="absolute top-full left-0 w-[1200px] bg-white shadow-[0_10px_20px_rgba(0,0,0,0.1)] rounded-b-lg py-6 hidden group-hover:block z-50" style="box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                            <div class="grid grid-cols-6 gap-0">
                                @foreach($megaCategories as $category)
                                    <div class="px-4 py-4 border-r border-helin-border cursor-pointer {{ ($loop->last || $loop->iteration % 6 === 0) ? 'border-r-0' : '' }}">
                                        <div class="border border-helin-border rounded-lg p-3 h-full hover:bg-[#e6f7f7] hover:border-turquesa/30 transition-colors">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-tooth text-sm" style="color: #6BC2C3;"></i>
                                                <a href="{{ route('catalogo', ['category' => $category->slug]) }}" class="text-helin-text text-[12px] uppercase tracking-wide hover:text-turquesa transition-colors font-semibold">{{ $category->name }}</a>
                                            </div>
                                            <div class="h-0.5 w-12 bg-turquesa mb-5 ml-6"></div>
                                            @if($category->activeChildren->count())
                                                <ul class="space-y-2">
                                                    @foreach($category->activeChildren as $child)
                                                        <li><a href="{{ route('catalogo', ['category' => $child->slug]) }}" class="text-helin-text text-[13px] hover:text-turquesa flex items-center gap-2 py-1 font-normal transition-colors"><span class="text-turquesa text-[10px]">></span> {{ $child->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @php
                        $currentRoute = request()->route()->getName();
                        $currentCategory = request()->route('category') ?? request('category');
                        $currentCategory = is_array($currentCategory) ? implode(',', $currentCategory) : $currentCategory;
                        $currentTag = request('tag');
                        $headerMenuItems = \App\Models\Menus::getHeaderItems();
                    @endphp
                    <!-- Categorías / Menú configurable -->
                    @foreach($headerMenuItems as $loopIndex => $menuItem)
                        @php
                            $menuUrl = $menuItem->url;
                            $menuCategory = null;
                            if (!$menuUrl && $menuItem->title) {
                                $menuCategory = \App\Models\Category::with('activeChildren')->where('name', $menuItem->title)
                                    ->orWhere('name', 'like', '%' . $menuItem->title . '%')
                                    ->orWhere('slug', 'like', '%' . \Illuminate\Support\Str::slug($menuItem->title) . '%')
                                    ->first();
                                $menuUrl = $menuCategory ? route('catalogo', ['category' => $menuCategory->slug]) : route('catalogo');
                            }
                            $hasDropdown = $menuItem->children->count() > 0 || ($menuCategory && $menuCategory->activeChildren && $menuCategory->activeChildren->count() > 0);
                            $menuIsActive = false;
                            $homeUrl = route('home');
                            if ($menuUrl === $homeUrl || $menuUrl === '/' || rtrim($menuUrl, '/') === rtrim($homeUrl, '/')) {
                                $menuIsActive = $currentRoute === 'home';
                            } elseif (str_contains($menuUrl, 'tag=on_sale')) {
                                $menuIsActive = $currentTag === 'on_sale';
                            } elseif ($currentCategory && str_contains($menuUrl, $currentCategory)) {
                                $menuIsActive = true;
                            }
                        @endphp
                        <a href="{{ $menuUrl }}" class="text-helin-heading hover:text-turquesa flex items-center gap-1 font-bold whitespace-nowrap border-b-2 border-transparent pb-1 {{ $loopIndex === 0 ? 'ml-16' : '' }} {{ $menuIsActive ? 'text-turquesa border-turquesa' : '' }}">{{ $menuItem->title }} @if($hasDropdown)<span class="text-xs">+</span>@endif</a>
                    @endforeach
                </nav>
                <div class="flex items-center gap-4 ml-auto">
                    <a href="{{ route('recursos-clinicos') }}" class="bg-turquesa hover:bg-turquesa-dark text-white text-sm px-5 py-2.5 rounded-full flex items-center gap-2 transition-colors mr-12">
                        Recursos Clínicos
                        <i class="fas fa-cloud-download-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- Menú Mobile - Scrollable -->
        <div class="lg:hidden border-t border-helin-border mobile-category-menu relative">
            <div class="flex overflow-x-auto scrollbar-hide py-2 px-4 gap-3 text-xs sm:text-sm whitespace-nowrap">
                <a href="{{ route('catalogo') }}" class="text-helin-heading hover:text-turquesa font-semibold flex-shrink-0 px-1">Productos</a>
                <a href="{{ route('home') }}" class="text-helin-heading hover:text-turquesa font-semibold flex-shrink-0 px-1">Inicio</a>
                @foreach(\App\Models\Category::active()->ordered()->get() as $category)
                    <a href="{{ route('catalogo', ['category' => $category->slug]) }}" class="text-helin-heading hover:text-turquesa font-semibold flex-shrink-0 px-1">{{ $category->name }}</a>
                @endforeach
                <a href="{{ route('catalogo', ['tag' => 'on_sale']) }}" class="text-helin-heading hover:text-turquesa font-semibold flex-shrink-0 px-1">Ofertas</a>
            </div>
            <span class="scroll-hint-fade" aria-hidden="true"></span>
        </div>
    </div>
</header>
