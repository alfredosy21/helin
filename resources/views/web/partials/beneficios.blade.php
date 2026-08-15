<!-- Barra de Beneficios -->
@php
    $benefitsSection = \App\Models\Sections::find(\App\Models\Sections::BENEFITS);
    $benefitsJson = $benefitsSection ? (json_decode($benefitsSection->items, true) ?: []) : [];
    $benefitsItems = $benefitsJson['items'] ?? $benefitsJson;
@endphp
<section class="bg-white py-4 sm:py-5">
    <div class="container mx-auto px-4">
        <div class="beneficios-scroll-wrap relative md:overflow-visible">
        <div class="flex md:grid md:grid-cols-5 gap-4 overflow-x-auto md:overflow-visible pb-2 md:pb-0 scrollbar-hide">
            @if(count($benefitsItems) > 0)
                @foreach($benefitsItems as $benefit)
                <div class="flex items-center gap-3 p-4 min-w-[200px] md:min-w-0 rounded-xl transition-colors duration-300">
                    <div class="w-12 h-12 bg-turquesa/20 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                        <i class="{{ $benefit['icon'] ?? 'fas fa-check' }} text-turquesa text-xl transition-colors duration-300"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-helin-heading text-sm transition-colors duration-300">{{ $benefit['title'] ?? '' }}</h4>
                        <p class="text-helin-text text-xs transition-colors duration-300">{{ $benefit['text'] ?? $benefit['description'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            @else
            <div class="flex items-center gap-3 p-4 min-w-[200px] md:min-w-0 rounded-xl transition-colors duration-300">
                <div class="w-12 h-12 bg-turquesa/20 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                    <i class="fas fa-truck text-turquesa text-xl transition-colors duration-300"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-helin-heading text-sm transition-colors duration-300">Envíos rápidos y seguros</h4>
                    <p class="text-helin-text text-xs transition-colors duration-300">A toda Venezuela</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 min-w-[200px] md:min-w-0 rounded-xl transition-colors duration-300">
                <div class="w-12 h-12 bg-turquesa/20 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                    <i class="fas fa-shield-alt text-turquesa text-xl transition-colors duration-300"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-helin-heading text-sm transition-colors duration-300">Garantía Helin</h4>
                    <p class="text-helin-text text-xs transition-colors duration-300">Calidad y respaldo en cada producto</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 min-w-[200px] md:min-w-0 rounded-xl transition-colors duration-300">
                <div class="w-12 h-12 bg-turquesa/20 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                    <i class="fas fa-headset text-turquesa text-xl transition-colors duration-300"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-helin-heading text-sm transition-colors duration-300">Asesoría especializada</h4>
                    <p class="text-helin-text text-xs transition-colors duration-300">Soporte técnico y comercial</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 min-w-[200px] md:min-w-0 rounded-xl transition-colors duration-300">
                <div class="w-12 h-12 bg-turquesa/20 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                    <i class="fas fa-box text-turquesa text-xl transition-colors duration-300"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-helin-heading text-sm transition-colors duration-300">Stock disponible</h4>
                    <p class="text-helin-text text-xs transition-colors duration-300">Productos de las mejores marcas</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 min-w-[200px] md:min-w-0 rounded-xl transition-colors duration-300">
                <div class="w-12 h-12 bg-turquesa/20 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                    <i class="fa-regular fa-square-check text-turquesa text-xl transition-colors duration-300" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-helin-heading text-sm transition-colors duration-300">Productos certificados</h4>
                    <p class="text-helin-text text-xs transition-colors duration-300">Cumplimos con los más altos estándares</p>
                </div>
            </div>
            @endif
        </div>
        <span class="beneficios-scroll-fade beneficios-scroll-fade--right md:hidden" aria-hidden="true"></span>
        </div>
    </div>
</section>
