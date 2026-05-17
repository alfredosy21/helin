<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-blue-900/20">

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-emerald-400/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <!-- Navigation & Header -->
    <div class="relative p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-4">
                <nav class="flex items-center text-sm text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="flex items-center hover:text-primary-600 transition-all duration-200 hover:scale-105">
                        <x-ui-icon name="home" class="w-4 h-4 mr-1" />
                        CMS
                    </a>
                    <x-ui-icon name="chevron-right" class="w-4 h-4 mx-2 text-slate-400" />
                    <span class="flex items-center text-slate-900 dark:text-white font-medium">
                        <x-ui-icon name="settings" class="w-4 h-4 mr-2 text-primary-600" />
                        {{ __('cms.settings.title') }}
                    </span>
                </nav>
                <div class="space-y-2">
                    <h1 class="text-5xl font-black bg-gradient-to-r from-slate-900 via-blue-800 to-slate-900 dark:from-white dark:via-blue-200 dark:to-white bg-clip-text text-transparent tracking-tight">
                        {{ $isEditing ? __('cms.settings.editing') : __('cms.settings.title') }}
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 text-lg">
                        {{ $isEditing ? __('cms.settings.edit_subtitle') : __('cms.settings.view_subtitle') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($isEditing)
                    <x-ui-button variant="secondary" wire:click="toggleEdit" class="group relative overflow-hidden hover:scale-105 transition-all duration-200">
                        <span class="relative z-10 flex items-center">
                            <x-ui-icon name="x" class="w-4 h-4 mr-2" />
                            {{ __('cms.settings.cancel') }}
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-100 to-slate-200 dark:from-gray-700 dark:to-gray-600 group-hover:from-slate-200 group-hover:to-slate-300 dark:group-hover:from-gray-600 dark:group-hover:to-gray-500 transition-all duration-200"></div>
                    </x-ui-button>
                    <x-ui-button variant="primary" wire:click="save" class="group relative overflow-hidden hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl shadow-blue-500/20">
                        <span class="relative z-10 flex items-center">
                            <x-ui-icon name="save" class="w-4 h-4 mr-2" />
                            {{ __('cms.settings.save') }}
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 group-hover:from-blue-700 group-hover:to-blue-800 transition-all duration-200"></div>
                    </x-ui-button>
                @else
                    <x-ui-button variant="primary" wire:click="toggleEdit" class="group relative overflow-hidden hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl shadow-slate-900/20 dark:shadow-blue-600/20">
                        <span class="relative z-10 flex items-center">
                            <x-ui-icon name="edit-3" class="w-4 h-4 mr-2" />
                            {{ __('cms.settings.edit_params') }}
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-blue-600 dark:to-blue-700 group-hover:from-slate-800 group-hover:to-slate-900 dark:group-hover:from-blue-700 dark:group-hover:to-blue-800 transition-all duration-200"></div>
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
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/20 dark:border-gray-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-[2.5rem]"></div>
                    <div class="relative">
                        <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
                            <div class="w-3 h-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full shadow-lg shadow-blue-500/30"></div>
                            <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">{{ __('cms.settings.corporate_info') }}</span>
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-ui-input label="{{ __('cms.settings.company_name') }}" wire:model="name" />
                            <x-ui-input label="{{ __('cms.settings.contact_email') }}" wire:model="email" />
                            <x-ui-input label="{{ __('cms.settings.main_phone') }}" wire:model="phone" />
                            <x-ui-input label="{{ __('cms.settings.business_hours') }}" wire:model="shedule" />
                            <div class="md:col-span-2">
                                <x-ui-textarea label="{{ __('cms.settings.physical_address') }}" wire:model="address" rows="2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Corporate Image Card -->
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/20 dark:border-gray-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 rounded-[2.5rem]"></div>
                    <div class="relative">
                        <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                            <div class="w-3 h-8 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-full shadow-lg shadow-emerald-500/30"></div>
                            <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">{{ __('cms.settings.corporate_image') }}</span>
                        </h2>
                        <div class="flex flex-col items-center">
                            <div class="relative group">
                                <!-- Main Upload Area -->
                                <div class="w-full h-56 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-[2rem] border-2 border-dashed border-slate-200/50 dark:border-gray-600/50 flex items-center justify-center overflow-hidden mb-6 group-hover:border-emerald-400/50 group-hover:bg-gradient-to-br group-hover:from-emerald-50 group-hover:via-white group-hover:to-emerald-100 dark:group-hover:from-emerald-900 dark:group-hover:via-gray-800 dark:group-hover:to-emerald-700 transition-all duration-500 relative">

                                    <!-- Background Pattern -->
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/10 to-teal-400/10 rounded-[2rem]"></div>
                                        <div class="absolute top-0 left-0 w-32 h-32 bg-emerald-400/5 rounded-full blur-2xl"></div>
                                        <div class="absolute bottom-0 right-0 w-24 h-24 bg-teal-400/5 rounded-full blur-2xl"></div>
                                    </div>

                                    <!-- Content -->
                                    @if($image)
                                        <div class="relative z-10">
                                            <img src="{{ $image->temporaryUrl() }}" class="h-full object-contain p-6 group-hover:scale-105 transition-transform duration-500 rounded-xl shadow-lg group-hover:shadow-emerald-500/20" />
                                            <div class="absolute top-2 right-2 bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                {{ __('cms.settings.new_image') }}
                                            </div>
                                        </div>
                                    @elseif($current_image)
                                        <div class="relative z-10">
                                            <img src="{{ asset('storage/' . $current_image) }}" class="h-full object-contain p-6 group-hover:scale-105 transition-transform duration-500 rounded-xl shadow-lg group-hover:shadow-emerald-500/20" />
                                            <div class="absolute top-2 right-2 bg-primary-500 text-white px-3 py-1 rounded-full text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                {{ __('cms.settings.current_image') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="relative z-10 text-center">
                                            <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900 dark:to-teal-800 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                                                <x-ui-icon name="image" class="w-10 h-10 text-emerald-600 dark:text-emerald-400" />
                                            </div>
                                            <div>
                                                <p class="text-base font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('cms.settings.no_image') }}</p>
                                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('cms.settings.drag_image') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-[2rem] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="w-12 h-12 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center mb-2 backdrop-blur-sm">
                                                <x-ui-icon name="upload-cloud" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                                            </div>
                                            <p class="text-sm font-medium text-white bg-emerald-600/80 backdrop-blur-sm px-3 py-1 rounded-full">
                                                Click para seleccionar
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom File Input -->
                                <div class="relative">
                                    <input type="file" wire:model="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                    <div class="relative group">
                                        <div class="flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full font-medium shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 cursor-pointer group-hover:scale-105">
                                            <x-ui-icon name="folder-open" class="w-5 h-5" />
                                            <span>Seleccionar archivo</span>
                                            <x-ui-icon name="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" />
                                        </div>
                                        <div class="text-center mt-2">
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                Formatos: JPG, PNG, GIF (Máx. 1MB)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row: Social Media & SEO -->
            <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/20 dark:border-gray-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 rounded-[2.5rem]"></div>
                <div class="relative">
                    <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
                        <div class="w-3 h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full shadow-lg shadow-purple-500/30"></div>
                        <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Redes sociales y SEO</span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui-input label="Facebook url" wire:model="facebook" />
                        <x-ui-input label="Instagram url" wire:model="instagram" />
                        <x-ui-input label="Linkedin url" wire:model="linkedin" />
                        <x-ui-input label="Youtube url" wire:model="youtube" />
                        <div class="md:col-span-2 space-y-4">
                            <x-ui-input label="Palabras clave" wire:model="keywords" />
                            <x-ui-textarea label="Descripción corta (SEO)" wire:model="description" rows="2" />
                            <x-ui-textarea label="Descripción del sistema" wire:model="settings_description" rows="3" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row: Footer Card - Full Width -->
            <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/20 dark:border-gray-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-amber-500/5 rounded-[2.5rem]"></div>
                <div class="relative">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-4 h-12 bg-gradient-to-r from-orange-600 to-amber-600 rounded-full shadow-lg shadow-orange-500/30"></div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">Pie de página</h2>
                    </div>
                    <div class="max-w-2xl">
                        <x-ui-input label="Texto de copyright" wire:model="copy" placeholder=" 2026 Helin CMS - Todos los derechos reservados" />
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                            Este texto aparecerá en el pie de página de todo el sitio web. Usa etiquetas HTML si necesitas formato especial.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- READ MODE: CARDS -->
        <div class="relative px-6 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Corporate Information Card -->
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/20 dark:border-gray-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-[2.5rem]"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 rounded-2xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/20">
                            <x-ui-icon name="building" class="w-8 h-8" />
                        </div>
                        <h3 class="text-2xl font-black bg-gradient-to-r from-slate-900 to-blue-800 dark:from-white dark:to-blue-200 bg-clip-text text-transparent mb-3">
                            {{ $name ?: 'Sin nombre' }}
                        </h3>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-6">Datos de contacto</p>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3 group">
                                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                    <x-ui-icon name="mail" class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Email</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $email ?: 'No configurado' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 group">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                    <x-ui-icon name="phone" class="w-4 h-4 text-green-600 dark:text-green-400" />
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Teléfono</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $phone ?: 'No configurado' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 group">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                    <x-ui-icon name="clock" class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Horario</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $shedule ?: 'No configurado' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO & Metadata Card -->
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/20 dark:border-gray-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 rounded-[2.5rem]"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900 dark:to-emerald-800 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-emerald-500/20">
                            <x-ui-icon name="search" class="w-8 h-8" />
                        </div>
                        <h3 class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-3">SEO & Metadatos</h3>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-6">Posicionamiento</p>
                        <div class="space-y-4">
                            <div class="p-4 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-slate-200/50 dark:border-gray-600/50">
                                <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed">
                                    {{ $description ?: 'No hay descripción SEO configurada. Añade una descripción para mejorar el posicionamiento en buscadores.' }}
                                </p>
                            </div>
                            @if($keywords)
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $keywords) as $keyword)
                                <span class="px-3 py-1 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900 dark:to-teal-900 text-emerald-700 dark:text-emerald-300 text-xs font-medium rounded-full border border-emerald-200 dark:border-emerald-700">
                                    {{ trim($keyword) }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- System Status Card -->
                <div class="group relative bg-gradient-to-br from-slate-900 to-slate-800 p-8 rounded-[2.5rem] shadow-2xl text-white hover:shadow-3xl transition-all duration-300 hover:scale-[1.02] border border-slate-700/50">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 rounded-[2.5rem]"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-2xl flex items-center justify-center text-primary-400 mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/20">
                            <x-ui-icon name="info" class="w-8 h-8" />
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Estado del sistema</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Información técnica</p>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg border border-white/10 backdrop-blur-sm">
                                <span class="text-sm font-medium">Ambiente</span>
                                <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold rounded-full font-mono">
                                    {{ app()->environment() }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg border border-white/10 backdrop-blur-sm">
                                <span class="text-sm font-medium">Versión PHP</span>
                                <span class="px-3 py-1 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-xs font-bold rounded-full font-mono">
                                    {{ phpversion() }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg border border-white/10 backdrop-blur-sm">
                                <span class="text-sm font-medium">Framework</span>
                                <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-xs font-bold rounded-full font-mono">
                                    Laravel
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Normalizar parámetro de Livewire 3 (puede venir como array u objeto)
function normalizeLivewireEvent(raw) {
    if (Array.isArray(raw) && raw.length > 0) return raw[0];
    if (raw && typeof raw === 'object') return raw;
    return {};
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
