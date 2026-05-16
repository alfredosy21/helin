<div class="min-h-screen pb-12 bg-[#f8fafc] relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 mb-1 font-medium tracking-wide uppercase">
                    <span>Catálogo</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-primary-600 font-semibold">Productos</span>
                </div>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    Catálogo de <span class="text-primary-600">Productos</span>
                </h1>
            </div>

            {{-- Botón Principal --}}
            <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Producto
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
                    <input type="text" wire:model.live="search" placeholder="Buscar por nombre o SKU..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-[#222] placeholder-[#c0c1c6]" />
                </div>
                <select wire:model.live="filterCategory" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterBrand" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="">Todas las marcas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterStatus" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="all">Todos los estados</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                    <option value="featured">Destacados</option>
                </select>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">10 por pág.</option>
                    <option value="15">15 por pág.</option>
                    <option value="25">25 por pág.</option>
                    <option value="50">50 por pág.</option>
                </select>
                <button wire:click="resetFilters" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-colors border border-slate-100 bg-slate-50" title="Resetear filtros">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.01M20 20v-5h-.01M4 9a8 8 0 0 1 8-8 8 8 0 0 1 8 8v7a8 8 0 0 1-8 8 8 8 0 0 1-8-8V9Z"/>
                    </svg>
                </button>
            </div>

            {{-- Products Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[#c0c1c6] text-xs font-semibold">
                            <th class="px-6 py-3.5 text-center w-16">ID</th>
                            <th class="px-6 py-3.5">Producto</th>
                            <th class="px-6 py-3.5">Categoría / Marca</th>
                            <th class="px-6 py-3.5 text-right">Precio</th>
                            <th class="px-6 py-3.5 text-center w-20">Stock</th>
                            <th class="px-6 py-3.5 text-center w-24">Estado</th>
                            <th class="px-6 py-3.5 text-right w-40">Acciones</th>
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
                                    <div class="flex items-center justify-center gap-1">
                                        @if($product->is_active)
                                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                                            <span class="text-xs font-medium text-slate-700">Activo</span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                            <span class="text-xs font-medium text-slate-400">Inactivo</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <button wire:click="edit({{ $product->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                        <button wire:click="duplicate({{ $product->id }})" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer" title="Duplicar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z"/>
                                            </svg>
                                        </button>
                                        <button onclick="openDeleteModal({{ $product->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
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
                                        <p class="text-xs font-medium">No se encontraron productos</p>
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
<div class="fixed inset-0 z-[100] flex items-center justify-end">
    <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-xs" wire:click="cancel"></div>

    <div class="relative w-full max-w-2xl h-full bg-white shadow-xl flex flex-col border-l border-slate-100">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h2 class="text-base font-bold text-[#222]">
                    {{ $editingId ? 'Editar Producto' : 'Nuevo Producto' }}
                </h2>
                <p class="text-xs text-[#c0c1c6]">{{ $editingId ? 'Actualizar información del catálogo' : 'Crear nuevo producto médico' }}</p>
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
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Nombre *</label>
                        <input type="text" wire:model="name" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        @error('name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">SKU *</label>
                        <input type="text" wire:model="sku" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        @error('sku') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Categoría *</label>
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
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Marca *</label>
                        <select wire:model="brand_id" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                            <option value="">Seleccionar</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Precio *</label>
                        <input type="number" step="0.01" wire:model="price" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                        @error('price') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Moneda</label>
                        <select wire:model="currency"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="COP">COP</option>
                            <option value="MXN">MXN</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Stock</label>
                        <input type="number" wire:model="stock"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                        @error('stock') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Unidad</label>
                        <input type="text" wire:model="unit" placeholder="Und"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Descripción</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none"></textarea>
                </div>

                {{-- Especificaciones Clínicas --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Especificaciones Clínicas</label>
                    <textarea wire:model="clinical_specs" rows="3" placeholder="Especificaciones técnicas del producto..."
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none placeholder-slate-300"></textarea>
                </div>

                {{-- SEO --}}
                <div class="border-t border-slate-100 pt-5 space-y-4">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">SEO y Metaetiquetas</h4>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Meta Título</label>
                        <input type="text" wire:model="meta_title" placeholder="Título para SEO"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Meta Descripción</label>
                        <textarea wire:model="meta_description" rows="2" placeholder="Descripción para SEO"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none placeholder-slate-300"></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Meta Keywords</label>
                        <input type="text" wire:model="meta_keywords" placeholder="palabra1, palabra2, palabra3"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                    </div>
                </div>

                {{-- Promociones --}}
                <div class="border-t border-slate-100 pt-5 space-y-4">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Promociones y Descuentos</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Precio de Oferta</label>
                            <input type="number" step="0.01" wire:model="sale_price" placeholder="0.00"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Fecha Inicio</label>
                                <input type="date" wire:model="sale_start_date"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Fecha Fin</label>
                                <input type="date" wire:model="sale_end_date"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Publicación --}}
                <div class="border-t border-slate-100 pt-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Fecha de Publicación</label>
                        <input type="datetime-local" wire:model="published_at"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                    </div>
                </div>

                {{-- Toggles de estado --}}
                <div class="flex flex-wrap items-center gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                        <span class="text-xs font-medium text-slate-600">Activo</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_featured" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                        <span class="text-xs font-medium text-slate-600">Destacado</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_new" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                        <span class="text-xs font-medium text-slate-600">Nuevo</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_on_sale" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                        <span class="text-xs font-medium text-slate-600">En Oferta</span>
                    </label>
                </div>
            </div>

            <div class="p-6 border-t border-slate-50 bg-slate-50/50 flex gap-3">
                <button type="button" wire:click="cancel" class="flex-1 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors py-2 cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" wire:loading.attr="disabled" class="flex-1 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors py-2 border-none cursor-pointer flex items-center justify-center">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Guardar cambios' : 'Crear producto' }}</span>
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

{{-- Modal de Eliminación --}}
@if($showDeleteModal)
<div class="fixed inset-0 z-[100] flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-xs" wire:click="$set('showDeleteModal', false)"></div>
    <div class="relative w-full max-w-sm bg-white rounded-xl shadow-2xl p-6 text-center border border-slate-100">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">¿Eliminar producto?</h3>
        <p class="text-sm text-slate-500 mb-6">Esta acción no se puede deshacer. El producto será eliminado permanentemente.</p>
        <div class="flex gap-3">
            <button wire:click="$set('showDeleteModal', false)" class="flex-1 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors py-2.5 cursor-pointer">
                Cancelar
            </button>
            <button wire:click="delete" wire:loading.attr="disabled" class="flex-1 rounded-lg text-sm font-medium bg-red-500 hover:bg-red-600 text-white transition-colors py-2.5 border-none cursor-pointer flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="delete">Eliminar</span>
                <span wire:loading wire:target="delete">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>
@endif

<script>
function normalizeLivewireEvent(raw) {
    if (Array.isArray(raw) && raw.length > 0) return raw[0];
    if (raw && typeof raw === 'object') return raw;
    return {};
}

function openDeleteModal(id) {
    if (typeof Livewire !== 'undefined') {
        Livewire.dispatch('openDeleteModal', { id: id });
    }
}

document.addEventListener('livewire:init', () => {
    Livewire.on('toast', (event) => {
        const data = normalizeLivewireEvent(event);
        const type = data.type || 'info';
        const message = data.message || '';

        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 max-w-sm transform transition-all duration-300 ease-in-out ${
            type === 'success' ? 'bg-white border-l-4 border-emerald-500' :
            type === 'error' ? 'bg-white border-l-4 border-red-500' :
            type === 'warning' ? 'bg-white border-l-4 border-yellow-500' :
            'bg-white border-l-4 border-blue-500'
        } rounded-r-xl p-4 shadow-xl border border-slate-100`;

        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 ${
                        type === 'success' ? 'text-emerald-500' :
                        type === 'error' ? 'text-red-500' :
                        type === 'warning' ? 'text-yellow-500' :
                        'text-primary-500'
                    }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M${
                            type === 'success' ? '5 13l4 4L19 7' :
                            type === 'error' ? '6 18L18 6' :
                            type === 'warning' ? '12 9v2m0 4h.01' :
                            '13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                        }"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">${message}</p>
                </div>
            </div>`;

        document.body.appendChild(toast);

        setTimeout(() => { toast.classList.add('translate-x-0'); }, 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => { toast.remove(); }, 300);
        }, 3000);
    });
});
</script>
</div>
