<?php
   /**
    * Página Principal - Helin
    */
   $pageTitle = 'Helin - Material Dental de Calidad';
   $customCSS = '<link rel="stylesheet" href="css/home.css">';
   include 'includes/head.php';
   ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/mobile-nav.php'; ?>
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
               <a href="catalogo.php" class="inline-flex items-center justify-center gap-2 h-12 px-8 rounded-full bg-white text-turquesa text-sm font-black shadow-xl hover:shadow-2xl transition-all hover:scale-105" style="box-shadow: 0 16px 30px rgba(15,47,67,.16);">
               Ver Catálogo →
               </a>
               <a href="solicitud.php" class="inline-flex items-center justify-center h-12 px-8 rounded-full border-2 border-white text-white text-sm font-black hover:bg-white/10 transition-all hover:scale-105">
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
   <?php include 'includes/beneficios.php'; ?>
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
                  <a href="catalogo.php" class="text-link text-turquesa font-black text-sm">Ver categoría →</a>
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
               <?php
                  $categorySubtitle = 'Recuperación y soporte';
                  $categoryTitle = 'Regeneración ósea guiada';
                  $categoryLink = 'catalogo.php';
                  include 'components/category-card.php';

                  $categorySubtitle = 'Fijación y precisión';
                  $categoryTitle = 'Osteosíntesis';
                  $categoryLink = 'catalogo.php';
                  include 'components/category-card.php';

                  $categorySubtitle = 'Bienestar oral';
                  $categoryTitle = 'Cuidado Bucal';
                  $categoryLink = 'catalogo.php';
                  include 'components/category-card.php';

                  $categorySubtitle = 'Precisión clínica';
                  $categoryTitle = 'Instrumentos';
                  $categoryLink = 'catalogo.php';
                  include 'components/category-card.php';

                  $categorySubtitle = 'Tecnología para tu práctica';
                  $categoryTitle = 'Equipos';
                  $categoryLink = 'catalogo.php';
                  include 'components/category-card.php';

                  $categorySubtitle = 'Diagnóstico y exactitud';
                  $categoryTitle = 'Planificación Digital';
                  $categoryLink = 'catalogo.php';
                  include 'components/category-card.php';
               ?>
            </div>
         </div>
      </div>
   </section>
   <!-- Sección "Estamos cerca de ti" -->
   <div class="container mx-auto px-4">
      <section class="near my-8 rounded-2xl border border-gray-200 bg-gray-50 flex items-center gap-7 p-6" style="
         margin: 30px 0;
         border-radius: 28px;
         border: 1px solid var(--line);
         background: #effafa;
         display: flex;
         align-items: center;
         gap: 26px;
         padding: 24px 30px;
         box-shadow: 0 10px 25px rgba(15,47,67,.06);
         ">
         <div class="circle-icon w-12 h-12 rounded-xl bg-turquesa/10 border border-turquesa/30 flex items-center justify-center text-turquesa font-black text-xl">
            ⌖
         </div>
         <div>
            <h2 class="text-2xl lg:text-3xl font-bold leading-none" style="letter-spacing: -0.045em;">
               <span class="text-turquesa">Estamos cerca de ti,</span> donde construyes salud oral
            </h2>
            <p class="text-gray-600 font-bold mt-1">Caracas · Valencia · Barquisimeto · Maracaibo</p>
         </div>
      </section>
   </div>
   <!-- Productos Destacados -->
   <section class="py-12 sm:py-16 bg-gray-100">
      <div class="container mx-auto px-4">
         <div class="section-title flex items-end justify-between gap-5 mb-5">
            <div>
               <h2 class="text-2xl lg:text-3xl font-bold leading-none mb-1" style="letter-spacing: -0.045em;">Más vendidos en Implantología</h2>
               <p class="text-gray-500 text-sm mt-1">Selección de productos destacados</p>
            </div>
            <a href="catalogo.php" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">Ver todos los productos →</a>
         </div>
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Implante';
               $productName = 'Implante Dental Straumann BLX';
               $productBrand = 'Straumann';
               $productPrice = 299.00;
               $productBadge = 'Nuevo';
               $productLink = 'producto.php';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Biomaterial';
               $productName = 'Biomaterial Óseo Bio-Oss';
               $productBrand = 'Geistlich';
               $productPrice = 149.00;
               $productBadge = '';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Kit';
               $productName = 'Kit de Cirugía Básico';
               $productBrand = 'Helin';
               $productPrice = 89.00;
               $productOldPrice = 120.00;
               $productBadge = 'Oferta';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Suturas';
               $productName = 'Suturas Resorbibles 4-0';
               $productBrand = 'Johnson & Johnson';
               $productPrice = 45.00;
               $productBadge = '';
               include 'components/product-card.php';
               ?>
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
            <a href="catalogo.php" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">Ver todos los productos →</a>
         </div>
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Membrana';
               $productName = 'Membrana Colágeno Bio-Gide';
               $productBrand = 'Geistlich';
               $productPrice = 89.00;
               $productBadge = 'Nuevo';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Tornillos';
               $productName = 'Tornillos de Osteosíntesis';
               $productBrand = 'Stryker';
               $productPrice = 75.00;
               $productBadge = 'Nuevo';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Placas';
               $productName = 'Placas de Reconstrucción';
               $productBrand = 'Stryker';
               $productPrice = 120.00;
               $productBadge = 'Nuevo';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Pinzas';
               $productName = 'Pinzas de Elevación';
               $productBrand = 'Hu-Friedy';
               $productPrice = 95.00;
               $productBadge = 'Nuevo';
               include 'components/product-card.php';
               ?>
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
            <a href="catalogo.php" class="text-turquesa text-xs font-black uppercase whitespace-nowrap">Ver todos los productos →</a>
         </div>
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Implante+2';
               $productName = 'Implante Nobel Active';
               $productBrand = 'Nobel Biocare';
               $productPrice = 249.00;
               $productOldPrice = 320.00;
               $productBadge = 'Oferta';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Kit+Avanzado';
               $productName = 'Kit de Cirugía Avanzado';
               $productBrand = 'Helin';
               $productPrice = 159.00;
               $productOldPrice = 200.00;
               $productBadge = 'Oferta';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Cinceles';
               $productName = 'Set de Cinceles';
               $productBrand = 'Hu-Friedy';
               $productPrice = 65.00;
               $productOldPrice = 85.00;
               $productBadge = 'Oferta';
               include 'components/product-card.php';

               $productImage = 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Separadores';
               $productName = 'Separadores de Muelas';
               $productBrand = 'Hu-Friedy';
               $productPrice = 35.00;
               $productOldPrice = 50.00;
               $productBadge = 'Oferta';
               include 'components/product-card.php';
               ?>
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
         <article class="testimonial">
            <div class="stars">★★★★★</div>
            <p>Excelente atención y muy buen acompañamiento comercial. Encontramos los productos necesarios para implantología.</p>
            <div class="person">
               <div class="avatar"></div>
               <div><strong>Dra. María Fernanda López</strong><span>Odontóloga implantóloga</span></div>
            </div>
            <div class="quote">”</div>
         </article>
         <article class="testimonial">
            <div class="stars">★★★★★</div>
            <p>Helin nos ha brindado soluciones confiables y un portafolio muy completo. Destaco la rapidez en la atención.</p>
            <div class="person">
               <div class="avatar"></div>
               <div><strong>Dr. José Andrés Rivas</strong><span>Especialista en cirugía bucal</span></div>
            </div>
            <div class="quote">”</div>
         </article>
         <article class="testimonial">
            <div class="stars">★★★★★</div>
            <p>Muy buena experiencia de compra. La plataforma es fácil de usar y el equipo comercial responde con rapidez.</p>
            <div class="person">
               <div class="avatar"></div>
               <div><strong>Clínica Sonrisa Integral</strong><span>Centro odontológico</span></div>
            </div>
            <div class="quote">”</div>
         </article>
      </div>
   </section>
   <!-- Sección de Opinión -->
   <div class="container mx-auto px-4">
      <section class="opinion">
         <h3>¡Nos encantaría conocer tu opinión!</h3>
         <a href="#">Compartir comentario</a>
      </section>
   </div>
</main>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>

