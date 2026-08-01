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
                     data-src="{{ asset('storage/' . $settings->image) }}"
                     data-fallback="{{ asset('images/logo-helin.webp') }}"
                     alt="Helin"
                     class="h-9 md:h-12 w-auto mb-2 md:mb-4 lazy-image">
            @else
                <h3 class="text-2xl md:text-3xl mb-2 md:mb-4 lowercase">helin.</h3>
            @endif
                <p class="text-white/80 text-xs md:text-sm leading-relaxed mb-3 md:mb-6">{{ $settings->tagline }}</p>
                <div class="flex space-x-2 md:space-x-3">
                    <a href="https://www.instagram.com/helin.latam/" target="_blank" class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-sm md:text-base">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/helin-latam" target="_blank" class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-sm md:text-base">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://www.facebook.com/helin.latam.user" target="_blank" class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-sm md:text-base">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>

            <!-- Columna 2: Nuestra Empresa + Políticas -->
            <div>
                <h4 class="text-base md:text-lg mb-2.5 md:mb-4"><a href="{{ route('nuestra-empresa') }}" class="hover:text-white/80 transition-colors">Nuestra Empresa</a></h4>
                <ul class="space-y-1.5 md:space-y-2 text-white/80 text-xs md:text-sm leading-snug">
                    <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ route('nuestra-empresa') }}#quienes-somos" class="hover:text-white transition-colors">Quiénes somos</a></li>
                    <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ route('nuestra-empresa') }}#nuestros-aliados" class="hover:text-white transition-colors">Aliados comerciales</a></li>
                    <li class="flex items-center gap-1.5 md:gap-2"><span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-white rounded-full flex-shrink-0"></span><a href="{{ route('politicas') }}" class="hover:text-white transition-colors">Nuestras políticas</a></li>
                </ul>
            </div>

            <!-- Columna 3: Nuestras Sedes -->
            <div>
                <h4 class="text-base md:text-lg mb-2.5 md:mb-4">Nuestras sedes</h4>
                <ul class="space-y-1.5 md:space-y-2 text-white/80 text-xs md:text-sm">
                    {{-- Caracas --}}
                    <li class="flex items-center gap-1.5 md:gap-2">
                        <div class="flex items-center gap-1 md:gap-1.5">
                            @if($settings && $settings->caracas_location)
                                <a href="{{ $settings->caracas_location }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            @endif
                            @if($settings && $settings->caracas_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->caracas_whatsapp) }}?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                            @endif
                        </div>
                        <span class="font-medium">Caracas</span>
                    </li>

                    {{-- Valencia --}}
                    <li class="flex items-center gap-1.5 md:gap-2">
                        <div class="flex items-center gap-1 md:gap-1.5">
                            @if($settings && $settings->valencia_location)
                                <a href="{{ $settings->valencia_location }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            @endif
                            @if($settings && $settings->valencia_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->valencia_whatsapp) }}?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                            @endif
                        </div>
                        <span class="font-medium">Valencia</span>
                    </li>

                    {{-- Barquisimeto --}}
                    <li class="flex items-center gap-1.5 md:gap-2">
                        <div class="flex items-center gap-1 md:gap-1.5">
                            @if($settings && $settings->barquisimeto_location)
                                <a href="{{ $settings->barquisimeto_location }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            @endif
                            @if($settings && $settings->barquisimeto_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->barquisimeto_whatsapp) }}?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                            @endif
                        </div>
                        <span class="font-medium">Barquisimeto</span>
                    </li>

                    {{-- Maracaibo --}}
                    <li class="flex items-center gap-1.5 md:gap-2">
                        <div class="flex items-center gap-1 md:gap-1.5">
                            @if($settings && $settings->maracaibo_location)
                                <a href="{{ $settings->maracaibo_location }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            @endif
                            @if($settings && $settings->maracaibo_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->maracaibo_whatsapp) }}?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @else
                                <div class="w-5 h-5 md:w-6 md:h-6 bg-white/20 rounded-full flex items-center justify-center cursor-pointer hover:bg-white hover:text-turquesa transition-all duration-300 text-[11px] md:text-sm">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                            @endif
                        </div>
                        <span class="font-medium">Maracaibo</span>
                    </li>
                </ul>
            </div>

            <!-- Columna 4: Contáctanos -->
            <div>
                <h4 class="text-base md:text-lg mb-2.5 md:mb-4">Contáctanos</h4>
                <div class="space-y-2 md:space-y-3 text-white/80 text-xs md:text-sm">
                    <a href="mailto:{{ $settings->email ?? 'info@helinbeam.com' }}" class="flex items-center gap-2 md:gap-3 hover:text-white transition-colors duration-300">
                        <div class="w-7 h-7 md:w-8 md:h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 hover:bg-white hover:text-turquesa transition-all duration-300 text-xs md:text-sm">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span>{{ $settings->email ?? 'info@helinbeam.com' }}</span>
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings->phone ?? '+58 412 739 8580') }}" class="flex items-center gap-2 md:gap-3 hover:text-white transition-colors duration-300">
                        <div class="w-7 h-7 md:w-8 md:h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0 hover:bg-white hover:text-turquesa transition-all duration-300 text-xs md:text-sm">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] md:text-xs">Central telefónica</span>
                            <span>{{ $settings->phone ?? '+58 412 739 8580' }}</span>
                        </div>
                    </a>
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
            <p class="text-center text-white/80 text-xs footer-copy">© <span id="year"></span> Helin. Desarrollado por <a href="https://syevolution.com" target="_blank" rel="noopener noreferrer" class="hover:text-white underline transition-colors">SY Evolution</a>.</p>
            <script>document.getElementById("year").textContent = new Date().getFullYear();</script>
        </div>
    </div>
</footer>
