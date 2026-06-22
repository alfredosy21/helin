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
      linear-gradient(135deg, #34b1b5 0%, #97d5d4 100%);
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
         <aside class="hero-badges hidden lg:block">
            <div class="flex flex-col gap-4">
               <div class="hero-badge flex items-center gap-2 text-white/92 text-xs font-bold uppercase leading-tight">
                  <span class="mini-icon w-10 h-10 border border-white/55 rounded-xl flex items-center justify-center bg-white/12 text-lg">✓</span>
                  <span>Calidad<br>garantizada</span>
               </div>
               <div class="hero-badge flex items-center gap-2 text-white/92 text-xs font-bold uppercase leading-tight">
                  <span class="mini-icon w-10 h-10 border border-white/55 rounded-xl flex items-center justify-center bg-white/12 text-lg">△</span>
                  <span>Alta<br>precisión</span>
               </div>
               <div class="hero-badge flex items-center gap-2 text-white/92 text-xs font-bold uppercase leading-tight">
                  <span class="mini-icon w-10 h-10 border border-white/55 rounded-xl flex items-center justify-center bg-white/12 text-lg">◎</span>
                  <span>Soluciones<br>quirúrgicas</span>
               </div>
               <div class="hero-badge flex items-center gap-2 text-white/92 text-xs font-bold uppercase leading-tight">
                  <span class="mini-icon w-10 h-10 border border-white/55 rounded-xl flex items-center justify-center bg-white/12 text-lg">✚</span>
                  <span>Asesoría<br>especializada</span>
               </div>
            </div>
         </aside>
         <!-- Hero Copy -->
         <div class="hero-copy text-center lg:text-left">
            <div class="brand text-4xl lg:text-5xl font-black tracking-tight leading-none mb-3" style="letter-spacing: -0.07em;">helin.</div>
            <small class="block text-xs font-black uppercase tracking-wide mb-3 text-white/90">Soluciones que cuidan.</small>
            <h1 class="text-3xl lg:text-5xl font-bold leading-tight mb-4" style="letter-spacing: -0.055em;">Todo en cirugía odontológica especializada.</h1>
            <p class="text-white/90 text-base lg:text-lg font-medium mb-6 max-w-2xl mx-auto lg:mx-0">Instrumental, insumos y soluciones diseñadas para procedimientos quirúrgicos seguros, precisos y eficientes.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
               <a href="{{ route('web.catalogo') }}" class="inline-flex items-center justify-center gap-2 h-12 px-8 rounded-full bg-white text-turquesa text-sm font-black shadow-xl hover:shadow-2xl transition-all hover:scale-105" style="box-shadow: 0 16px 30px rgba(15,47,67,.16);">
               Ver Catálogo →
               </a>
               <a href="{{ route('web.solicitud') }}" class="inline-flex items-center justify-center h-12 px-8 rounded-full border-2 border-white text-white text-sm font-black hover:bg-white/10 transition-all hover:scale-105">
               Solicitar Cotización
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
         <div class="categories bg-white border border-gray-200 rounded-3xl p-6 shadow-lg mb-8" style="box-shadow: 0 18px 45px rgba(15,47,67,.08);">
            <!-- Categoría Destacada -->
            <article class="category-featured relative min-h-[200px] rounded-2xl bg-gradient-to-r from-gray-50 to-white/78 p-8 mb-4 border border-gray-200 overflow-hidden grid md:grid-cols-2 gap-6 items-center" style="
               background: linear-gradient(90deg, #f8ffff 0%, rgba(255,255,255,.78) 48%, rgba(151,213,212,.22)),
               radial-gradient(circle at 76% 40%, rgba(52,177,181,.12), transparent 26%);
               ">
               <div>
                  <small class="block text-turquesa text-xs font-black mb-2">Soluciones especializadas</small>
                  <h2 class="text-3xl lg:text-4xl font-bold leading-none mb-4" style="letter-spacing: -0.05em;">Implantología</h2>
                  <a href="{{ route('web.catalogo') }}" class="text-link text-turquesa font-black text-sm">Ver categoría →</a>
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
               @include('web.components.category-card', ['categorySubtitle' => 'Recuperación y soporte', 'categoryTitle' => 'Regeneración ósea guiada', 'categoryLink' => route('web.catalogo')])
               @include('web.components.category-card', ['categorySubtitle' => 'Fijación y precisión', 'categoryTitle' => 'Osteosíntesis', 'categoryLink' => route('web.catalogo')])
               @include('web.components.category-card', ['categorySubtitle' => 'Bienestar oral', 'categoryTitle' => 'Cuidado Bucal', 'categoryLink' => route('web.catalogo')])
               @include('web.components.category-card', ['categorySubtitle' => 'Precisión clínica', 'categoryTitle' => 'Instrumentos', 'categoryLink' => route('web.catalogo')])
               @include('web.components.category-card', ['categorySubtitle' => 'Tecnología para tu práctica', 'categoryTitle' => 'Equipos', 'categoryLink' => route('web.catalogo')])
               @include('web.components.category-card', ['categorySubtitle' => 'Diagnóstico y exactitud', 'categoryTitle' => 'Planificación Digital', 'categoryLink' => route('web.catalogo')])
            </div>
         </div>
      </div>
   </section>

   <!-- Sección "Estamos cerca de ti" -->
   <div class="container mx-auto px-4">
      @include('web.partials.near')
   </div>

   <!-- Productos Destacados -->
   <section class="py-12 sm:py-16 bg-gray-100">
      <div class="container mx-auto px-4">
         <div class="section-title flex items-end justify-between gap-5 mb-5">
            <div>
               <h2 class="text-2xl lg:text-3xl font-bold leading-none mb-1" style="letter-spacing: -0.045em;">Más vendidos en Implantología</h2>
               <p class="text-gray-500 text-sm mt-1">Selección de productos destacados</p>
            </div>
            <a href="{{ route('web.catalogo') }}" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">Ver todos los productos →</a>
         </div>
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Implante', 'productName' => 'Implante Dental Straumann BLX', 'productBrand' => 'Straumann', 'productPrice' => 299.00, 'productBadge' => 'Nuevo', 'productLink' => route('web.producto')])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Biomaterial', 'productName' => 'Biomaterial Óseo Bio-Oss', 'productBrand' => 'Geistlich', 'productPrice' => 149.00, 'productBadge' => ''])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Kit', 'productName' => 'Kit de Cirugía Básico', 'productBrand' => 'Helin', 'productPrice' => 89.00, 'productOldPrice' => 120.00, 'productBadge' => 'Oferta'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Suturas', 'productName' => 'Suturas Resorbibles 4-0', 'productBrand' => 'Johnson & Johnson', 'productPrice' => 45.00, 'productBadge' => ''])
         </div>
      </div>
   </section>

   <!-- Productos Nuevos -->
   <section class="py-12 sm:py-16">
      <div class="container mx-auto px-4">
         <div class="section-title flex items-end justify-between gap-5 mb-5">
            <div>
               <h2 class="text-2xl lg:text-3xl font-bold leading-none mb-1" style="letter-spacing: -0.045em;">Más vendidos en Regeneración Ósea Guiada</h2>
               <p class="text-gray-500 text-sm mt-1">Biomateriales y soluciones especializadas</p>
            </div>
            <a href="{{ route('web.catalogo') }}" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">Ver todos los productos →</a>
         </div>
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Membrana', 'productName' => 'Membrana Colágeno Bio-Gide', 'productBrand' => 'Geistlich', 'productPrice' => 89.00, 'productBadge' => 'Nuevo'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Tornillos', 'productName' => 'Tornillos de Osteosíntesis', 'productBrand' => 'Stryker', 'productPrice' => 75.00, 'productBadge' => 'Nuevo'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Placas', 'productName' => 'Placas de Reconstrucción', 'productBrand' => 'Stryker', 'productPrice' => 120.00, 'productBadge' => 'Nuevo'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Pinzas', 'productName' => 'Pinzas de Elevación', 'productBrand' => 'Hu-Friedy', 'productPrice' => 95.00, 'productBadge' => 'Nuevo'])
         </div>
      </div>
   </section>

   <!-- Productos en Oferta -->
   <section class="py-12 sm:py-16 bg-gray-100">
      <div class="container mx-auto px-4">
         <div class="section-title flex items-end justify-between gap-5 mb-5">
            <div>
               <h2 class="text-2xl lg:text-3xl font-bold leading-none mb-1" style="letter-spacing: -0.045em;">Más vendidos en Instrumentos y Equipos</h2>
               <p class="text-gray-500 text-sm mt-1">Precisión clínica para tu práctica</p>
            </div>
            <a href="{{ route('web.catalogo') }}" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">Ver todos los productos →</a>
         </div>
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Implante+2', 'productName' => 'Implante Nobel Active', 'productBrand' => 'Nobel Biocare', 'productPrice' => 249.00, 'productOldPrice' => 320.00, 'productBadge' => 'Oferta'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Kit+Avanzado', 'productName' => 'Kit de Cirugía Avanzado', 'productBrand' => 'Helin', 'productPrice' => 159.00, 'productOldPrice' => 200.00, 'productBadge' => 'Oferta'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Cinceles', 'productName' => 'Set de Cinceles', 'productBrand' => 'Hu-Friedy', 'productPrice' => 65.00, 'productOldPrice' => 85.00, 'productBadge' => 'Oferta'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Separadores', 'productName' => 'Separadores de Muelas', 'productBrand' => 'Hu-Friedy', 'productPrice' => 35.00, 'productOldPrice' => 50.00, 'productBadge' => 'Oferta'])
         </div>
      </div>
   </section>

   <!-- Testimonios -->
   <section class="testimonials mt-14 rounded-3xl p-9" style="
      background: linear-gradient(135deg,#fff 0%, #f1fbfb 100%);
      border-radius: 34px;
      padding: 36px;
      ">
      <div class="test-head flex items-end justify-between gap-6 mb-8">
         <div>
            <small class="text-turquesa font-black text-xs">Testimonios</small>
            <h2 class="text-3xl lg:text-4xl font-bold leading-none mt-1" style="letter-spacing: -0.05em;">Lo que dicen<br>nuestros clientes</h2>
         </div>
         <div class="arrows flex gap-3">
            <button class="arrow w-12 h-12 rounded-full border-0 bg-turquesa text-white text-2xl font-black hover:bg-turquesa/90 transition-all hover:scale-105">←</button>
            <button class="arrow w-12 h-12 rounded-full border-0 bg-turquesa text-white text-2xl font-black hover:bg-turquesa/90 transition-all hover:scale-105">→</button>
         </div>
      </div>
      <div class="testimonial-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
         @include('web.components.testimonial-card', ['testimonialText' => 'Excelente atención y muy buen acompañamiento comercial. Encontramos los productos necesarios para implantología.', 'testimonialAuthor' => 'Dra. María Fernanda López', 'testimonialTitle' => 'Odontóloga implantóloga'])

         @include('web.components.testimonial-card', ['testimonialText' => 'Helin nos ha brindado soluciones confiables y un portafolio muy completo. Destaco la rapidez en la atención.', 'testimonialAuthor' => 'Dr. José Andrés Rivas', 'testimonialTitle' => 'Especialista en cirugía bucal'])

         @include('web.components.testimonial-card', ['testimonialText' => 'Muy buena experiencia de compra. La plataforma es fácil de usar y el equipo comercial responde con rapidez.', 'testimonialAuthor' => 'Clínica Sonrisa Integral', 'testimonialTitle' => 'Centro odontológico'])
      </div>
   </section>

   <!-- Sección de Opinión -->
   <div class="container mx-auto px-4">
      @include('web.partials.opinion')
   </div>
</main>
@endsection
