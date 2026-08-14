<div class="min-h-screen bg-[#f8fafc] pb-12">

    <!-- Header -->
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <x-cms-breadcrumb :module-id="\App\Models\Module::SETTINGS" :submodule-id="\App\Models\Submodule::GENERAL_SETTINGS" />
                <p class="text-sm text-slate-500 mt-2.5">
                    {{ __('cms.settings.edit_subtitle') }}
                </p>
            </div>

            <div class="flex items-center gap-6">
                <button type="button" wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="min-w-[120px] flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-[#079d8b] text-white text-sm font-medium rounded-lg transition-colors border-none cursor-pointer">
                    <span wire:loading.remove wire:target="save">
                        {{ __('cms.settings.save') }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="save" class="ml-1">
                        {{ __('cms.settings.save') }}
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="relative px-6 pb-6">
        <!-- FORM -->
        <div class="space-y-12">
            <!-- First Row: Corporate Information + Corporate Image -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Corporate Information Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        {{ __('cms.settings.corporate_info') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui-input label="{{ __('cms.settings.company_name') }}" wire:model="name" required />
                        <x-ui-input label="{{ __('cms.settings.contact_email') }}" wire:model="email" required />
                        <x-ui-input label="{{ __('cms.settings.main_phone') }}" wire:model="phone" />
                        <x-ui-input label="{{ __('cms.settings.business_hours') }}" wire:model="shedule" />
                        <div class="md:col-span-2">
                            <x-ui-textarea label="Eslogan / Slogan" wire:model="tagline" rows="2" placeholder="Todo en Cirugía Odontológica Especializada" />
                        </div>
                        <div class="md:col-span-2">
                            <x-ui-textarea label="Dirección de Contacto (Página de contacto)" wire:model="contact_address" rows="2" placeholder="Dirección completa que se mostrará en la página de contacto" />
                        </div>
                    </div>
                </div>

                <!-- Corporate Image Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        {{ __('cms.settings.corporate_image') }}
                    </h2>
                    <div class="relative">
                        @if($image)
                        <div class="mb-3 relative">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-48 object-contain rounded-lg border border-slate-100 bg-slate-50">
                        </div>
                        @elseif($current_image)
                        <div class="mb-3 relative">
                            <img src="{{ asset('storage/' . $current_image) }}" class="w-full h-48 object-contain rounded-lg border border-slate-100 bg-slate-50">
                        </div>
                        @endif
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                                </svg>
                                <p class="text-xs text-slate-500">{{ __('cms.settings.select_image') }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ __('cms.settings.image_formats_hint') }}</p>
                            </div>
                            <input type="file" wire:model="image" wire:change="save" class="hidden" accept="image/*" />
                        </label>
                        @error('image') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Second Row: Social Media & SEO -->
            <div class="bg-white p-6 rounded-xl border border-slate-100">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    {{ __('cms.settings.social_seo') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-ui-input label="{{ __('cms.settings.facebook_url') }}" wire:model="facebook" />
                    <x-ui-input label="{{ __('cms.settings.instagram_url') }}" wire:model="instagram" />
                    <x-ui-input label="{{ __('cms.settings.linkedin_url') }}" wire:model="linkedin" />
                    <x-ui-input label="{{ __('cms.settings.youtube_url') }}" wire:model="youtube" />
                    <div class="md:col-span-2 space-y-4">
                        <x-ui-input label="{{ __('cms.settings.keywords') }}" wire:model="keywords" />
                        <x-ui-textarea label="{{ __('cms.settings.short_description_seo') }}" wire:model="description" rows="2" />
                        <x-ui-textarea label="{{ __('cms.settings.system_description') }}" wire:model="settings_description" rows="3" />
                        <x-ui-textarea label="Google Analytics Code" wire:model="analytics_code" rows="4" placeholder="Pega aquí tu código de Google Analytics (gtag.js)" />
                        <x-ui-input label="URL Encuesta de Opinión" wire:model="opinion_url" placeholder="https://forms.gle/..." />
                    </div>
                </div>
            </div>

            <!-- Third Row: Footer Card - Full Width -->
            <div class="bg-white p-6 rounded-xl border border-slate-100">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    {{ __('cms.settings.footer') }}
                </h2>
                <div class="max-w-2xl">
                    <x-ui-input label="{{ __('cms.settings.copyright_text') }}" wire:model="copy" placeholder="2026 Helin CMS - Todos los derechos reservados" />
                    <p class="text-sm text-slate-500 mt-2">
                        {{ __('cms.settings.copyright_help') }}
                    </p>
                </div>
            </div>

            <!-- Fourth Row: Offices Configuration - Full Width -->
            <div class="bg-white p-6 rounded-xl border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        Nuestras Sedes
                    </h2>
                    <button type="button" wire:click="addOffice" class="inline-flex items-center gap-2 rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2 text-sm font-medium transition-colors border-none cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Agregar sede
                    </button>
                </div>

                @forelse($offices as $index => $office)
                <div class="border border-slate-100 rounded-xl p-5 mb-5 bg-slate-50/40" wire:key="office-{{ $index }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-800">Sede #{{ $index + 1 }}</h3>
                        <button type="button" wire:click="removeOffice({{ $index }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Nombre de la sede</label>
                            <input type="text" wire:model="offices.{{ $index }}.name" placeholder="ej: Caracas"
                                   class="w-full px-3 py-2.5 bg-white border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            @error('offices.' . $index . '.name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">WhatsApp (URL)</label>
                            <input type="text" wire:model="offices.{{ $index }}.whatsapp" placeholder="https://wa.me/58424..."
                                   class="w-full px-3 py-2.5 bg-white border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            @error('offices.' . $index . '.whatsapp') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Dirección punto de retiro</label>
                            <textarea wire:model="offices.{{ $index }}.url" rows="2"
                                      class="w-full px-3 py-2 bg-white border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300 resize-none"
                                      placeholder="Dirección del punto de retiro"></textarea>
                            @error('offices.' . $index . '.url') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center gap-3 md:col-span-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="offices.{{ $index }}.active" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="ml-3 text-sm font-medium text-slate-700">Sede activa</span>
                            </label>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-slate-400">
                    <p class="text-sm">No hay sedes configuradas. Presiona "Agregar sede" para crear una.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
