<div class="min-h-screen bg-[#f8fafc] pb-12">

    <!-- Header -->
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <x-cms-breadcrumb module="cms.settings.title">
                    <x-slot name="moduleIcon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </x-slot>
                </x-cms-breadcrumb>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ $isEditing ? __('cms.settings.editing') : __('cms.settings.title') }}
                </h1>
                <p class="text-sm text-[#c0c1c6] mt-1">
                    {{ $isEditing ? __('cms.settings.edit_subtitle') : __('cms.settings.view_subtitle') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                @if($isEditing)
                    <x-ui-button variant="secondary" wire:click="toggleEdit">
                        <x-ui-icon name="x" class="w-4 h-4 mr-2" />
                        {{ __('cms.settings.cancel') }}
                    </x-ui-button>
                    <x-ui-button variant="primary" wire:click="save">
                        <x-ui-icon name="save" class="w-4 h-4 mr-2" />
                        {{ __('cms.settings.save') }}
                    </x-ui-button>
                @else
                    <x-ui-button variant="primary" wire:click="toggleEdit">
                        <x-ui-icon name="edit-3" class="w-4 h-4 mr-2" />
                        {{ __('cms.settings.edit_params') }}
                    </x-ui-button>
                @endif
            </div>
        </div>
    </div>

    <div class="relative px-6 pb-6">
    @if($isEditing)
        <!-- EDIT MODE: FORM -->
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
                        <x-ui-input label="{{ __('cms.settings.company_name') }}" wire:model="name" />
                        <x-ui-input label="{{ __('cms.settings.contact_email') }}" wire:model="email" />
                        <x-ui-input label="{{ __('cms.settings.main_phone') }}" wire:model="phone" />
                        <x-ui-input label="{{ __('cms.settings.business_hours') }}" wire:model="shedule" />
                        <div class="md:col-span-2">
                            <x-ui-textarea label="{{ __('cms.settings.physical_address') }}" wire:model="address" rows="2" />
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
                            <input type="file" wire:model="image" class="hidden" accept="image/*" />
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
        </div>
    @else
        <!-- READ MODE: CARDS -->
        <div class="px-6 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Corporate Information Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-100">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-600">
                            <x-ui-icon name="building" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ $name ?: __('cms.settings.no_name') }}</h3>
                            <p class="text-xs text-[#c0c1c6] uppercase tracking-wide">{{ __('cms.settings.contact_data') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-ui-icon name="mail" class="w-4 h-4 text-slate-500" />
                            </div>
                            <div>
                                <p class="text-xs text-[#c0c1c6]">{{ __('cms.settings.email_label') }}</p>
                                <p class="text-sm text-slate-700 font-medium">{{ $email ?: __('cms.settings.not_configured') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-ui-icon name="phone" class="w-4 h-4 text-slate-500" />
                            </div>
                            <div>
                                <p class="text-xs text-[#c0c1c6]">{{ __('cms.settings.phone_label') }}</p>
                                <p class="text-sm text-slate-700 font-medium">{{ $phone ?: __('cms.settings.not_configured') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <x-ui-icon name="clock" class="w-4 h-4 text-slate-500" />
                            </div>
                            <div>
                                <p class="text-xs text-[#c0c1c6]">{{ __('cms.settings.schedule_label') }}</p>
                                <p class="text-sm text-slate-700 font-medium">{{ $shedule ?: __('cms.settings.not_configured') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO & Metadata Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-100">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-600">
                            <x-ui-icon name="search" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ __('cms.settings.seo_metadata') }}</h3>
                            <p class="text-xs text-[#c0c1c6] uppercase tracking-wide">{{ __('cms.settings.positioning') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $description ?: __('cms.settings.no_seo_description') }}
                            </p>
                        </div>
                        @if($keywords)
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $keywords) as $keyword)
                            <span class="px-2 py-0.5 bg-slate-50 text-slate-600 text-xs font-medium rounded border border-slate-100">
                                {{ trim($keyword) }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- System Status Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-100">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-600">
                            <x-ui-icon name="info" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ __('cms.settings.system_status') }}</h3>
                            <p class="text-xs text-[#c0c1c6] uppercase tracking-wide">{{ __('cms.settings.technical_info') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                            <span class="text-sm text-slate-600">{{ __('cms.settings.environment') }}</span>
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full font-mono">
                                {{ app()->environment() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                            <span class="text-sm text-slate-600">{{ __('cms.settings.php_version') }}</span>
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full font-mono">
                                {{ phpversion() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                            <span class="text-sm text-slate-600">{{ __('cms.settings.framework') }}</span>
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-full font-mono">
                                {{ __('cms.settings.framework') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
