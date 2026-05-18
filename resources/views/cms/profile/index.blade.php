{{-- SINGLE ROOT ELEMENT --}}
<div class="min-h-screen pb-12 bg-[#f8fafc]">

    <!-- Header Section -->
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <x-cms-breadcrumb module="cms.profile.breadcrumb">
                    <x-slot name="moduleIcon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.25-.33-7.5-1.632z"/>
                        </svg>
                    </x-slot>
                </x-cms-breadcrumb>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('cms.profile.title') }}
                </h1>
            </div>

            <div class="flex items-center">
                <x-ui-button variant="primary" wire:click="save" wire:loading.attr="disabled">
                    <x-ui-icon name="save" class="w-5 h-5 mr-2" />
                    {{ __('cms.profile.update_button') }}
                </x-ui-button>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="px-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Avatar & Quick Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-100">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#c0c1c6] mb-6">{{ __('cms.profile.avatar_title') }}</h2>

                <div class="flex flex-col items-center space-y-6">
                    <div class="relative">
                        <div class="w-48 h-48 rounded-2xl overflow-hidden border-4 border-slate-50 relative">
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif($current_image)
                                <img src="{{ asset('storage/' . $current_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                    <x-ui-icon name="user" class="w-20 h-20 text-slate-300" />
                                </div>
                            @endif

                            <!-- Loading Overlay -->
                            <div wire:loading wire:target="image" class="absolute inset-0 bg-slate-900/50 flex items-center justify-center">
                                <x-ui-icon name="loader" class="w-8 h-8 text-white animate-spin" />
                            </div>
                        </div>

                        @if($current_image)
                            <button wire:click="removeImage" class="absolute -top-2 -right-2 w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-xl flex items-center justify-center shadow-sm transition-colors cursor-pointer border-none">
                                <x-ui-icon name="trash-2" class="w-5 h-5" />
                            </button>
                        @endif
                    </div>

                    <div class="w-full">
                        <label class="flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-lg font-medium cursor-pointer hover:bg-slate-800 transition-colors">
                            <x-ui-icon name="upload-cloud" class="w-5 h-5" />
                            <span>{{ __('cms.profile.upload_photo') }}</span>
                            <input type="file" wire:model="image" class="hidden" accept="image/*" />
                        </label>
                        @error('image') <p class="text-center text-xs text-red-500 font-medium mt-2">{{ $message }}</p> @enderror
                        <p class="text-center text-[10px] text-slate-400 mt-2 uppercase font-bold tracking-wide">{{ __('cms.profile.photo_formats') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Personal Data & Security -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Personal Information Card -->
            <div class="bg-white p-6 rounded-xl border border-slate-100">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    {{ __('cms.profile.personal_info') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <x-ui-input label="{{ __('cms.profile.full_name') }}" wire:model="name" icon="user" />
                        @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <x-ui-input label="{{ __('cms.profile.email') }}" wire:model="email" icon="mail" />
                        @error('email') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <x-ui-input label="{{ __('cms.profile.department') }}" wire:model="department" placeholder="{{ __('cms.profile.department_placeholder') }}" icon="building" />
                        @error('department') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <x-ui-input label="{{ __('cms.profile.position') }}" wire:model="position" placeholder="{{ __('cms.profile.position_placeholder') }}" icon="award" />
                        @error('position') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <x-ui-input label="{{ __('cms.profile.phone') }}" wire:model="phone" placeholder="{{ __('cms.profile.phone_placeholder') }}" icon="phone" />
                        @error('phone') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider mb-1">{{ __('cms.profile.biography') }}</label>
                        <textarea wire:model="biography" rows="4" placeholder="{{ __('cms.profile.biography_placeholder') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none"></textarea>
                        @error('biography') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="bg-white p-6 rounded-xl border border-slate-100">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-red-500 rounded-full"></div>
                    {{ __('cms.profile.security_title') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <x-ui-input type="password" label="{{ __('cms.profile.current_password') }}" wire:model="current_password" />
                        @error('current_password') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <x-ui-input type="password" label="{{ __('cms.profile.new_password') }}" wire:model="new_password" />
                        @error('new_password') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <x-ui-input type="password" label="{{ __('cms.profile.confirm_password') }}" wire:model="password_confirmation" />
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                            <x-ui-icon name="shield-alert" class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ __('cms.profile.security_zone') }}</h4>
                            <p class="text-xs text-slate-500">{{ __('cms.profile.password_recommendation') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3 w-full sm:w-auto">
                        <x-ui-button variant="secondary" onclick="confirmCloseAllSessions()" class="flex-1 sm:flex-none text-red-500 font-medium border-red-100 hover:bg-red-50">
                            {{ __('cms.profile.close_sessions') }}
                        </x-ui-button>
                        <x-ui-button wire:click="savePassword" class="flex-1 sm:flex-none bg-slate-900 text-white font-medium">
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
