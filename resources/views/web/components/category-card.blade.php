<article class="category-card min-h-[140px] border border-gray-200 rounded-2xl p-5 bg-gradient-to-br from-white to-turquesa/10 shadow-sm hover:shadow-md transition relative overflow-hidden {{ $categoryIcon ?? '' }}">
   <small class="block text-turquesa text-xs font-black mb-2">{{ $categorySubtitle ?? '' }}</small>
   <h3 class="text-xl font-bold leading-none mb-4">{{ $categoryTitle ?? '' }}</h3>
   <a href="{{ $categoryLink ?? route('web.catalogo') }}" class="text-link text-turquesa font-black text-sm">Ver categoría →</a>
</article>
