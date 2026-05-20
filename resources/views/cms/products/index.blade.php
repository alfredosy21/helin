{{-- Content Layout --}}
<div class="p-6 space-y-6">

    {{-- Header Section & Breadcrumb --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <x-cms-breadcrumb :module-id="3" :submodule-id="5" />
                   <p class="text-sm text-slate-500 mt-2.5">
                    {{ __('cms.products.title') }}
</p>
            </div>

            {{-- Botón Principal --}}
            <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('cms.products.new_button') }}
            </button>
        </div>

        {{-- Main Unified Card: Filtros y Tabla --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/>
                        </svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.products.search_placeholder') }}"
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-[#222] placeholder-[#c0c1c6]" />
                </div>
                <select wire:model.live="filterCategory" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="">{{ __('cms.products.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterBrand" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="">{{ __('cms.products.all_brands') }}</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterStatus" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="all">{{ __('cms.products.filter_all') }}</option>
                    <option value="active">{{ __('cms.products.filter_active') }}</option>
                    <option value="inactive">{{ __('cms.products.filter_inactive') }}</option>
                    <option value="featured">{{ __('cms.products.filter_featured') }}</option>
                </select>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                    <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                    <option value="50">{{ __('cms.tables.per_page_50') }}</option>
                </select>
                <x-cms-tooltip text="{{ __('cms.products.reset_filters') }}">
                    <button wire:click="resetFilters" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-colors border border-slate-100 bg-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.01M20 20v-5h-.01M4 9a8 8 0 0 1 8-8 8 8 0 0 1 8 8v7a8 8 0 0 1-8 8 8 8 0 0 1-8-8V9Z"/>
                        </svg>
                    </button>
                </x-cms-tooltip>
            </div>

            {{-- Products Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[#c0c1c6] text-xs font-semibold">
                            <th class="px-6 py-3.5 text-center w-16">{{ __('cms.tables.id') }}</th>
                            <th class="px-6 py-3.5">{{ __('cms.tables.product') }}</th>
                            <th class="px-6 py-3.5">{{ __('cms.tables.category_brand') }}</th>
                            <th class="px-6 py-3.5 text-right">{{ __('cms.tables.price') }}</th>
                            <th class="px-6 py-3.5 text-center w-20">{{ __('cms.tables.stock') }}</th>
                            <th class="px-6 py-3.5 text-center w-40">{{ __('cms.tables.updated_at') }}</th>
                            <th class="px-6 py-3.5 text-center w-24">{{ __('cms.tables.status') }}</th>
                            <th class="px-6 py-3.5 text-right w-40">{{ __('cms.tables.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($products as $product)
                            <tr wire:key="product-{{ $product->id }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center font-mono text-xs text-slate-400">
                                    #{{ str_pad((string)$product->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-[#222]">{{ $product->name }}</span>
                                        <span class="text-xs text-[#c0c1c6]">SKU: {{ $product->sku }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        @if($product->category)
                                            <span class="text-xs text-slate-600">{{ $product->category->name }}</span>
                                        @endif
                                        @if($product->brand)
                                            <span class="text-xs text-slate-400">{{ $product->brand->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-[#222]">
                                    ${{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-600">
                                    {{ $product->stock ?? 0 }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs text-slate-500">
                                        {{ $product->updated_at->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @if($product->is_active)
                                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                                            <span class="text-xs font-medium text-slate-700">{{ __('cms.general.status_active') }}</span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                            <span class="text-xs font-medium text-slate-400">{{ __('cms.general.status_inactive') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                            <button wire:click="edit({{ $product->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                                </svg>
                                            </button>
                                        </x-cms-tooltip>
                                        <x-cms-tooltip text="{{ __('cms.general.duplicate') }}">
                                            <button wire:click="duplicate({{ $product->id }})" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z"/>
                                                </svg>
                                            </button>
                                        </x-cms-tooltip>
                                        <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                            <button onclick="deleteProduct({{ $product->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-[#c0c1c6]">
                                        <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.25a2.25 2.25 0 0 1-2.25 2.25H2.25A2.25 2.25 0 0 1 0 20.25v-4.25A2.25 2.25 0 0 1 2.25 13.5A2.25 2.25 0 0 0 2.25 11.25V7.104a2.25 2.25 0 0 1 .515-1.425l3.525-4.406A2.25 2.25 0 0 1 8.012 1.5h7.976a2.25 2.25 0 0 1 1.722.813l3.525 4.406a2.25 2.25 0 0 1 .515 1.425v4.146ZM12 3v3.75m0-3.75a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Z"/>
                                        </svg>
                                        <p class="text-xs font-medium">{{ __('cms.products.no_products') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($products->hasPages())
                <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Drawer lateral de Producto --}}
@if($showForm)
<div class="fixed inset-0 z-[50] flex items-center justify-end">
    <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-xs" wire:click="cancel"></div>

    <div class="relative w-full max-w-2xl h-full bg-white shadow-xl flex flex-col border-l border-slate-100">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h2 class="text-base font-bold text-[#222]">
                    {{ $editingId ? __('cms.products.edit_title') : __('cms.products.new_title') }}
                </h2>
                <p class="text-xs text-[#c0c1c6]">{{ $editingId ? __('cms.products.edit_subtitle') : __('cms.products.new_subtitle') }}</p>
            </div>
            <button wire:click="cancel" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors border-none bg-transparent cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form wire:submit.prevent="save" class="flex flex-col flex-1 h-full">
            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                {{-- Básico --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.name_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        @error('name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.sku_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="sku" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        @error('sku') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.category_label') }} <span class="text-red-500">*</span></label>
                        <select wire:model="category_id" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                            <option value="">Seleccionar</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.brand_label') }} <span class="text-red-500">*</span></label>
                        <select wire:model="brand_id" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                            <option value="">{{ __('cms.products.select_option') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.price_label') }} <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" wire:model="price" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                        @error('price') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.currency_label') }}</label>
                        <select wire:model="currency"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="COP">COP</option>
                            <option value="MXN">MXN</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.stock_label') }} <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="stock"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                        @error('stock') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.unit_label') }}</label>
                        <input type="text" wire:model="unit" placeholder="{{ __('cms.products.unit_placeholder') }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.description_label') }}</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none"></textarea>
                </div>

                {{-- Especificaciones Clínicas --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.clinical_specs_label') }}</label>
                    <textarea wire:model="clinical_specs" rows="3" placeholder="{{ __('cms.products.clinical_specs_placeholder') }}"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none placeholder-slate-300"></textarea>
                </div>

                {{-- Imágenes y Documentos --}}
                <div class="border-t border-slate-100 pt-5 space-y-4">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('cms.products.media_section') }}</h4>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.featured_image_label') }}</label>
                        @if($featured_image)
                            <div class="mb-2 relative group">
                                <img src="{{ $featured_image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('featured_image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                                </svg>
                                <p class="text-xs text-slate-500">{{ __('cms.products.featured_image_placeholder') }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG (Máx. 2MB)</p>
                            </div>
                            <input type="file" wire:model="featured_image" class="hidden" accept="image/*" />
                        </label>
                        @error('featured_image') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.gallery_label') }}</label>
                        @if(!empty($gallery))
                            <div class="grid grid-cols-4 gap-2 mb-2">
                                @foreach($gallery as $index => $img)
                                    <div class="relative group">
                                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-16 object-cover rounded-lg border border-slate-100">
                                        <button type="button" wire:click="$set('gallery', {{ json_encode(array_values(array_filter($gallery, fn($_, $i) => $i !== $index, ARRAY_FILTER_USE_BOTH))) }})" class="absolute top-1 right-1 p-0.5 bg-white rounded text-red-500 hover:text-red-700 border-none cursor-pointer">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                                </svg>
                                <p class="text-xs text-slate-500">{{ __('cms.products.gallery_placeholder') }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG (Máx. 2MB c/u)</p>
                            </div>
                            <input type="file" wire:model="gallery" class="hidden" accept="image/*" multiple />
                        </label>
                        @error('gallery.*') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.documents_label') }}</label>
                        @if(!empty($documents))
                            <div class="flex flex-wrap gap-2 mb-2">
                                @foreach($documents as $doc)
                                    <span class="inline-flex items-center px-2 py-1 bg-slate-50 border border-slate-100 rounded text-xs text-slate-600">{{ $doc->getClientOriginalName() }}</span>
                                @endforeach
                            </div>
                        @endif
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                                <p class="text-xs text-slate-500">{{ __('cms.products.documents_placeholder') }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">PDF, DOC (Máx. 5MB c/u)</p>
                            </div>
                            <input type="file" wire:model="documents" class="hidden" accept=".pdf,.doc,.docx" multiple />
                        </label>
                        @error('documents.*') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- SEO --}}
                <div class="border-t border-slate-100 pt-5 space-y-4">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('cms.products.seo_section') }}</h4>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.meta_title') }}</label>
                        <input type="text" wire:model="meta_title" placeholder="{{ __('cms.products.meta_title_placeholder') }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.meta_description') }}</label>
                        <textarea wire:model="meta_description" rows="2" placeholder="{{ __('cms.products.meta_description_placeholder') }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none placeholder-slate-300"></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.meta_keywords') }}</label>
                        <input type="text" wire:model="meta_keywords" placeholder="{{ __('cms.products.meta_keywords_placeholder') }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                    </div>
                </div>

                {{-- Promociones --}}
                <div class="border-t border-slate-100 pt-5 space-y-4">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('cms.products.promotions_section') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.sale_price') }}</label>
                            <input type="number" step="0.01" wire:model="sale_price" placeholder="0.00"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.sale_start') }}</label>
                                <input type="date" wire:model="sale_start_date"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.sale_end') }}</label>
                                <input type="date" wire:model="sale_end_date"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Publicación --}}
                <div class="border-t border-slate-100 pt-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.products.publish_date') }}</label>
                        <input type="datetime-local" wire:model="published_at"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                    </div>
                </div>

                {{-- Toggles de estado --}}
                <div class="flex flex-wrap items-center gap-6 pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        <span class="ml-3 text-xs font-medium text-slate-600">{{ __('cms.products.active') }}</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_featured" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                        <span class="ml-3 text-xs font-medium text-slate-600">{{ __('cms.products.featured') }}</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_new" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                        <span class="ml-3 text-xs font-medium text-slate-600">{{ __('cms.products.new') }}</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_on_sale" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                        <span class="ml-3 text-xs font-medium text-slate-600">{{ __('cms.products.on_sale') }}</span>
                    </label>
                </div>
            </div>

            <div class="p-6 border-t border-slate-50 bg-slate-50/50 flex gap-3">
                <button type="button" wire:click="cancel" class="flex-1 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors py-2 cursor-pointer">
                    {{ __('cms.general.cancel') }}
                </button>
                <button type="submit" wire:loading.attr="disabled" class="flex-1 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors py-2 border-none cursor-pointer flex items-center justify-center">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? __('cms.general.save') : __('cms.products.new_title') }}</span>
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif

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
</script>
</div>
</div>
