@extends('web.layouts.app')

@section('title', 'Helin - Material Dental de Calidad')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/home.css') }}">
@endsection

@section('content')
<main>
   <!-- Hero Section -->
   <section class="hero relative overflow-hidden" style="
      background: radial-gradient(circle at 78% 18%, rgba(255,255,255,.42), transparent 22%),
      linear-gradient(135deg, #6BC2C3 0%, #97d5d4 100%);
      color: #fff;
      position: relative;
      overflow: hidden;
      ">
      @php
          $heroSection = \App\Models\Sections::find(\App\Models\Sections::HERO_HOME);
      @endphp
      <div class="hero-inner relative max-w-6xl mx-auto px-6 py-16 lg:py-20" style="
         position: relative;
         display: grid;
         grid-template-columns: auto 1fr;
         gap: 2rem;
         align-items: center;
         ">
         <!-- Hero Badges -->
         @if($heroSection && $heroSection->status_content)
             @php
                 $items = $heroSection->items ? json_decode($heroSection->items, true) : [];
                 $heroBadges = $items['hero_badges'] ?? [];
             @endphp
             @if($heroSection->layout_type === 'hero_badges' && !empty($heroBadges))
                 <aside class="hero-badges hidden lg:block">
                     <div class="flex flex-col gap-4">
                         @foreach($heroBadges as $badge)
                             <div class="hero-badge flex items-center gap-2 text-[#123F4A] text-xs font-bold uppercase leading-tight">
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
            <div class="brand text-4xl lg:text-5xl font-black tracking-tight leading-none mb-3" style="letter-spacing: 0;">helin.</div>
            <small class="block text-xs font-black uppercase tracking-wide mb-3 text-[#123F4A]">Soluciones que cuidan.</small>
            <h1 class="text-5xl lg:text-7xl leading-tight mb-4" style="letter-spacing: 0;">
               TODO EN CIRUGÍA <br>
               ODONTOLÓGICA <br>
               <span style="color: #123F4A;"> ESPECIALIZADA.</span>
            </h1>
            <p class="text-white/90 text-base lg:text-lg font-body font-normal mb-6 max-w-2xl mx-auto lg:mx-0">Instrumental, insumos y soluciones diseñadas para<br>procedimientos quirúrgicos seguros, precisos y eficientes.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mt-8">
               <a href="{{ route('catalogo') }}" class="inline-flex items-center justify-center gap-2 h-12 px-8 rounded-full bg-white text-turquesa text-sm font-black shadow-xl hover:shadow-2xl transition-all hover:scale-105" style="box-shadow: 0 16px 30px rgba(15,47,67,.16);">
               Ir a productos →
               </a>
               <a href="{{ route('solicitud') }}" class="inline-flex items-center justify-center h-12 px-8 rounded-full border-2 border-white text-white text-sm font-black hover:bg-white/10 transition-all hover:scale-105">
               Hablar con un asesor
               </a>
            </div>
         </div>
      </div>
      <!-- Hero Visual Background -->
      <div class="hero-visual absolute inset-0 pointer-events-none">
         <div class="medical-scene absolute inset-0 rounded-3xl overflow-hidden opacity-30" style="
            background: radial-gradient(circle at 55% 20%, #fff 0 0, transparent 30%),
            linear-gradient(135deg, rgba(255,255,255,.25), rgba(255,255,255,.08));
            border: 1px solid rgba(255,255,255,.35);
            "></div>
      </div>
   </section>

   @include('web.partials.beneficios')

   <!-- Categorías Destacadas -->
   <section class="py-12 sm:py-16">
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
                  <a href="{{ route('catalogo', ['category' => 'implantes']) }}" class="text-link text-turquesa font-black text-sm">Ver categoría →</a>
               </div>
               <div class="implant-visual relative h-32 hidden md:block">
                  <div class="implant absolute bottom-2 left-4 w-12 h-32 rounded-2xl bg-gradient-to-r from-gray-300 to-white to-gray-400 transform rotate-12" style="
                     box-shadow: 72px -18px 0 -7px #c9d4d7, 152px 10px 0 2px #f2ffff;
                     "></div>
                  <div class="kit absolute right-2 bottom-0 w-48 h-28 rounded-2xl bg-turquesa/40 border-4 border-turquesa/25"></div>
               </div>
            </article>
            <!-- Grid de Categorías -->
            <div class="category-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
   <div class="container mx-auto px-4">
      @include('web.partials.near')
   </div>

   @php
       $productSections = \App\Models\Sections::where('status', 1)
           ->whereIn('id', [\App\Models\Sections::IMPLANTOLOGY_PRODUCTS, \App\Models\Sections::GBR_PRODUCTS, \App\Models\Sections::INSTRUMENTS_PRODUCTS])
           ->orderBy('id')
           ->get();

       // Mapear secciones a categorías
       $sectionCategories = [
           \App\Models\Sections::IMPLANTOLOGY_PRODUCTS => ['name' => 'Implantes', 'slug' => 'implantes'],
           \App\Models\Sections::GBR_PRODUCTS         => ['name' => 'Regeneración Guiada Bucal (GBR)', 'slug' => 'regeneracion-guiada-bucal-gbr'],
           \App\Models\Sections::INSTRUMENTS_PRODUCTS => ['name' => 'Instrumentos', 'slug' => 'tijeras'],
       ];
   @endphp

   @foreach($productSections as $index => $section)
       @if($section->status_content)
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
                           <h2 class="text-2xl lg:text-3xl leading-none mb-1" style="letter-spacing: 0;">{{ $section->title }}</h2>
                           @php
                               $description = trim(strip_tags($section->content));
                               $firstLine = explode("\n", $description)[0];
                           @endphp
                           <p class="text-helin-text text-sm mt-1">{{ $firstLine }}</p>
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
                                   'productImage' => asset('storage/products/73432-21300078.webp'),
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

   <!-- Testimonios -->
   <section class="testimonials container mx-auto px-4 mt-14 rounded-3xl p-9" style="
      background: linear-gradient(135deg,#fff 0%, #f4f7f8 100%);
      border-radius: 34px;
      padding: 36px;
      ">
      <div class="test-head flex items-end justify-between gap-6 mb-8">
         <div>
            <small class="text-turquesa font-black text-xs">Testimonios</small>
            <h2 class="text-3xl lg:text-4xl leading-none mt-1" style="letter-spacing: 0;">Lo que dicen<br>nuestros clientes</h2>
         </div>
         <div class="arrows flex gap-3">
            <button class="arrow w-12 h-12 rounded-full border-0 bg-turquesa text-white text-2xl font-black hover:bg-turquesa/90 transition-all hover:scale-105">←</button>
            <button class="arrow w-12 h-12 rounded-full border-0 bg-turquesa text-white text-2xl font-black hover:bg-turquesa/90 transition-all hover:scale-105">→</button>
         </div>
      </div>
      <div class="testimonial-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
         @php
             $testimonials = \App\Models\Testimonial::where('is_active', true)->orderBy('position', 'asc')->take(3)->get();
         @endphp
         @foreach($testimonials as $testimonial)
             @include('web.components.testimonial-card', [
                 'testimonialText' => $testimonial->content,
                 'testimonialAuthor' => $testimonial->name,
                 'testimonialTitle' => $testimonial->specialty
             ])
         @endforeach
      </div>
   </section>

   <!-- Sección de Opinión -->
   <div class="container mx-auto px-4">
      @include('web.partials.opinion')
   </div>
</main>
@endsection
