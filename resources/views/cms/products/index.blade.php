{{-- Content Layout --}}
<div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
   {{-- Header Section & Breadcrumb --}}
   <x-ui-section-header :module-id="\App\Models\Module::CATALOG" :submodule-id="\App\Models\Submodule::PRODUCTS" :subtitle="__('cms.products.title')">
      @if(!$showForm)
      <x-slot:action>
         <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[13px] font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('cms.products.new_button') }}
         </button>
      </x-slot:action>
      @endif
   </x-ui-section-header>
   @if(!$showForm)
   {{-- Main Unified Card: Filtros y Tabla --}}
   <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">
      {{-- Search & Filter Section --}}
      <div class="p-6 bg-white border-b border-slate-50">
         <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="relative flex-1 lg:max-w-xl">
               <span class="absolute left-3 top-1/2 -translate-y-1/2 text-body">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/>
                  </svg>
               </span>
               <input type="text" wire:model.live="search" placeholder="{{ __('cms.products.search_placeholder') }}"
                  class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
            </div>
            <!-- Filters Row -->
            <div class="flex flex-wrap items-center gap-3">
               <!-- Family Filter -->
               <div class="w-32">
                  <select wire:model.live="filterCategory" class="cms-perpage-select w-full bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                     <option value="">{{ __('cms.products.all_families') }}</option>
                     @foreach($categories as $category)
                     <option value="{{ $category->id }}">{{ $category->name }}</option>
                     @endforeach
                  </select>
               </div>
               <!-- Brand Filter -->
               <div class="min-w-[140px]">
                  <select wire:model.live="filterBrand" class="cms-perpage-select w-full bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                     <option value="">{{ __('cms.products.all_brands') }}</option>
                     @foreach($brands as $brand)
                     <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                     @endforeach
                  </select>
               </div>
               <!-- Status Filter -->
               <div class="min-w-[120px]">
                  <select wire:model.live="filterStatus" class="cms-perpage-select w-full bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                     <option value="all">{{ __('cms.products.filter_all') }}</option>
                     <option value="active">{{ __('cms.products.filter_active') }}</option>
                     <option value="inactive">{{ __('cms.products.filter_inactive') }}</option>
                     <option value="featured">{{ __('cms.products.filter_featured') }}</option>
                  </select>
               </div>
               <!-- Per Page -->
               <div class="min-w-[80px]">
                  <select wire:model.live="perPage" class="cms-perpage-select w-full bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                     <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                     <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                     <option value="50">{{ __('cms.tables.per_page_50') }}</option>
                  </select>
               </div>
            </div>
         </div>
      </div>
      {{-- Products Table --}}
      <div class="overflow-x-auto">
         <table class="w-full table-fixed text-left border-collapse">
            <thead>
               <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                  <th class="px-4 py-2.5">{{ __('cms.tables.product') }}</th>
                  <th class="px-4 py-2.5">{{ __('cms.tables.family') }}</th>
                  <th class="px-4 py-2.5">{{ __('cms.tables.brand') }}</th>
                  <th class="px-4 py-2.5 text-center w-32">{{ __('cms.tables.updated') }}</th>
                  <th class="px-4 py-2.5 text-center w-24">{{ __('cms.tables.status') }}</th>
                  <th class="px-4 py-2.5 text-right w-40">{{ __('cms.tables.actions') }}</th>
               </tr>
            </thead>
            <tbody id="products-table-body" class="divide-y divide-slate-100 text-[13px]">
               @forelse($products as $product)
               <tr wire:key="product-{{ $product->id }}" data-id="{{ $product->id }}" class="hover:bg-slate-50/70 transition-colors duration-150">
                  <td class="px-4 py-2.5">
                     <div class="flex items-start gap-2">
                        <div class="drag-handle cursor-move text-body hover:text-body mt-1">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                           </svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                           <div class="flex items-center gap-2">
                              <span class="text-body truncate">{{ $product->name }}</span>
                              @if($product->is_featured)
                              <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-yellow-50 text-yellow-600 border border-yellow-100">{{ __('cms.products.featured_badge') }}</span>
                              @endif
                           </div>
                        </div>
                     </div>
                  </td>
                  <td class="px-4 py-2.5">
                     @if($product->category)
                     <span class="text-[13px] text-body block truncate">{{ $product->category->name }}</span>
                     @else
                     <span class="text-[13px] text-body">-</span>
                     @endif
                  </td>
                  <td class="px-4 py-2.5">
                     @if($product->brand)
                     <span class="text-[13px] text-body block truncate">{{ $product->brand->name }}</span>
                     @else
                     <span class="text-[13px] text-body">-</span>
                     @endif
                  </td>
                  <td class="px-4 py-2.5 text-center text-[13px] text-body">
                     {{ $product->updated_at ? $product->updated_at->format('d/m/Y') : '-' }}
                  </td>
                  <td class="px-4 py-2.5 text-center">
                     <x-ui-badge-status :active="$product->is_active" :active-label="__('cms.general.status_active')" :inactive-label="__('cms.general.status_inactive')" />
                  </td>
                  <td class="px-4 py-2.5 text-right">
                     <div class="flex justify-end gap-1">
                        <x-cms-tooltip text="{{ $product->is_featured ? __('cms.products.remove_featured') : __('cms.products.mark_featured') }}">
                           <button wire:click="toggleFeatured({{ $product->id }})"
                                   class="p-2 {{ $product->is_featured ? 'text-yellow-500 bg-yellow-50' : 'text-body hover:text-yellow-500 hover:bg-yellow-50' }} rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                              </svg>
                           </button>
                        </x-cms-tooltip>
                        <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                           <button wire:click="edit({{ $product->id }})" class="p-2 text-body hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                              </svg>
                           </button>
                        </x-cms-tooltip>
                        <x-cms-tooltip text="{{ __('cms.general.duplicate') }}">
                           <button wire:click="duplicate({{ $product->id }})" class="p-2 text-body hover:text-blue-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z"/>
                              </svg>
                           </button>
                        </x-cms-tooltip>
                        <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                           <button onclick="deleteProduct({{ $product->id }})" class="p-2 text-body hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                              </svg>
                           </button>
                        </x-cms-tooltip>
                     </div>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="6" class="px-4 py-8 text-center"><x-ui-empty-state icon="folder" :title="__('cms.products.no_products')" />
                  </td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
   {{-- Paginación --}}
   {{ $products->links() }}
   @else
   {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
   <form wire:submit.prevent="save" class="space-y-4 sm:space-y-5">

      {{-- Información básica --}}
      <x-ui-form-card title="{{ $editingId ? __('cms.products.edit_title') : __('cms.products.new_title') }}" description="{{ __('cms.products.name_label') }}" icon="package">
         <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.name_label') }} <span class="text-red-500">*</span></label>
                  <input type="text" wire:model="name" required
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                  @error('name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
               </div>
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.sku_label') }} <span class="text-red-500">*</span></label>
                  <input type="text" wire:model="sku" required
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                  @error('sku') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
               </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.category_label') }} <span class="text-red-500">*</span></label>
                  <select wire:model="category_id" required
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                     <option value="">Seleccionar</option>
                     @foreach($categories as $category)
                     <option value="{{ $category->id }}">{{ $category->name }}</option>
                     @endforeach
                  </select>
                  @error('category_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
               </div>
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.brand_label') }} <span class="text-red-500">*</span></label>
                  <select wire:model="brand_id" required
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                     <option value="">{{ __('cms.products.select_option') }}</option>
                     @foreach($brands as $brand)
                     <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                     @endforeach
                  </select>
                  @error('brand_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
               </div>
            </div>
         </div>
      </x-ui-form-card>

      {{-- Clasificación --}}
      <x-ui-form-card title="Clasificación" description="Sistema, plataforma y material" icon="layers">
         <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.system_product_label') }}</label>
                  <select wire:model="system_product_id"
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                     <option value="">{{ __('cms.products.select_system_option') }}</option>
                     @foreach($systemProducts as $systemProduct)
                     <option value="{{ $systemProduct->id }}">{{ $systemProduct->name }}</option>
                     @endforeach
                  </select>
                  @error('system_product_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
               </div>
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.product_platform_label') }}</label>
                  <select wire:model="product_platform_id"
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                     <option value="">{{ __('cms.products.select_platform_option') }}</option>
                     @foreach($productPlatforms as $productPlatform)
                     <option value="{{ $productPlatform->id }}">{{ $productPlatform->name }}</option>
                     @endforeach
                  </select>
                  @error('product_platform_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
               </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Material</label>
                  <input type="text" wire:model="material" placeholder="ej: Titanio Grado 5, Cerámica, Acero"
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
               </div>
               <div class="space-y-1.5 flex items-center pt-5">
                  <x-ui-toggle wire:model="is_biomaterial" label="Es Biomaterial" />
               </div>
            </div>
         </div>
      </x-ui-form-card>

      {{-- Precio y stock --}}
      <x-ui-form-card title="Precio y stock" description="Información comercial" icon="dollar-sign">
         <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.price_label') }} <span class="text-red-500">*</span></label>
               <input type="number" step="0.01" wire:model="price" required
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
               @error('price') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.currency_label') }}</label>
               <select wire:model="currency"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                  <option value="USD">USD</option>
                  <option value="EUR">EUR</option>
                  <option value="COP">COP</option>
                  <option value="MXN">MXN</option>
               </select>
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.stock_label') }} <span class="text-red-500">*</span></label>
               <input type="number" wire:model="stock"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
               @error('stock') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.unit_label') }}</label>
               <input type="text" wire:model="unit" placeholder="{{ __('cms.products.unit_placeholder') }}"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
            </div>
         </div>
      </x-ui-form-card>

      {{-- Descripción --}}
      <x-ui-form-card title="Descripción" description="Descripción y especificaciones clínicas" icon="file-text">
         <div class="space-y-4">
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.description_label') }}</label>
               <textarea wire:model="description" rows="3"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none"></textarea>
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.clinical_specs_label') }}</label>
               <textarea wire:model="clinical_specs" rows="3" placeholder="{{ __('cms.products.clinical_specs_placeholder') }}"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none placeholder-body"></textarea>
            </div>
         </div>
      </x-ui-form-card>

      {{-- Atributos --}}
      @if($attributeGroups->isNotEmpty())
      <x-ui-form-card title="Atributos" description="Atributos del producto" icon="sliders-horizontal">
         <div class="space-y-4">
            @foreach($attributeGroups as $attribute)
               @if($attribute->activeValues->isNotEmpty())
               <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-4 space-y-2">
                  <div class="flex items-center justify-between">
                     <span class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ $attribute->name }}</span>
                     @if($attribute->unit)
                        <span class="text-[13px] text-body">{{ $attribute->unit }}</span>
                     @endif
                  </div>
                  <div class="flex flex-wrap gap-3">
                     @foreach($attribute->activeValues as $value)
                     <label class="inline-flex items-center gap-2 text-sm text-body cursor-pointer">
                        <input type="checkbox" value="{{ $value->id }}" wire:model="attribute_value_ids"
                               class="rounded border-slate-300 text-primary focus:ring-primary">
                        {{ $value->display_label }}
                     </label>
                     @endforeach
                  </div>
               </div>
               @endif
            @endforeach
            @error('attribute_value_ids') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
         </div>
      </x-ui-form-card>
      @endif

      {{-- Media --}}
      <x-ui-form-card title="{{ __('cms.products.media_section') }}" description="Imágenes y documentos" icon="image">
         <div class="space-y-4">
            <div class="space-y-1.5">
               <x-ui-file-upload model="featured_image" :preview="$featured_image" :label="__('cms.products.featured_image_label')">
                  {{ __('cms.products.featured_image_placeholder') }}
               </x-ui-file-upload>
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.gallery_label') }}</label>
               @if(!empty($gallery))
               <div class="grid grid-cols-4 gap-2 mb-2">
                  @foreach($gallery as $index => $img)
                  <div class="relative group">
                     <img src="{{ $img->temporaryUrl() }}" class="w-full h-16 object-cover rounded-lg border border-slate-100">
                     <button type="button" wire:click="removeGalleryImage({{ $index }})" class="absolute top-1 right-1 p-0.5 bg-white rounded text-red-500 hover:text-red-700 border-none cursor-pointer">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                     </button>
                  </div>
                  @endforeach
               </div>
               @endif
               <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                  <div class="flex flex-col items-center justify-center pt-4 pb-4">
                     <svg class="w-6 h-6 text-body mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                     </svg>
                     <p class="text-[13px] text-body">{{ __('cms.products.gallery_placeholder') }}</p>
                     <p class="text-[10px] text-body mt-0.5">JPG, PNG (Máx. 2MB c/u)</p>
                  </div>
                  <input type="file" wire:model="gallery" class="hidden" accept="image/*" multiple />
               </label>
               @error('gallery.*') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.documents_label') }}</label>
               @if(!empty($documents))
               <div class="flex flex-wrap gap-2 mb-2">
                  @foreach($documents as $doc)
                  <span class="inline-flex items-center px-2 py-1 bg-slate-50 border border-slate-100 rounded text-xs text-body">{{ $doc->getClientOriginalName() }}</span>
                  @endforeach
               </div>
               @endif
               <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                  <div class="flex flex-col items-center justify-center pt-4 pb-4">
                     <svg class="w-6 h-6 text-body mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                     </svg>
                     <p class="text-[13px] text-body">{{ __('cms.products.documents_placeholder') }}</p>
                     <p class="text-[10px] text-body mt-0.5">PDF, DOC (Máx. 5MB c/u)</p>
                  </div>
                  <input type="file" wire:model="documents" class="hidden" accept=".pdf,.doc,.docx" multiple />
               </label>
               @error('documents.*') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
            </div>
         </div>
      </x-ui-form-card>

      {{-- SEO --}}
      <x-ui-form-card title="{{ __('cms.products.seo_section') }}" description="Optimización para buscadores" icon="search">
         <div class="space-y-4">
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.meta_title') }}</label>
               <input type="text" wire:model="meta_title" placeholder="{{ __('cms.products.meta_title_placeholder') }}"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.meta_description') }}</label>
               <textarea wire:model="meta_description" rows="2" placeholder="{{ __('cms.products.meta_description_placeholder') }}"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none placeholder-body"></textarea>
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.meta_keywords') }}</label>
               <input type="text" wire:model="meta_keywords" placeholder="{{ __('cms.products.meta_keywords_placeholder') }}"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">SEO Description (Web)</label>
               <textarea wire:model="seo_description" rows="2" placeholder="Descripción para meta tags de la web"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none placeholder-body"></textarea>
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">SEO Keywords (Web)</label>
               <input type="text" wire:model="seo_keywords" placeholder="ej: implantes, titanio, odontología"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
               <p class="text-[11px] text-body italic mt-1">Separadas por comas</p>
            </div>
         </div>
      </x-ui-form-card>

      {{-- Promociones --}}
      <x-ui-form-card title="{{ __('cms.products.promotions_section') }}" description="Precios y fechas de oferta" icon="tag">
         <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
               <div class="space-y-1.5">
                  <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.sale_price') }}</label>
                  <input type="number" step="0.01" wire:model="sale_price" placeholder="0.00"
                     class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
               </div>
               <div class="grid grid-cols-2 gap-3">
                  <div class="space-y-1.5">
                     <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.sale_start') }}</label>
                     <input type="date" wire:model="sale_start_date"
                        class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                  </div>
                  <div class="space-y-1.5">
                     <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.sale_end') }}</label>
                     <input type="date" wire:model="sale_end_date"
                        class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                  </div>
               </div>
            </div>
            <div class="space-y-1.5">
               <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.products.publish_date') }}</label>
               <input type="datetime-local" wire:model="published_at"
                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
            </div>
         </div>
      </x-ui-form-card>

      {{-- Estado --}}
      <x-ui-form-card title="Estado" description="Visibilidad y destacados" icon="toggle-left">
         <div class="flex flex-wrap items-center gap-6">
            <x-ui-toggle wire:model="is_active" :label="__('cms.products.active')" />
            <x-ui-toggle wire:model="is_featured" :label="__('cms.products.featured')" />
            <x-ui-toggle wire:model="is_new" :label="__('cms.products.new')" />
            <x-ui-toggle wire:model="is_on_sale" :label="__('cms.products.on_sale')" />
         </div>
      </x-ui-form-card>

      {{-- Acciones --}}
      <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
         <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg text-[13px] font-medium border border-slate-200 text-body bg-white hover:bg-slate-50 transition-colors cursor-pointer">
            {{ __('cms.general.cancel') }}
         </button>
         <button type="button" wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-5 py-2 rounded-lg text-[13px] font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
            <span wire:loading wire:target="save">
               <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
               </svg>
            </span>
            <span wire:loading.remove wire:target="save">{{ $editingId ? __('cms.general.save') : __('cms.products.new_button') }}</span>
            <span wire:loading wire:target="save">{{ $editingId ? __('cms.general.save') : __('cms.products.new_button') }}</span>
         </button>
      </div>
   </form>
   @endif
</div>
<script>
   function deleteProduct(id) {
       const component = window.Livewire ? Livewire.find(
           document.querySelector('[wire\\:id]').getAttribute('wire:id')
       ) : null;
       if (!component) return;

       window.confirmAction({
           title: '{{ __('cms.products.delete_title') }}',
           text: '{{ __('cms.products.delete_warning') }}',
           confirmButtonText: '{{ __('cms.general.yes_delete') }}',
           cancelButtonText: '{{ __('cms.general.cancel') }}',
           confirmButtonColor: '#ef4444',
           onConfirm: function() {
               component.call('openDeleteModal', id);
               component.call('delete');
           }
       });
   }

   // Drag & Drop con SortableJS
   (function() {
       let sortableInstance = null;

       function initSortable() {
           const tbody = document.getElementById('products-table-body');

           if (!tbody) return;
           if (typeof Sortable === 'undefined') return;
           if (sortableInstance) sortableInstance.destroy();

           sortableInstance = new Sortable(tbody, {
               handle: '.drag-handle',
               animation: 150,
               ghostClass: 'bg-emerald-50',
               onEnd: function() {
                   const rows = tbody.querySelectorAll('tr[data-id]');
                   const orderedIds = Array.from(rows).map(row => parseInt(row.dataset.id));
                   const component = window.Livewire ? Livewire.find('{{ $this->getId() }}') : null;

                   if (component && orderedIds.length > 0) {
                       component.updateOrder(orderedIds);
                   }
               }
           });
       }

       // Initialize after DOM is ready
       if (document.readyState === 'loading') {
           document.addEventListener('DOMContentLoaded', initSortable);
       } else {
           initSortable();
       }

       // Reinitialize after Livewire updates
       document.addEventListener('livewire:updated', initSortable);
   })();
</script>
