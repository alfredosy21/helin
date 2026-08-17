<!-- Footer -->
<footer class="bg-turquesa text-white">
    <div class="container mx-auto px-4 py-6 md:py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-8">
            <!-- Columna 1: Identidad y Redes Sociales -->
            <div>
                @php
                $settings = \App\Models\Settings::getSettings();
            @endphp
            @if($settings && $settings->image)
                <img src="{{ asset('storage/' . $settings->image) }}"
                     alt="Helin"
                     class="h-9 md:h-12 w-auto mb-2 md:mb-4">
            @else
                <h3 class="text-2xl md:text-3xl mb-2 md:mb-4 lowercase">helin.</h3>
            @endif
                <p class="text-white/80 text-xs md:text-sm leading-relaxed mb-3 md:mb-6">{{ $settings->tagline }}</p>
                <div class="flex space-x-2 md:space-x-3">
                    @php
                        $socials = [
                            'instagram' => $settings->instagram ?? null,
                            'facebook' => $settings->facebook ?? null,
                            'linkedin' => $settings->linkedin ?? null,
                            'youtube' => $settings->youtube ?? null,
                        ];
                        $socialIcons = [
                            'instagram' => 'fab fa-instagram',
                            'facebook' => 'fab fa-facebook-f',
                            'linkedin' => 'fab fa-linkedin-in',
                            'youtube' => 'fab fa-youtube',
                        ];
                    @endphp
                    @foreach($socials as $network => $url)
                        @if($url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-sm md:text-base">
                                <i class="{{ $socialIcons[$network] }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Columna 2: Nuestra Empresa + Políticas -->
            <div>
                <h4 class="text-base md:text-lg mb-2.5 md:mb-4"><a href="{{ route('nuestra-empresa') }}" class="hover:text-white/80 transition-colors">Nuestra Empresa</a></h4>
                @php
                    $footerMenus = \App\Models\Menus::getFooterItems();
                    $empresaRoot = $footerMenus->firstWhere('title', 'Nuestra Empresa');
                @endphp
                <ul class="space-y-1.5 md:space-y-2 text-white/80 text-xs md:text-sm leading-snug">
                    @if($empresaRoot && $empresaRoot->children->count())
                        @foreach($empresaRoot->children as $footerLink)
                            <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ $footerLink->url ?: '#' }}" class="hover:text-white transition-colors">{{ $footerLink->title }}</a></li>
                        @endforeach
                    @else
                    <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ route('nuestra-empresa') }}#quienes-somos" class="hover:text-white transition-colors">Quiénes somos</a></li>
                    <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ route('nuestra-empresa') }}#nuestros-aliados" class="hover:text-white transition-colors">Aliados comerciales</a></li>
                    <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ route('politicas') }}" class="hover:text-white transition-colors">Nuestras políticas</a></li>
                    <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ route('contactanos') }}" class="hover:text-white transition-colors">Contáctanos</a></li>
                    @endif
                </ul>
            </div>

            <!-- Columna 3: Nuestras Sedes -->
            <div>
                <h4 class="text-base md:text-lg mb-2.5 md:mb-4">Nuestras sedes</h4>
                @php
                    $offices = $settings && $settings->offices ? $settings->offices : [];
                @endphp
                <ul class="space-y-1.5 md:space-y-2 text-white/80 text-xs md:text-sm">
                    @foreach($offices as $office)
                        @php
                            $cityName = ucfirst($office['name'] ?? '');
                            $whatsapp = $office['whatsapp'] ?? null;
                            $location = $office['url'] ?? null;
                            if ($location && !preg_match('~^https?://~', $location)) {
                                $location = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($location);
                            }
                        @endphp
                        @if($whatsapp || $location)
                        <li class="flex items-center gap-1.5 md:gap-2">
                            <div class="flex items-center gap-1 md:gap-1.5">
                                @if($location)
                                    <a href="{{ $location }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </a>
                                @else
                                    <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                @endif
                                @if($whatsapp)
                                    <a href="{{ $whatsapp }}?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @else
                                    <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                @endif
                            </div>
                            <span class="font-medium">{{ $cityName }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <!-- Columna 4: Contáctanos -->
            <div>
                <h4 class="text-base md:text-lg mb-2.5 md:mb-4">Contáctanos</h4>
                <div class="space-y-2 md:space-y-3 text-white/80 text-xs md:text-sm">
                    @if($settings && $settings->email)
                    <a href="mailto:{{ $settings->email }}" class="flex items-center gap-2 md:gap-3 hover:text-white transition-colors duration-300">
                        <div class="w-7 h-7 md:w-8 md:h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 hover:bg-white hover:text-turquesa transition-all duration-300 text-xs md:text-sm">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span>{{ $settings->email }}</span>
                    </a>
                    @endif
                    @if($settings && $settings->phone)
                    <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings->phone) }}" class="flex items-center gap-2 md:gap-3 hover:text-white transition-colors duration-300">
                        <div class="w-7 h-7 md:w-8 md:h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 hover:bg-white hover:text-turquesa transition-all duration-300 text-xs md:text-sm">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] md:text-xs">Central telefónica</span>
                            <span>{{ $settings->phone }}</span>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Scroll to Top -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-5 right-5 w-10 h-10 md:bottom-8 md:right-8 md:w-12 md:h-12 bg-turquesa hover:bg-turquesa-dark text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110 z-50 scroll-to-top text-sm md:text-base">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Franja de Cierre -->
    <div class="bg-turquesa-dark border-t border-white/20">
        <div class="container mx-auto px-4 py-1.5 md:py-2">
            <p class="text-center text-white/80 text-xs footer-copy">© <span id="year"></span> Helin. {{ $settings->copy ?? 'Desarrollado por <a href="https://syevolution.com" target="_blank" rel="noopener noreferrer" class="hover:text-white underline transition-colors">SY Evolution</a>.' }}</p>
            <script src="@minAsset('helin/js/footer.js')"></script>
        </div>
    </div>
</footer>
