@extends('web.layouts.app')

@section('title', 'Helin - Material Dental de Calidad')
@section('meta-description', 'Helin - Soluciones odontológicas especializadas en implantes, instrumentos y biomateriales. Calidad garantizada para profesionales de la salud bucal en Venezuela.')
@section('meta-keywords', 'implantes dentales, material dental, instrumentos odontológicos, biomateriales, cirugía guiada, helin, productos odontológicos Venezuela')
@section('og-type', 'website')
@section('og-image', asset('images/helin-home-og.jpg'))

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/home.css') }}">
@endsection

@section('content')
<main>
   <!-- Hero Section -->
   <section class="hero relative overflow-hidden" style="
      background: url('{{ asset('images/banner.png') }}') center top / cover no-repeat;
      color: #fff;
      position: relative;
      overflow: hidden;
      ">
            <div class="hero-inner relative max-w-6xl mx-auto px-6 py-16 lg:py-20" style="
         position: relative;
         display: grid;
         grid-template-columns: auto 1fr;
         gap: 2rem;
         align-items: center;
         ">
         <!-- Hero Badges -->
         @if($heroSection && $heroSection->status == 1 && $heroSection->status_content == 1)
             @php
                 $items = $heroSection->items ? json_decode($heroSection->items, true) : [];
                 $heroBadges = $items['hero_badges'] ?? [];
             @endphp
             @if($heroSection->layout_type === 'hero_badges' && !empty($heroBadges))
                 <aside class="hero-badges hidden lg:block">
                     <div class="flex flex-col gap-4">
                         @foreach($heroBadges as $badge)
                             <div class="hero-badge flex items-center gap-2 text-[#123F4A] uppercase leading-tight" style="font-size:0.625rem; font-family:'Inter',sans-serif; font-weight:600;">
                                 <span class="mini-icon w-10 h-10 border border-[#123F4A]/30 rounded-xl flex items-center justify-center bg-[#123F4A]/10 text-lg text-[#123F4A]">{{ $badge['icon'] ?? '✓' }}</span>
                                 <span>{{ $badge['text'] ?? '' }}</span>
                             </div>
                         @endforeach
                     </div>
                 </aside>
             @else
                 {!! $heroSection->content !!}
             @endif
         @endif
         <!-- Hero Copy -->
         <div class="hero-copy text-center lg:text-left">
            <small class="block text-xs font-black uppercase tracking-wide mb-3 text-[#123F4A]">Precisión para cada procedimiento</small>
            <h1 class="text-5xl lg:text-7xl leading-tight mb-4" style="letter-spacing: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.25);">
               {!! $heroSection->content !!}
            </h1>
            @if($heroSection->description)
                <p class="text-white text-base lg:text-lg font-body font-normal mb-6 max-w-2xl mx-auto lg:mx-0" style="text-shadow: 0 1px 3px rgba(0,0,0,0.3);">{!! $heroSection->description !!}</p>
            @endif
            @if($heroSection->buttons)
                @php
                    $buttons = json_decode($heroSection->buttons, true);
                @endphp
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mt-8">
                    @foreach($buttons as $button)
                        @if($button['style'] === 'primary')
                            <a href="{{ $button['url'] === 'catalogo' ? route('catalogo') : ($button['url'] === 'contactanos' ? route('contactanos') : $button['url']) }}" class="inline-flex items-center justify-center gap-2 h-12 px-8 rounded-full bg-white text-turquesa text-sm font-black shadow-xl hover:shadow-2xl transition-all hover:scale-105" style="box-shadow: 0 16px 30px rgba(15,47,67,.16);">
                            {{ $button['text'] }}
                            </a>
                        @else
                            <a href="{{ $button['url'] === 'catalogo' ? route('catalogo') : ($button['url'] === 'contactanos' ? route('contactanos') : $button['url']) }}" class="inline-flex items-center justify-center h-12 px-8 rounded-full border-2 border-white text-white text-sm font-black hover:bg-white/10 transition-all hover:scale-105">
                            {{ $button['text'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mt-8">
                   <a href="{{ route('catalogo') }}" class="inline-flex items-center justify-center gap-2 h-12 px-8 rounded-full bg-white text-turquesa text-sm font-black shadow-xl hover:shadow-2xl transition-all hover:scale-105" style="box-shadow: 0 16px 30px rgba(15,47,67,.16);">
                   Ir a productos →
                   </a>
                   <a href="{{ route('contactanos') }}" class="inline-flex items-center justify-center h-12 px-8 rounded-full border-2 border-white text-white text-sm font-black hover:bg-white/10 transition-all hover:scale-105">
                   Hablar con un asesor
                   </a>
                </div>
            @endif
         </div>
      </div>
      <!-- Hero Visual Background -->
      <div class="hero-visual absolute inset-0 pointer-events-none" style="background: rgba(255,255,255,0.10);"></div>
   </section>

   @include('web.partials.beneficios')

   <!-- Categorías Destacadas -->
   <section class="pt-12 sm:pt-16">
      <div class="container mx-auto px-4">
         <div class="categories bg-white border border-helin-border rounded-3xl p-6 shadow-lg mb-8" style="box-shadow: 0 18px 45px rgba(15,47,67,.08);">
            <!-- Categoría Destacada -->
            <article class="category-featured relative min-h-[200px] rounded-2xl bg-gradient-to-r from-helin-soft to-white/78 p-8 mb-4 border border-helin-border overflow-hidden grid md:grid-cols-2 gap-6 items-center" style="
               background: linear-gradient(90deg, #f8ffff 0%, rgba(255,255,255,.78) 48%, rgba(151,213,212,.22)),
               radial-gradient(circle at 76% 40%, rgba(107,194,195,.12), transparent 26%);
               ">
               <div>
                  <small class="block text-turquesa text-xs font-black mb-2">Soluciones especializadas</small>
                  <h2 class="text-3xl lg:text-4xl leading-none mb-4" style="letter-spacing: 0;">Implantología</h2>
                  <a href="{{ route('catalogo', ['category' => 'implantologia']) }}" class="text-link">Ver categoría →</a>
               </div>
               <div class="implant-visual relative h-32 hidden md:block">
                  <div class="implant absolute bottom-2 left-4 w-12 h-32 rounded-2xl bg-gradient-to-r from-gray-300 to-white to-gray-400 transform rotate-12" style="
                     box-shadow: 72px -18px 0 -7px #c9d4d7, 152px 10px 0 2px #f2ffff;
                     "></div>
                  <div class="kit absolute right-2 bottom-0 w-48 h-28 rounded-2xl bg-turquesa/40 border-4 border-turquesa/25"></div>
               </div>
            </article>
            <!-- Skeleton Loader para Categorías -->
            <div id="categoriesSkeleton" class="skeleton-grid skeleton-grid-responsive">
               @for($i = 1; $i <= 6; $i++)
                  @include('web.components.skeleton-category')
               @endfor
            </div>

            <!-- Grid de Categorías -->
            <div id="categoriesGrid" class="category-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 hidden">
               @include('web.components.category-card', ['categorySubtitle' => 'Recuperación y soporte', 'categoryTitle' => 'Regeneración ósea guiada', 'categoryLink' => route('catalogo', ['category' => 'regeneracion-guiada-bucal-gbr'])])
               @include('web.components.category-card', ['categorySubtitle' => 'Fijación y precisión', 'categoryTitle' => 'Osteosíntesis', 'categoryLink' => route('catalogo', ['category' => 'placas'])])
               @include('web.components.category-card', ['categorySubtitle' => 'Bienestar oral', 'categoryTitle' => 'Cuidado Bucal', 'categoryLink' => route('catalogo', ['category' => 'cuidados-especiales-quirurgicos'])])
               @include('web.components.category-card', ['categorySubtitle' => 'Precisión clínica', 'categoryTitle' => 'Instrumentos', 'categoryLink' => route('catalogo', ['category' => 'tijeras'])])
               @include('web.components.category-card', ['categorySubtitle' => 'Tecnología para tu práctica', 'categoryTitle' => 'Equipos', 'categoryLink' => route('catalogo', ['category' => 'equipos-odontologicos'])])
               @include('web.components.category-card', ['categorySubtitle' => 'Diagnóstico y exactitud', 'categoryTitle' => 'Planificación Digital', 'categoryLink' => route('catalogo', ['category' => 'planificacion-digital'])])
            </div>
         </div>
      </div>
   </section>

   <!-- Sección "Estamos cerca de ti" -->
   <div class="container mx-auto px-4" style="padding-bottom: 20px;">
      <!-- Flow Highlight Section -->
      <section class="flow-highlight">
                <aside class="how-card">
                   <h3>¿Cómo solicitar productos Helin?</h3>
                   <div class="step">
                      <a href="{{ route('catalogo') }}" class="hover:text-[#123F4A] transition-colors"><b>✓</b></a>
                      <div><strong>Selecciona tus productos</strong><span>Explora el catálogo Helin y elige los productos que necesitas.</span></div>
                      <div class="number">1</div>
                   </div>
                   <div class="step">
                      <a href="{{ route('catalogo') }}" class="hover:text-[#123F4A] transition-colors"><b>✓</b></a>
                      <div><strong>Arma tu carrito</strong><span>Agrega cantidades y revisa el resumen de tu solicitud comercial.</span></div>
                      <div class="number">2</div>
                   </div>
                   <div class="step">
                      <a href="{{ route('catalogo') }}" class="hover:text-[#123F4A] transition-colors"><b>✓</b></a>
                      <div><strong>Contacta a tu ejecutivo</strong><span>Envía la solicitud por WhatsApp al ejecutivo asignado según tu zona.</span></div>
                      <div class="number">3</div>
                   </div>
                 </aside>

         <div class="featured-products">
           <div class="featured-head">
             <h3>Destacados <span style="color:var(--helin)">Helin</span></h3>
             <a href="{{ route('catalogo', ['featured' => '1']) }}" class="crumb">VER TODOS LOS PRODUCTOS →</a>
           </div>

                      <div class="mini-grid">
                @include('web.components.product-card', [
                    'productImage' => asset('images/im1.png'),
                    'productName' => 'Producto Destacado 1',
                    'productBrand' => 'Helin',
                    'productPrice' => 0.00,
                    'productOldPrice' => null,
                    'productBadge' => 'Nuevo',
                    'productLink' => route('catalogo'),
                    'productSlug' => 'producto-destacado-1'
                ])
                @include('web.components.product-card', [
                    'productImage' => asset('images/im2.png'),
                    'productName' => 'Producto Destacado 2',
                    'productBrand' => 'Helin',
                    'productPrice' => 0.00,
                    'productOldPrice' => null,
                    'productBadge' => '',
                    'productLink' => route('catalogo'),
                    'productSlug' => 'producto-destacado-2'
                ])
                @include('web.components.product-card', [
                    'productImage' => asset('images/im3.png'),
                    'productName' => 'Producto Destacado 3',
                    'productBrand' => 'Helin',
                    'productPrice' => 0.00,
                    'productOldPrice' => null,
                    'productBadge' => 'Oferta',
                    'productLink' => route('catalogo'),
                    'productSlug' => 'producto-destacado-3'
                ])
           </div>
         </div>
      </section>

      @include('web.partials.near')
   </div>


   @foreach($productSections as $index => $section)
       @php
           $sectionTitleLower = strtolower($section->title ?? '');
           $isInstrumentosEquipos = str_contains($sectionTitleLower, 'instrumentos') || str_contains($sectionTitleLower, 'equipos');
       @endphp
       @if($section->status == 1 && $section->status_content == 1 && !$isInstrumentosEquipos)
           @php
               $sectionCat   = $sectionCategories[$section->id] ?? null;
               $category     = $sectionCat ? \App\Models\Category::where('name', $sectionCat['name'])->first() : null;
               $categorySlug = $sectionCat['slug'] ?? null;
               $products     = $category ? \App\Models\Product::where('category_id', $category->id)
                   ->where('is_active', true)
                   ->inRandomOrder()
                   ->take(4)
                   ->get() : collect();
           @endphp
           <section class="py-12 sm:py-16 {{ $index % 2 == 0 ? 'bg-helin-soft' : '' }}">
               <div class="container mx-auto px-4">
                   <div class="section-title flex items-end justify-between gap-5 mb-5">
                       <div>
                           @php
                               $isImplantologia = str_contains(strtolower($section->title), 'implantología');
                               $isRegeneracion = str_contains(strtolower($section->title), 'regeneración') || str_contains(strtolower($section->title), 'osea') || str_contains(strtolower($section->title), 'guía');
                               $isInstrumentos = str_contains(strtolower($section->title), 'instrumentos') || str_contains(strtolower($section->title), 'equipos');
                               $sectionTitle = $section->title;
                               if ($isImplantologia) {
                                   $sectionTitle = 'Destacados en Implantología';
                               } elseif ($isRegeneracion) {
                                   $sectionTitle = 'Destacados en Regeneración Ósea Guíada';
                               } elseif ($isInstrumentos) {
                                   $sectionTitle = 'Destacados en Instrumentos y Equipos';
                               } else {
                                   $sectionTitle = str_ireplace('más vendido', 'Destacados', $sectionTitle);
                                   $sectionTitle = str_ireplace('más vendidos', 'Destacados', $sectionTitle);
                               }
                           @endphp
                           <h2 class="text-2xl lg:text-3xl leading-none mb-1" style="letter-spacing: 0;">{{ $sectionTitle }}</h2>
                           @if($isImplantologia)
                               <p class="text-helin-text text-sm mt-1">Explora productos especializados para procedimientos implantológicos.</p>
                           @elseif($isRegeneracion)
                               <p class="text-helin-text text-sm mt-1">Explora biomateriales y soluciones especializadas para procedimientos regenerativos.</p>
                           @elseif($isInstrumentos)
                               <p class="text-helin-text text-sm mt-1">Explora instrumentos y equipos diseñados para aportar precisión y eficiencia a la práctica odontológica.</p>
                           @else
                               @php
                                   $description = trim(strip_tags($section->content));
                                   $firstLine = explode("\n", $description)[0];
                               @endphp
                               <p class="text-helin-text text-sm mt-1">{{ $firstLine }}</p>
                           @endif
                       </div>
                       <a href="{{ $categorySlug ? route('catalogo', ['category' => $categorySlug]) : ($section->url_button ?: route('catalogo')) }}" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">{{ $section->name_button ?: 'Ver todos los productos →' }}</a>
                   </div>
                   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                       @if($products->count() > 0)
                           @foreach($products as $product)
                               @php
                                   $badge = '';
                                   if($product->is_new) $badge = 'Nuevo';
                                   elseif($product->is_on_sale) $badge = 'Oferta';
                                   $sectionImages = [asset('images/im1.png'), asset('images/im2.png'), asset('images/im3.png'), asset('images/im4.png')];
                                   $sectionImage = $sectionImages[($loop->index) % 4];
                               @endphp
                               @include('web.components.product-card', [
                                   'productImage' => $sectionImage,
                                   'productName' => $product->name,
                                   'productBrand' => $product->brand->name ?? 'Helin',
                                   'productPrice' => $product->price,
                                   'productOldPrice' => $product->is_on_sale ? $product->price : null,
                                   'productBadge' => $badge,
                                   'productLink' => route('producto', ['slug' => $product->slug]),
                                   'productSlug' => $product->slug,
                               ])
                           @endforeach
                       @else
                           @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Producto Destacado 1', 'productBrand' => 'Helin', 'productPrice' => 299.00, 'productBadge' => 'Nuevo', 'productLink' => route('catalogo')])
                           @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Producto Destacado 2', 'productBrand' => 'Helin', 'productPrice' => 149.00, 'productBadge' => ''])
                           @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Producto Destacado 3', 'productBrand' => 'Helin', 'productPrice' => 89.00, 'productOldPrice' => 120.00, 'productBadge' => 'Oferta'])
                           @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Producto Destacado 4', 'productBrand' => 'Helin', 'productPrice' => 45.00, 'productBadge' => ''])
                       @endif
                   </div>
               </div>
           </section>
       @endif
   @endforeach

   <!-- Destacados en Instrumentos y Equipos -->
   <section class="py-12 sm:py-16">
       <div class="container mx-auto px-4">
           <div class="section-title flex items-end justify-between gap-5 mb-5">
               <div>
                   <h2 class="text-2xl lg:text-3xl leading-none mb-1" style="letter-spacing: 0;">Destacados en Instrumentos y Equipos</h2>
                   <p class="text-helin-text text-sm mt-1">Explora instrumentos y equipos diseñados para aportar precisión y eficiencia a la práctica odontológica.</p>
               </div>
               <a href="{{ route('catalogo', ['category' => 'equipos-odontologicos']) }}" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">Ver todos los productos →</a>
           </div>
           <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
               @include('web.components.product-card', [
                   'productImage' => asset('images/im1.png'),
                   'productName' => 'Instrumento Destacado 1',
                   'productBrand' => 'Helin',
                   'productPrice' => 0.00,
                   'productOldPrice' => null,
                   'productBadge' => 'Nuevo',
                   'productLink' => route('catalogo', ['category' => 'equipos-odontologicos']),
                   'productSlug' => 'instrumento-destacado-1'
               ])
               @include('web.components.product-card', [
                   'productImage' => asset('images/im2.png'),
                   'productName' => 'Instrumento Destacado 2',
                   'productBrand' => 'Helin',
                   'productPrice' => 0.00,
                   'productOldPrice' => null,
                   'productBadge' => '',
                   'productLink' => route('catalogo', ['category' => 'equipos-odontologicos']),
                   'productSlug' => 'instrumento-destacado-2'
               ])
               @include('web.components.product-card', [
                   'productImage' => asset('images/im3.png'),
                   'productName' => 'Instrumento Destacado 3',
                   'productBrand' => 'Helin',
                   'productPrice' => 0.00,
                   'productOldPrice' => null,
                   'productBadge' => 'Oferta',
                   'productLink' => route('catalogo', ['category' => 'equipos-odontologicos']),
                   'productSlug' => 'instrumento-destacado-3'
               ])
               @include('web.components.product-card', [
                   'productImage' => asset('images/im4.png'),
                   'productName' => 'Instrumento Destacado 4',
                   'productBrand' => 'Helin',
                   'productPrice' => 0.00,
                   'productOldPrice' => null,
                   'productBadge' => '',
                   'productLink' => route('catalogo', ['category' => 'equipos-odontologicos']),
                   'productSlug' => 'instrumento-destacado-4'
               ])
           </div>
       </div>
   </section>

   <!-- Testimonios -->
   <section class="testimonials container mx-auto px-4 mt-14 rounded-3xl p-9" style="
      background: linear-gradient(135deg,#fff 0%, #f4f7f8 100%);
      border-radius: 34px;
      padding: 36px;
      ">
      <div class="test-head flex items-end justify-between gap-6 mb-8">
         <div>
            @if($testimonialsSection && $testimonialsSection->status == 1 && $testimonialsSection->status_content == 1)
                <small class="text-turquesa font-black text-xs">{{ $testimonialsSection->subtitle ?? 'Testimonios' }}</small>
                <h2 class="text-3xl lg:text-4xl leading-none mt-1" style="letter-spacing: 0;">{!! $testimonialsSection->title !!}</h2>
            @else
                <!-- Fallback hardcoded -->
                <small class="text-turquesa font-black text-xs">Testimonios</small>
                <h2 class="text-3xl lg:text-4xl leading-none mt-1" style="letter-spacing: 0;">Lo que dicen<br>nuestros clientes</h2>
            @endif
         </div>
         <div class="arrows flex gap-3">
            <button id="testimonialPrev" class="arrow w-12 h-12 rounded-full border-0 bg-turquesa text-white text-2xl font-black hover:bg-turquesa/90 transition-all hover:scale-105">←</button>
            <button id="testimonialNext" class="arrow w-12 h-12 rounded-full border-0 bg-turquesa text-white text-2xl font-black hover:bg-turquesa/90 transition-all hover:scale-105">→</button>
         </div>
      </div>
      <div class="testimonial-carousel overflow-hidden -mx-2.5">
         <div id="testimonialTrack" class="testimonial-track flex transition-transform duration-500 ease-in-out px-2.5">
             @php
                 $duplicatedTestimonial = null;
             @endphp
             @foreach($testimonials as $testimonial)
                 @php
                     $authorName = strtolower($testimonial->name ?? '');
                     $testimonialImage = '';
                     if (str_contains($authorName, 'maría fernanda lópez')) {
                         $testimonialImage = asset('images/dra_test.png');
                     } elseif (str_contains($authorName, 'josé andrés rivas')) {
                         $testimonialImage = asset('images/dr_test.png');
                         $duplicatedTestimonial = $testimonial;
                     } elseif (str_contains($authorName, 'sorrisa') || str_contains($authorName, 'sonrisa') || str_contains($authorName, 'integral')) {
                         $testimonialImage = asset('images/clinica_test.png');
                     }
                 @endphp
                 <div class="testimonial-slide w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-2.5">
                     @include('web.components.testimonial-card', [
                         'testimonialText' => $testimonial->content,
                         'testimonialAuthor' => $testimonial->name,
                         'testimonialTitle' => $testimonial->specialty,
                         'testimonialImage' => $testimonialImage
                     ])
                 </div>
             @endforeach
             @if($duplicatedTestimonial)
                 <div class="testimonial-slide w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-2.5">
                     @include('web.components.testimonial-card', [
                         'testimonialText' => $duplicatedTestimonial->content,
                         'testimonialAuthor' => $duplicatedTestimonial->name,
                         'testimonialTitle' => $duplicatedTestimonial->specialty,
                         'testimonialImage' => asset('images/dr_test.png')
                     ])
                 </div>
             @endif
         </div>
      </div>
   </section>

   <!-- Sección de Opinión -->
   <div class="container mx-auto px-4">
      @include('web.partials.opinion')
   </div>
</main>

@push('scripts')
<script src="{{ asset('helin/js/home.js') }}"></script>
<script>
// Ocultar skeleton y mostrar categorías cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const skeleton = document.getElementById('categoriesSkeleton');
        const grid = document.getElementById('categoriesGrid');

        if (skeleton && grid) {
            skeleton.style.display = 'none';
            grid.classList.remove('hidden');
        }
    }, 800); // Delay para simular carga inicial

    // Carrusel de testimonios (infinito)
    const track = document.getElementById('testimonialTrack');
    const prevBtn = document.getElementById('testimonialPrev');
    const nextBtn = document.getElementById('testimonialNext');

    if (track && prevBtn && nextBtn) {
        const realSlides = Array.from(track.querySelectorAll('.testimonial-slide'));
        if (realSlides.length === 0) return;

        function getItemsVisible() {
            if (window.innerWidth >= 1024) return 3;
            if (window.innerWidth >= 768) return 2;
            return 1;
        }

        // Construir carrusel infinito clonando slides
        let itemsVisible = getItemsVisible();
        function buildInfiniteTrack() {
            track.innerHTML = '';
            // Clonar últimos N al inicio
            realSlides.slice(-itemsVisible).forEach(slide => {
                const clone = slide.cloneNode(true);
                clone.dataset.clone = 'start';
                track.appendChild(clone);
            });
            // Slides reales
            realSlides.forEach(slide => track.appendChild(slide.cloneNode(true)));
            // Clonar primeros N al final
            realSlides.slice(0, itemsVisible).forEach(slide => {
                const clone = slide.cloneNode(true);
                clone.dataset.clone = 'end';
                track.appendChild(clone);
            });
        }
        buildInfiniteTrack();

        let currentIndex = itemsVisible;
        let isTransitioning = false;

        function getSlideWidth() {
            return 100 / itemsVisible;
        }

        function moveTo(index, animate = true) {
            if (animate) {
                track.style.transition = 'transform 0.5s ease-in-out';
            } else {
                track.style.transition = 'none';
            }
            const slideWidth = getSlideWidth();
            track.style.transform = `translateX(-${index * slideWidth}%)`;
        }

        function handleNext() {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex++;
            moveTo(currentIndex);
        }

        function handlePrev() {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex--;
            moveTo(currentIndex);
        }

        function onTransitionEnd() {
            const totalSlides = track.children.length;
            // Si estamos en los clones del final, saltar al inicio real
            if (currentIndex >= totalSlides - itemsVisible) {
                currentIndex = itemsVisible;
                moveTo(currentIndex, false);
            }
            // Si estamos en los clones del inicio, saltar al final real
            if (currentIndex < itemsVisible) {
                currentIndex = totalSlides - itemsVisible - itemsVisible;
                moveTo(currentIndex, false);
            }
            setTimeout(() => { isTransitioning = false; }, 20);
        }

        track.addEventListener('transitionend', onTransitionEnd);
        track.addEventListener('webkitTransitionEnd', onTransitionEnd);

        nextBtn.addEventListener('click', handleNext);
        prevBtn.addEventListener('click', handlePrev);

        function onResize() {
            const newItemsVisible = getItemsVisible();
            if (newItemsVisible !== itemsVisible) {
                itemsVisible = newItemsVisible;
                buildInfiniteTrack();
                currentIndex = itemsVisible;
                moveTo(currentIndex, false);
            }
        }

        window.addEventListener('resize', onResize);
        moveTo(currentIndex, false);
    }
});
</script>
@endpush
@endsection
