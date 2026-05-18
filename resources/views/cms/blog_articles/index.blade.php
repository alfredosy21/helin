<div class="min-h-screen pb-12 bg-[#f8fafc] relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <x-cms-breadcrumb
                    module="cms.blog_articles.parent_breadcrumb"
                    submodule="cms.blog_articles.breadcrumb">
                    <x-slot name="moduleIcon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                        </svg>
                    </x-slot>
                    <x-slot name="submoduleIcon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A9 9 0 006 18c2.305 0 4.408.867 6 2.292m0-14.25a8.967 8.967 0 016-3.75c1.052 0 2.062.18 3 .512v14.25A9 9 0 0118 18a8.962 8.962 0 01-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </x-slot>
                </x-cms-breadcrumb>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('cms.blog_articles.title') }}
                </h1>
            </div>

            {{-- Botón Principal --}}
            <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('cms.blog_articles.new_button') }}
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
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.blog_articles.search_placeholder') }}"
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-[#222] placeholder-[#c0c1c6]" />
                </div>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                    <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                    <option value="50">{{ __('cms.tables.per_page_50') }}</option>
                </select>
            </div>

            {{-- Articles Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[#c0c1c6] text-xs font-semibold">
                            <th class="px-6 py-3.5">{{ __('cms.tables.article') }}</th>
                            <th class="px-6 py-3.5">{{ __('cms.tables.author') }}</th>
                            <th class="px-6 py-3.5">{{ __('cms.tables.category') }}</th>
                            <th class="px-6 py-3.5 text-center w-24">{{ __('cms.tables.status') }}</th>
                            <th class="px-6 py-3.5 text-right w-40">{{ __('cms.tables.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="articles-table-body" class="divide-y divide-slate-50 text-sm">
                        @forelse($articles as $article)
                            <tr wire:key="article-{{ $article->id }}" data-id="{{ $article->id }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="drag-handle cursor-move text-slate-400 hover:text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-medium text-[#222]">{{ $article->title }}</span>
                                        <div class="flex gap-1 mt-1">
                                            @if($article->is_featured)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-yellow-50 text-yellow-600 border border-yellow-100">{{ __('cms.blog_articles.featured_badge') }}</span>
                                            @endif
                                            @if($article->is_pinned)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary-50 text-primary-600 border border-primary-100">{{ __('cms.blog_articles.pinned_badge') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-xs">
                                    {{ $article->author ?? __('cms.blog_articles.no_author') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($article->blogCategory)
                                        <span class="text-xs text-slate-600">{{ $article->blogCategory->name }}</span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">{{ __('cms.blog_articles.no_category') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="toggleStatus({{ $article->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold transition-colors cursor-pointer border-none {{ $article->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $article->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $article->is_active ? __('cms.general.published') : __('cms.general.draft') }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <x-cms-tooltip text="{{ $article->is_featured ? __('cms.blog_articles.remove_featured') : __('cms.blog_articles.mark_featured') }}">
                                            <button wire:click="toggleFeatured({{ $article->id }})"
                                                class="p-2 {{ $article->is_featured ? 'text-yellow-500 bg-yellow-50' : 'text-slate-400 hover:text-yellow-500 hover:bg-yellow-50' }} rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                        </x-cms-tooltip>
                                        <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                            <button wire:click="edit({{ $article->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                                </svg>
                                            </button>
                                        </x-cms-tooltip>
                                        <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                            <button onclick="openDeleteModal({{ $article->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-[#c0c1c6]">
                                        <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.25a2.25 2.25 0 0 1-2.25 2.25H2.25A2.25 2.25 0 0 1 0 20.25v-4.25A2.25 2.25 0 0 1 2.25 13.5A2.25 2.25 0 0 0 2.25 11.25V7.104a2.25 2.25 0 0 1 .515-1.425l3.525-4.406A2.25 2.25 0 0 1 8.012 1.5h7.976a2.25 2.25 0 0 1 1.722.813l3.525 4.406a2.25 2.25 0 0 1 .515 1.425v4.146ZM12 3v3.75m0-3.75a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Z"/></svg>
                                        <p class="text-xs font-medium">{{ __('cms.general.no_records') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($articles->hasPages())
                <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
        {{-- Drawer Lateral --}}
    @if($showForm)
    <div class="fixed inset-0 z-[60] overflow-hidden">
        <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-[1px]" wire:click="cancel"></div>

        <div class="absolute inset-y-0 right-0 max-w-2xl w-full bg-white shadow-[0_0_40px_-10px_rgba(0,0,0,0.1)] flex flex-col transform transition-transform duration-300 ease-out"
            x-data="{}"
            x-show="$wire.showForm"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-lg font-bold text-[#222]">{{ $editingId ? __('cms.blog_articles.edit_title') : __('cms.blog_articles.new_title') }}</h2>
                    <p class="text-xs text-[#c0c1c6] mt-0.5">{{ __('cms.blog_articles.subtitle') }}</p>
                </div>
                <button wire:click="cancel" class="p-2 hover:bg-slate-100 rounded-lg text-[#c0c1c6] hover:text-slate-600 transition-colors border-none bg-transparent cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.title_label') }}</label>
                        <input type="text" wire:model="title" required placeholder="{{ __('cms.blog_articles.title_placeholder') }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all" />
                        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.author_label') }}</label>
                        <input type="text" wire:model="author" placeholder="{{ __('cms.blog_articles.author_placeholder') }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all" />
                        @error('author') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.slug_label') }}</label>
                        <input type="text" wire:model="slug" placeholder="{{ __('cms.blog_articles.slug_placeholder') }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-slate-50 focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all" />
                        <p class="mt-1 text-[10px] text-slate-400">{{ __('cms.categories.slug_helper') }}</p>
                        @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.category_label') }}</label>
                        <select wire:model="blog_category_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all">
                            <option value="">{{ __('cms.blog_articles.no_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('blog_category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.excerpt_label') }}</label>
                    <textarea wire:model="excerpt" rows="2" placeholder="{{ __('cms.blog_articles.excerpt_placeholder') }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all resize-none"></textarea>
                    @error('excerpt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.content_label') }}</label>
                    <div x-data="{ quill: null }"
                         x-init="
                            quill = new Quill($refs.editor, {
                                theme: 'snow',
                                placeholder: '{{ __('cms.blog_articles.content_placeholder') }}'
                            });
                            quill.root.innerHTML = $wire.content || '';
                            quill.on('text-change', () => { $wire.content = quill.root.innerHTML });
                         "
                         @open-form.window="setTimeout(() => { if(quill) quill.root.innerHTML = $wire.content || ''; }, 100)">
                        <div x-ref="editor" class="bg-white min-h-[200px] rounded-lg border border-slate-200"></div>
                    </div>
                    @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.featured_image_label') }}</label>
                    <div class="relative">
                        @if($featured_image)
                            <div class="mb-2 relative group">
                                <img src="{{ $featured_image->temporaryUrl() }}" class="w-full h-40 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('featured_image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @elseif($current_featured_image)
                            <div class="mb-2 relative group">
                                <img src="{{ asset('storage/' . $current_featured_image) }}" class="w-full h-40 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('current_featured_image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif
                        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                                </svg>
                                <p class="text-xs text-slate-500">{{ __('cms.blog_articles.featured_image_placeholder') }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG (Máx. 2MB)</p>
                            </div>
                            <input type="file" wire:model="featured_image" class="hidden" accept="image/*" />
                        </label>
                        @error('featured_image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-sm font-bold text-slate-700 mb-3">{{ __('cms.blog_articles.seo_section') }}</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.meta_title_label') }}</label>
                            <input type="text" wire:model="meta_title" placeholder="{{ __('cms.blog_articles.meta_title_placeholder') }}"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all" />
                            @error('meta_title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.meta_description_label') }}</label>
                            <textarea wire:model="meta_description" rows="2" placeholder="{{ __('cms.blog_articles.meta_description_placeholder') }}"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all resize-none"></textarea>
                            @error('meta_description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('cms.blog_articles.meta_keywords_label') }}</label>
                            <input type="text" wire:model="meta_keywords" placeholder="{{ __('cms.blog_articles.meta_keywords_placeholder') }}"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none transition-all" />
                            @error('meta_keywords') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <label class="flex items-center cursor-pointer gap-2">
                        <input type="checkbox" wire:model="is_active" class="hidden peer">
                        <div class="w-9 h-5 bg-slate-300 peer-checked:bg-emerald-500 rounded-full relative transition-colors">
                            <div class="absolute top-0.5 left-0.5 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4 shadow-sm"></div>
                        </div>
                        <span class="text-xs font-medium text-slate-600">{{ __('cms.general.published') }}</span>
                    </label>
                    <label class="flex items-center cursor-pointer gap-2">
                        <input type="checkbox" wire:model="is_featured" class="hidden peer">
                        <div class="w-9 h-5 bg-slate-300 peer-checked:bg-yellow-500 rounded-full relative transition-colors">
                            <div class="absolute top-0.5 left-0.5 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4 shadow-sm"></div>
                        </div>
                        <span class="text-xs font-medium text-slate-600">{{ __('cms.general.featured') }}</span>
                    </label>
                    <label class="flex items-center cursor-pointer gap-2">
                        <input type="checkbox" wire:model="is_pinned" class="hidden peer">
                        <div class="w-9 h-5 bg-slate-300 peer-checked:bg-primary rounded-full relative transition-colors">
                            <div class="absolute top-0.5 left-0.5 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4 shadow-sm"></div>
                        </div>
                        <span class="text-xs font-medium text-slate-600">{{ __('cms.general.pinned') }}</span>
                    </label>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <button type="button" wire:click="cancel"
                    class="px-4 py-2.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:bg-white hover:shadow-sm transition-all cursor-pointer bg-transparent">
                    {{ __('cms.general.cancel') }}
                </button>
                <button type="button" wire:click="save" wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-lg bg-primary hover:bg-[#079d8b] text-white text-sm font-medium shadow-sm hover:shadow-md transition-all cursor-pointer inline-flex items-center">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? __('cms.general.save') : __('cms.blog_articles.new_button') }}</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ __('cms.general.saving') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
    // Drag & Drop con SortableJS
    (function() {
        let sortableInstance = null;

        function initSortable() {
            const tbody = document.getElementById('articles-table-body');
            if (!tbody || typeof Sortable === 'undefined') return;
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

        initSortable();
        document.addEventListener('livewire:updated', initSortable);
    })();

    function openDeleteModal(id) {
        const component = window.Livewire.find(
            document.querySelector('[wire\\:id]').getAttribute('wire:id')
        );
        if (!component) return;

        window.confirmDelete({
            title: '{{ __('cms.blog_articles.delete_title') }}',
            text: '{{ __('cms.general.delete_confirm_text') }}',
            confirmButtonText: '{{ __('cms.general.yes_delete') }}',
            cancelButtonText: '{{ __('cms.general.cancel') }}',
            onConfirm: function() {
                component.call('openDeleteModal', id);
                component.call('delete');
            }
        });
    }
</script>
