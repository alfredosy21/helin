@extends('web.layouts.app')

@section('title', $pageSeo?->seo_title ?? 'Helin - Material Dental de Calidad')
@section('meta-description', $pageSeo?->seo_description ?? 'Helin - Soluciones odontológicas especializadas en implantes, instrumentos y biomateriales. Calidad garantizada para profesionales de la salud bucal en Venezuela.')
@section('meta-keywords', $pageSeo?->seo_keywords ?? 'implantes dentales, material dental, instrumentos odontológicos, biomateriales, cirugía guiada, helin, productos odontológicos Venezuela')
@section('og-type', 'website')
@section('og-image', $pageSeo?->og_image ? asset('storage/' . $pageSeo->og_image) : asset('images/helin-home-og.jpg'))

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
            <div class="hero-inner relative max-w-6xl mx-auto px-5 sm:px-6 py-10 sm:py-14 lg:py-20 grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-6 lg:gap-8 items-center">
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
            <h1 class="text-4xl sm:text-5xl lg:text-7xl leading-tight mb-3 sm:mb-4" style="letter-spacing: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.25);">
               {!! $heroSection->content !!}
            </h1>
            @if($heroSection->description)
                <p class="text-white text-sm sm:text-base lg:text-lg font-body font-normal mb-4 sm:mb-6 max-w-2xl mx-auto lg:mx-0" style="text-shadow: 0 1px 3px rgba(0,0,0,0.3);">{{ $heroSection->description }}</p>
            @endif
            @if($heroSection->buttons)
                @php
                    $buttons = json_decode($heroSection->buttons, true);
                @endphp
                <div class="grid grid-cols-1 w-fit mx-auto gap-3 sm:flex sm:flex-row sm:w-auto sm:mx-0 sm:gap-4 sm:items-center justify-center lg:justify-start mt-5 sm:mt-8">
                    @foreach($buttons as $button)
                        @if($button['style'] === 'primary')
                            <a href="{{ $button['url'] === 'catalogo' ? route('catalogo') : ($button['url'] === 'contactanos' ? route('contactanos') : $button['url']) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-10 px-5 sm:h-12 sm:px-8 rounded-full bg-white text-turquesa text-xs sm:text-sm font-black shadow-xl hover:shadow-2xl transition-all hover:scale-105" style="box-shadow: 0 16px 30px rgba(15,47,67,.16);">
                            {{ $button['text'] }}
                            </a>
                        @else
                            <a href="{{ $button['url'] === 'catalogo' ? route('catalogo') : ($button['url'] === 'contactanos' ? route('contactanos') : $button['url']) }}" class="w-full sm:w-auto inline-flex items-center justify-center h-10 px-5 sm:h-12 sm:px-8 rounded-full border-2 border-white text-white text-xs sm:text-sm font-black hover:bg-white/10 transition-all hover:scale-105">
                            {{ $button['text'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 w-fit mx-auto gap-3 sm:flex sm:flex-row sm:w-auto sm:mx-0 sm:gap-4 sm:items-center justify-center lg:justify-start mt-5 sm:mt-8">
                   <a href="{{ route('catalogo') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-10 px-5 sm:h-12 sm:px-8 rounded-full bg-white text-turquesa text-xs sm:text-sm font-black shadow-xl hover:shadow-2xl transition-all hover:scale-105" style="box-shadow: 0 16px 30px rgba(15,47,67,.16);">
                   Ir a productos →
                   </a>
                   <a href="{{ route('contactanos') }}" class="w-full sm:w-auto inline-flex items-center justify-center h-10 px-5 sm:h-12 sm:px-8 rounded-full border-2 border-white text-white text-xs sm:text-sm font-black hover:bg-white/10 transition-all hover:scale-105">
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
   <section class="pt-6 sm:pt-8">
      <div class="container mx-auto px-4">
         <div class="categories bg-white border border-helin-border rounded-3xl p-6 shadow-lg mb-8" style="box-shadow: 0 18px 45px rgba(15,47,67,.08);">
            <!-- Categoría Destacada -->
            @php
                $featuredCategory = \Illuminate\Support\Facades\Schema::hasColumn('categories', 'is_featured')
                    ? \App\Models\Category::where('is_featured', true)->where('is_active', true)->first()
                    : null;

                if (!$featuredCategory) {
                    $featuredCategory = \App\Models\Category::where('slug', 'implantologia')->where('is_active', true)->first()
                        ?? \App\Models\Category::where('is_active', true)->orderBy('order')->first();
                }
            @endphp
            @if($featuredCategory)
            <article class="category-featured relative min-h-[200px] rounded-2xl mb-4 border border-helin-border overflow-hidden">
               @if($featuredCategory->image)
               <img src="{{ asset('storage/' . $featuredCategory->image) }}" alt="{{ $featuredCategory->name }}" class="category-featured-bg hidden md:block">
               @else
               @php $homeSettings = \App\Models\Settings::getSettings(); @endphp
               <img src="{{ $homeSettings && $homeSettings->default_category_image ? asset('storage/' . $homeSettings->default_category_image) : asset('images/categoria1.png') }}" alt="{{ $featuredCategory->name }}" class="category-featured-bg hidden md:block">
               @endif
               <div class="category-featured-content">
                  <small class="block text-turquesa text-xs font-black mb-2">{{ $featuredCategory->banner_title ?? 'Soluciones especializadas' }}</small>
                  <h2 class="text-3xl lg:text-4xl leading-none mb-4" style="letter-spacing: 0;">{{ $featuredCategory->name }}</h2>
                  <a href="{{ route('catalogo', ['category' => $featuredCategory->slug]) }}" class="text-link">Ver categoría →</a>
               </div>
            </article>
            @endif
            <!-- Skeleton Loader para Categorías -->
            <div id="categoriesSkeleton" class="skeleton-grid skeleton-grid-responsive">
               @for($i = 1; $i <= 6; $i++)
                  @include('web.components.skeleton-category')
               @endfor
            </div>

            <!-- Grid de Categorías -->
            <div id="categoriesGrid" class="category-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 hidden">
               @php
                   $categoryCards = \App\Models\Category::where('is_active', true)
                       ->whereNull('parent_id')
                       ->orderBy('order')
                       ->take(6)
                       ->get();
                   $homeSettings = \App\Models\Settings::getSettings();
                   $homeDefaultCategoryImage = $homeSettings && $homeSettings->default_category_image ? asset('storage/' . $homeSettings->default_category_image) : asset('images/cat2.png');
               @endphp
@forelse($categoryCards as $cardIndex => $categoryCard)
                    @include('web.components.category-card', [
                        'categorySubtitle' => $categoryCard->banner_title ?: 'Productos Helin',
                        'categoryTitle' => $categoryCard->name,
                        'categoryLink' => route('catalogo', ['category' => $categoryCard->slug]),
                        'categoryImage' => $categoryCard->image ? asset('storage/' . $categoryCard->image) : $homeDefaultCategoryImage,
                    ])
               @empty
                   <p class="text-sm text-helin-text col-span-full">No hay categorías disponibles.</p>
               @endforelse
            </div>
         </div>
      </div>
   </section>

   <!-- Sección "Estamos cerca de ti" -->
   <div class="container mx-auto px-4" style="padding-bottom: 20px;">
      <div class="mb-8">
         @include('web.partials.near')
      </div>

      <!-- Flow Highlight Section -->
      <section class="flow-highlight">
                @php
                    $flowSettings = \App\Models\Settings::getSettings();
                    $flowWhatsApp = ($flowSettings && !empty($flowSettings->valencia_whatsapp)) ? preg_replace('/[^0-9]/', '', $flowSettings->valencia_whatsapp) : null;
                    $flowJson = $howToSection ? (json_decode($howToSection->items, true) ?: []) : [];
                    $flowSteps = $flowJson['steps'] ?? $flowJson['items'] ?? $flowJson;
                    $stepThreeUrl = $flowWhatsApp
                        ? 'https://api.whatsapp.com/send/?phone=' . $flowWhatsApp . '&text=' . urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.')
                        : route('contactanos');
                @endphp
                <aside class="how-card">
                   <h3>{{ $howToSection->title ?? '¿Cómo solicitar productos Helin?' }}</h3>
                   @if(count($flowSteps) > 0)
                       @foreach($flowSteps as $stepIndex => $step)
                           @php
                               $stepNum = $step['number'] ?? ($stepIndex + 1);
                               $stepUrl = $step['url'] ?? (($stepNum == 1) ? route('catalogo') : (($stepNum == 2) ? route('carrito') : $stepThreeUrl));
                           @endphp
                           <div class="step">
                              <a href="{{ $stepUrl }}" target="{{ ($stepNum == 3) ? '_blank' : '_self' }}" rel="{{ ($stepNum == 3) ? 'noopener noreferrer' : '' }}" class="hover:text-[#123F4A] transition-colors"><b>✓</b></a>
                              <div><strong>{{ $step['title'] ?? '' }}</strong><span>{{ $step['description'] ?? '' }}</span></div>
                              <div class="number">{{ $stepNum }}</div>
                           </div>
                       @endforeach
                   @else
                   <div class="step">
                      <a href="{{ route('catalogo') }}" class="hover:text-[#123F4A] transition-colors"><b>✓</b></a>
                      <div><strong>Selecciona tus productos</strong><span>Explora el catálogo Helin y elige los productos que necesitas.</span></div>
                      <div class="number">1</div>
                   </div>
                   <div class="step">
                      <a href="/carrito" class="hover:text-[#123F4A] transition-colors"><b>✓</b></a>
                      <div><strong>Arma tu carrito</strong><span>Agrega cantidades y revisa el resumen de tu solicitud comercial.</span></div>
                      <div class="number">2</div>
                   </div>
                   <div class="step">
                      <a href="{{ $stepThreeUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#123F4A] transition-colors"><b>✓</b></a>
                      <div><strong>Contacta a tu ejecutivo</strong><span>Envía la solicitud por WhatsApp al ejecutivo asignado según tu zona.</span></div>
                      <div class="number">3</div>
                   </div>
                   @endif
                 </aside>

         <div class="featured-products">
           <div class="featured-head">
             <h3>Destacados <span style="color:var(--helin)">Helin</span></h3>
             <a href="{{ route('catalogo', ['featured' => '1']) }}" class="crumb">VER TODOS LOS PRODUCTOS →</a>
           </div>

                      <div class="mini-grid">
                @forelse($featuredProducts as $product)
                @include('web.components.product-card', [
                    'productImage' => $product->main_image_url,
                    'productName' => $product->name,
                    'productBrand' => $product->brand->name ?? 'Helin',
                    'productPrice' => $product->is_on_sale && $product->sale_price ? $product->sale_price : $product->price,
                    'productOldPrice' => $product->is_on_sale && $product->sale_price ? $product->price : null,
                    'productBadge' => $product->is_new ? 'Nuevo' : ($product->is_on_sale ? 'Oferta' : ''),
                    'productLink' => route('producto', ['slug' => $product->slug]),
                    'productSlug' => $product->slug
                ])
                @empty
                    <p class="text-sm text-helin-text col-span-full">No hay productos destacados disponibles.</p>
                @endforelse
           </div>
         </div>
      </section>
   </div>


   @foreach($productSections as $index => $section)
        @if($section->status == 1 && $section->status_content == 1)
            @php
                $categorySlug = $section->category_slug;
                $category     = $categorySlug ? (\App\Models\Category::where('slug', $categorySlug)->where('is_active', true)->first()) : null;
               $products     = $category ? \App\Models\Product::where('category_id', $category->id)
                   ->where('is_active', true)
                   ->with('images')
                   ->inRandomOrder()
                   ->take(4)
                   ->get() : collect();
           @endphp
           <section class="py-8 sm:py-16 {{ $index % 2 == 0 ? 'bg-helin-soft' : '' }}">
               <div class="container mx-auto px-4">
<div class="section-title flex items-end justify-between gap-5 mb-5">
                        <div>
                            @php
                                $sectionDescription = trim(strip_tags($section->content));
                                $sectionFirstLine = explode("\n", $sectionDescription)[0];
                            @endphp
                            <h2 class="text-2xl lg:text-3xl leading-none mb-1" style="letter-spacing: 0;">{{ $section->title }}</h2>
                            @if($sectionFirstLine)
                                <p class="text-helin-text text-sm mt-1">{{ $sectionFirstLine }}</p>
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
                                @endphp
                                @include('web.components.product-card', [
                                    'productImage' => $product->main_image_url,
                                    'productName' => $product->name,
                                    'productBrand' => $product->brand->name ?? 'Helin',
                                    'productPrice' => $product->is_on_sale && $product->sale_price ? $product->sale_price : $product->price,
                                    'productOldPrice' => $product->is_on_sale && $product->sale_price ? $product->price : null,
                                    'productBadge' => $badge,
                                    'productLink' => route('producto', ['slug' => $product->slug]),
                                    'productSlug' => $product->slug,
                                ])
                            @endforeach
                        @endif
                    </div>
               </div>
           </section>
       @endif
   @endforeach

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
             @foreach($testimonials as $testimonial)
                 <div class="testimonial-slide w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-2.5">
                     @include('web.components.testimonial-card', [
                         'testimonialText' => $testimonial->content,
                         'testimonialAuthor' => $testimonial->name,
                         'testimonialTitle' => $testimonial->specialty,
                         'testimonialImage' => $testimonial->image ? asset('storage/' . $testimonial->image) : null
                     ])
                 </div>
             @endforeach
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
@endpush
@endsection
