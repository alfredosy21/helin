<article class="category-card min-h-[140px] border border-helin-border rounded-2xl shadow-sm hover:shadow-md transition relative overflow-hidden {{ $categoryIcon ?? '' }}">
   @if($categoryImage ?? false)
       <img src="{{ $categoryImage }}" alt="{{ $categoryTitle ?? '' }}" class="category-card-bg" loading="lazy">
   @endif
   <div class="category-card-content">
      <small class="block text-turquesa text-xs font-black mb-2">{{ $categorySubtitle ?? '' }}</small>
      <h3 class="text-xl leading-none mb-4">{{ $categoryTitle ?? '' }}</h3>
      <a href="{{ $categoryLink ?? route('catalogo') }}" class="text-link">Ver categoría →</a>
   </div>
</article>
