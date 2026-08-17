<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-3 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::SETTINGS" :submodule-id="\App\Models\Submodule::GENERAL_SETTINGS" :subtitle="__('cms.settings.edit_subtitle')" />

        <form wire:submit.prevent="save" class="space-y-4 sm:space-y-5">

            <!-- Corporate Information -->
            <x-ui-form-card title="{{ __('cms.settings.corporate_info') }}" description="Datos principales de la empresa" icon="building">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <x-ui-input label="{{ __('cms.settings.company_name') }}" wire:model="name" required />
                    <x-ui-input label="{{ __('cms.settings.contact_email') }}" wire:model="email" required />
                    <x-ui-input label="{{ __('cms.settings.main_phone') }}" wire:model="phone" />
                    <x-ui-input label="{{ __('cms.settings.business_hours') }}" wire:model="shedule" />
                    <x-ui-textarea label="Eslogan / Slogan" wire:model="tagline" rows="2" placeholder="Todo en Cirugía Odontológica Especializada" />
                    <x-ui-textarea label="Dirección de Contacto (Página de contacto)" wire:model="contact_address" rows="2" placeholder="Dirección completa que se mostrará en la página de contacto" />
                </div>
            </x-ui-form-card>

            <!-- Corporate Image Card -->
            <x-ui-form-card title="{{ __('cms.settings.corporate_image') }}" description="Logo e identidad visual" icon="image">
                <x-ui-file-upload model="image" current-model="current_image" :preview="$image" :current-image="$current_image" :label="__('cms.settings.select_image')" :hint="__('cms.settings.image_formats_hint')" height="h-24">
                    {{ __('cms.settings.select_image') }}
                </x-ui-file-upload>
            </x-ui-form-card>

            <!-- Default Images Card -->
            <x-ui-form-card title="{{ __('cms.settings.default_images') }}" description="Imágenes por defecto del catálogo" icon="photo">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Imagen por defecto de categoría --}}
                    <x-ui-file-upload model="default_category_image" current-model="current_default_category_image" :preview="$default_category_image" :current-image="$current_default_category_image" :label="__('cms.settings.default_category_image')" height="h-20">
                        {{ __('cms.settings.select_image') }}
                    </x-ui-file-upload>

                    {{-- Imagen por defecto de banner --}}
                    <x-ui-file-upload model="default_banner_image" current-model="current_default_banner_image" :preview="$default_banner_image" :current-image="$current_default_banner_image" :label="__('cms.settings.default_banner_image')" height="h-20">
                        {{ __('cms.settings.select_image') }}
                    </x-ui-file-upload>
                </div>
            </x-ui-form-card>

            <!-- Social Media & SEO -->
            <x-ui-form-card title="{{ __('cms.settings.social_seo') }}" description="Redes sociales y optimización SEO" icon="share-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <x-ui-input label="{{ __('cms.settings.facebook_url') }}" wire:model="facebook" />
                    <x-ui-input label="{{ __('cms.settings.instagram_url') }}" wire:model="instagram" />
                    <x-ui-input label="{{ __('cms.settings.linkedin_url') }}" wire:model="linkedin" />
                    <x-ui-input label="{{ __('cms.settings.youtube_url') }}" wire:model="youtube" />
                    <div class="md:col-span-2">
                        <x-ui-input label="{{ __('cms.settings.keywords') }}" wire:model="keywords" />
                    </div>
                    <div class="md:col-span-2">
                        <x-ui-textarea label="{{ __('cms.settings.short_description_seo') }}" wire:model="description" rows="2" />
                    </div>
                    <div class="md:col-span-2">
                        <x-ui-textarea label="{{ __('cms.settings.system_description') }}" wire:model="settings_description" rows="3" />
                    </div>
                    <div class="md:col-span-2">
                        <x-ui-textarea label="Google Analytics Code" wire:model="analytics_code" rows="4" placeholder="Pega aquí tu código de Google Analytics (gtag.js)" />
                    </div>
                    <div class="md:col-span-2">
                        <x-ui-input label="URL Encuesta de Opinión" wire:model="opinion_url" placeholder="https://forms.gle/..." />
                    </div>
                </div>
            </x-ui-form-card>

            <!-- Footer Card -->
            <x-ui-form-card title="{{ __('cms.settings.footer') }}" description="Contenido del pie de página" icon="layout">
                <div class="max-w-2xl">
                    <x-ui-input label="{{ __('cms.settings.copyright_text') }}" wire:model="copy" placeholder="2026 Helin CMS - Todos los derechos reservados" />
                    <p class="text-[11px] text-body mt-2">
                        {{ __('cms.settings.copyright_help') }}
                    </p>
                </div>
            </x-ui-form-card>

            <!-- Offices Configuration -->
            <x-ui-form-card title="Nuestras Sedes" description="Puntos de retiro y atención" icon="map-pin">
                <x-slot:action>
                    <button type="button" wire:click="addOffice" class="inline-flex items-center gap-1.5 rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[12px] font-medium transition-colors border-none cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Agregar sede
                    </button>
                </x-slot:action>

                <div class="space-y-3">
                    @forelse($offices as $index => $office)
                    <div class="border border-slate-100 rounded-lg p-4 bg-slate-50/40" wire:key="office-{{ $index }}">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[12px] font-semibold text-heading">Sede #{{ $index + 1 }}</h4>
                            <button type="button" wire:click="removeOffice({{ $index }})" class="p-1.5 text-body hover:text-red-500 hover:bg-white rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Nombre de la sede</label>
                                <input type="text" wire:model="offices.{{ $index }}.name" placeholder="ej: Caracas"
                                       class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                                @error('offices.' . $index . '.name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-body uppercase tracking-wider">WhatsApp (URL)</label>
                                <input type="text" wire:model="offices.{{ $index }}.whatsapp" placeholder="https://wa.me/58424..."
                                       class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                                @error('offices.' . $index . '.whatsapp') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Dirección punto de retiro</label>
                                <textarea wire:model="offices.{{ $index }}.url" rows="2"
                                          class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"
                                          placeholder="Dirección del punto de retiro"></textarea>
                                @error('offices.' . $index . '.url') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center gap-3 md:col-span-2">
                                <x-ui-toggle wire:model="offices.{{ $index }}.active" :label="__('cms.general.status_active')" />
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-body">
                        <p class="text-[13px]">No hay sedes configuradas. Presiona "Agregar sede" para crear una.</p>
                    </div>
                    @endforelse
                </div>
            </x-ui-form-card>

            <!-- Contact Subjects Configuration -->
            <x-ui-form-card title="Asuntos del formulario de contacto" description="Opciones del selector de asuntos" icon="mail">
                <x-slot:action>
                    <button type="button" wire:click="addContactSubject" class="inline-flex items-center gap-1.5 rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[12px] font-medium transition-colors border-none cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Agregar asunto
                    </button>
                </x-slot:action>

                <div class="space-y-3">
                    @forelse($contact_subjects as $index => $subject)
                    <div class="border border-slate-100 rounded-lg p-4 bg-slate-50/40" wire:key="subject-{{ $index }}">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[12px] font-semibold text-heading">Asunto #{{ $index + 1 }}</h4>
                            <button type="button" wire:click="removeContactSubject({{ $index }})" class="p-1.5 text-body hover:text-red-500 hover:bg-white rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Valor (slug)</label>
                                <input type="text" wire:model="contact_subjects.{{ $index }}.value" placeholder="ej: informacion-comercial"
                                       class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                                @error('contact_subjects.' . $index . '.value') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Etiqueta visible</label>
                                <input type="text" wire:model="contact_subjects.{{ $index }}.label" placeholder="ej: Información comercial"
                                       class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                                @error('contact_subjects.' . $index . '.label') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center gap-3 md:col-span-2">
                                <x-ui-toggle wire:model="contact_subjects.{{ $index }}.active" :label="__('cms.general.status_active')" />
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-body">
                        <p class="text-[13px]">No hay asuntos configurados. Presiona "Agregar asunto" para crear uno.</p>
                    </div>
                    @endforelse
                </div>
            </x-ui-form-card>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-5 py-2 rounded-lg text-[13px] font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="save">
                        {{ __('cms.settings.save') }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="save" class="ml-1">
                        {{ __('cms.settings.save') }}
                    </span>
                </button>
            </div>

        </form>

    </div>

</div>
