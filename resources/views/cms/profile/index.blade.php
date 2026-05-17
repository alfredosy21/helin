{{-- SINGLE ROOT ELEMENT --}}
<div class="min-h-screen pb-12 bg-gradient-to-br from-slate-50 via-white to-blue-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-blue-900/20 relative">

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Header Section -->
    <div class="relative p-6 mb-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <x-cms-breadcrumb module="cms.profile.breadcrumb">
                    <x-slot name="moduleIcon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.25-.33-7.5-1.632z"/>
                        </svg>
                    </x-slot>
                </x-cms-breadcrumb>
                <h1 class="text-5xl font-black tracking-tight text-slate-900 dark:text-white">
                    {{ __('cms.profile.title') }}
                </h1>
            </div>

            <div class="flex items-center">
                <x-ui-button variant="primary" wire:click="save" wire:loading.attr="disabled" class="group relative overflow-hidden hover:scale-105 transition-all duration-300 shadow-xl shadow-blue-500/20 !rounded-2xl">
                    <span class="relative z-10 flex items-center px-4 py-1">
                        <x-ui-icon name="save" class="w-5 h-5 mr-2" />
                        {{ __('cms.profile.update_button') }}
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700"></div>
                </x-ui-button>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="relative px-6 grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Left Column: Avatar & Quick Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="group relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-2xl p-8 rounded-[3rem] border border-white dark:border-gray-700/50 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-emerald-500/5"></div>

                <div class="relative flex flex-col items-center space-y-8">
                    <h2 class="text-xl font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 text-sm">{{ __('cms.profile.avatar_title') }}</h2>

                    <div class="relative">
                        <div class="w-56 h-56 rounded-[2.5rem] overflow-hidden border-[6px] border-white dark:border-gray-700 shadow-2xl relative group/avatar">
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover transform group-hover/avatar:scale-110 transition-transform duration-700">
                            @elseif($current_image)
                                <img src="{{ asset('storage/' . $current_image) }}" class="w-full h-full object-cover transform group-hover/avatar:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                    <x-ui-icon name="user" class="w-24 h-24 text-slate-300 dark:text-gray-500" />
                                </div>
                            @endif

                            <!-- Loading Overlay -->
                            <div wire:loading wire:target="image" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md flex items-center justify-center">
                                <x-ui-icon name="loader" class="w-10 h-10 text-white animate-spin" />
                            </div>
                        </div>

                        @if($current_image)
                            <button wire:click="removeImage" class="absolute -top-3 -right-3 w-12 h-12 bg-red-500 hover:bg-red-600 text-white rounded-2xl flex items-center justify-center shadow-xl transition-all hover:rotate-12 active:scale-95">
                                <x-ui-icon name="trash-2" class="w-6 h-6" />
                            </button>
                        @endif
                    </div>

                    <div class="w-full">
                        <label class="group/btn relative flex items-center justify-center gap-3 px-8 py-4 bg-slate-900 dark:bg-primary-600 text-white rounded-2xl font-bold cursor-pointer overflow-hidden transition-all hover:shadow-2xl hover:shadow-blue-500/40">
                            <x-ui-icon name="upload-cloud" class="w-6 h-6 animate-bounce" />
                            <span class="relative z-10">{{ __('cms.profile.upload_photo') }}</span>
                            <input type="file" wire:model="image" class="hidden" accept="image/*" />
                        </label>
                        @error('image') <p class="text-center text-xs text-red-500 font-bold mt-3 italic">{{ $message }}</p> @enderror
                        <p class="text-center text-[10px] text-slate-400 dark:text-slate-500 mt-4 uppercase font-black tracking-tighter">{{ __('cms.profile.photo_formats') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Personal Data & Security -->
        <div class="lg:col-span-2 space-y-10">

            <!-- Personal Information Card -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-2xl p-10 rounded-[3rem] border border-white dark:border-gray-700/50 shadow-2xl shadow-slate-200/50 dark:shadow-none relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>

                <h2 class="text-2xl font-black mb-10 flex items-center gap-4 text-slate-900 dark:text-white">
                    <span class="w-10 h-2 bg-primary-600 rounded-full"></span>
                    {{ __('cms.profile.personal_info') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-ui-input label="{{ __('cms.profile.full_name') }}" wire:model="name" icon="user" class="!rounded-2xl" />
                        @error('name') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <x-ui-input label="{{ __('cms.profile.email') }}" wire:model="email" icon="mail" class="!rounded-2xl" />
                        @error('email') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                                        <div class="space-y-2">
                        <x-ui-input label="{{ __('cms.profile.department') }}" wire:model="department" placeholder="{{ __('cms.profile.department_placeholder') }}" icon="building" class="!rounded-2xl" />
                        @error('department') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <x-ui-input label="{{ __('cms.profile.position') }}" wire:model="position" placeholder="{{ __('cms.profile.position_placeholder') }}" icon="award" class="!rounded-2xl" />
                        @error('position') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <x-ui-input label="{{ __('cms.profile.phone') }}" wire:model="phone" placeholder="{{ __('cms.profile.phone_placeholder') }}" icon="phone" class="!rounded-2xl" />
                        @error('phone') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('cms.profile.biography') }}</label>
                        <textarea wire:model="biography" rows="4" placeholder="{{ __('cms.profile.biography_placeholder') }}" class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
                        @error('biography') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-2xl p-10 rounded-[3rem] border border-white dark:border-gray-700/50 shadow-2xl shadow-slate-200/50 dark:shadow-none relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>

                <h2 class="text-2xl font-black mb-10 flex items-center gap-4 text-slate-900 dark:text-white">
                    <span class="w-10 h-2 bg-red-600 rounded-full"></span>
                    {{ __('cms.profile.security_title') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <x-ui-input type="password" label="{{ __('cms.profile.current_password') }}" wire:model="current_password" class="!rounded-2xl" />
                        @error('current_password') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <x-ui-input type="password" label="{{ __('cms.profile.new_password') }}" wire:model="new_password" class="!rounded-2xl" />
                        @error('new_password') <span class="text-xs text-red-500 font-bold ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <x-ui-input type="password" label="{{ __('cms.profile.confirm_password') }}" wire:model="password_confirmation" class="!rounded-2xl" />
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-100 dark:border-gray-700/50 flex flex-col sm:flex-row gap-6 justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-50 dark:bg-red-500/10 rounded-2xl flex items-center justify-center text-red-600">
                            <x-ui-icon name="shield-alert" class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ __('cms.profile.security_zone') }}</h4>
                            <p class="text-xs text-slate-500">{{ __('cms.profile.password_recommendation') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4 w-full sm:w-auto">
                        <x-ui-button variant="secondary" onclick="confirmCloseAllSessions()" class="flex-1 sm:flex-none !rounded-2xl text-red-500 font-bold border-red-100 hover:bg-red-50">
                            {{ __('cms.profile.close_sessions') }}
                        </x-ui-button>
                        <x-ui-button wire:click="savePassword" class="flex-1 sm:flex-none !rounded-2xl bg-slate-900 dark:bg-primary-700 text-white font-bold shadow-lg">
                            <span wire:loading.remove wire:target="savePassword">{{ __('cms.profile.update_password') }}</span>
                            <x-ui-icon wire:loading wire:target="savePassword" name="loader" class="w-5 h-5 animate-spin" />
                        </x-ui-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmCloseAllSessions() {
    window.confirmAction({
        title: '{{ __('cms.profile.close_all_sessions_title') }}',
        text: '{{ __('cms.profile.close_all_sessions_text') }}',
        confirmButtonText: '{{ __('cms.profile.confirm_close_sessions') }}',
        cancelButtonText: '{{ __('cms.general.cancel') }}',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#f1f5f9',
        onConfirm: function() {
            const wireEl = document.querySelector('[wire\\:id]');
            if (wireEl) {
                Livewire.find(wireEl.getAttribute('wire:id')).closeAllSessions();
            }
        }
    });
}
</script>
