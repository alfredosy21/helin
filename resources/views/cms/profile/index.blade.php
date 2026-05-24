{{-- SINGLE ROOT ELEMENT --}}
<div class="min-h-screen pb-12 bg-[#f8fafc]">

    <!-- Header Section -->
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <x-cms-breadcrumb  section="cms.profile.my_profile">
                    <x-slot name="sectionIcon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.25-.33-7.5-1.632z"/>
                        </svg>
                    </x-slot>
                </x-cms-breadcrumb>
                <p class="text-sm text-slate-500 mt-2.5">
                    {{ __('cms.profile.title') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="save" wire:loading.attr="disabled" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <x-ui-icon name="save" class="w-4 h-4 mr-2" />
                    {{ __('cms.profile.update_button') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-6 space-y-6">

        <!-- Profile Header with Avatar -->
        <div class="bg-white rounded-xl border border-slate-100 p-6">
            <div class="flex flex-col md:flex-row gap-6 items-start">
                <!-- Avatar Section -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-xl overflow-hidden border-4 border-slate-50">
                            @if($image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif($current_image)
                            <img src="{{ asset('storage/' . $current_image) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                <x-ui-icon name="user" class="w-12 h-12 text-slate-300" />
                            </div>
                            @endif

                            <div wire:loading wire:target="image" class="absolute inset-0 bg-slate-900/50 flex items-center justify-center">
                                <x-ui-icon name="loader" class="w-6 h-6 text-white animate-spin" />
                            </div>
                        </div>

                        @if($current_image)
                        <button wire:click="removeImage" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center shadow-sm transition-colors cursor-pointer border-none">
                            <x-ui-icon name="trash-2" class="w-4 h-4" />
                        </button>
                        @endif
                    </div>

                    <div class="mt-4">
                        <label class="flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-medium cursor-pointer hover:bg-[#079d8b] transition-colors text-sm">
                            <x-ui-icon name="upload-cloud" class="w-4 h-4" />
                            <span>{{ __('cms.profile.upload_photo') }}</span>
                            <input type="file" wire:model="image" class="hidden" accept="image/*" />
                        </label>
                        @error('image') <p class="text-center text-xs text-red-500 font-medium mt-2">{{ $message }}</p> @enderror
                        <p class="text-center text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wide">{{ __('cms.profile.photo_formats') }}</p>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        {{ __('cms.profile.personal_info') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <x-ui-input label="{{ __('cms.profile.full_name') }}" wire:model="name" icon="user" required />
                            @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <x-ui-input label="{{ __('cms.profile.email') }}" wire:model="email" icon="mail" required />
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
                            <textarea wire:model="biography" rows="3" placeholder="{{ __('cms.profile.biography_placeholder') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors resize-none"></textarea>
                            @error('biography') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Section -->
        <div class="bg-white rounded-xl border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                <div class="w-1.5 h-6 bg-red-500 rounded-full"></div>
                {{ __('cms.profile.security_title') }}
            </h2>

            <div class="space-y-6">
                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider mb-1">{{ __('cms.profile.current_password') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="current_password" wire:model="current_password" required
                                   class="w-full px-3 py-2 pr-10 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                            <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg id="current_password_icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider mb-1">{{ __('cms.profile.new_password') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="new_password" wire:model="new_password" required
                                   class="w-full px-3 py-2 pr-10 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                            <button type="button" onclick="togglePassword('new_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg id="new_password_icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('new_password') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider mb-1">{{ __('cms.profile.confirm_password') }}</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                   class="w-full px-3 py-2 pr-10 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg id="password_confirmation_icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Security Actions -->
                <div class="border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                                <x-ui-icon name="shield-alert" class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ __('cms.profile.security_zone') }}</h4>
                                <p class="text-xs text-slate-500">{{ __('cms.profile.password_recommendation') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button onclick="confirmCloseAllSessions()" class="rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors px-4 py-2.5 cursor-pointer">
                                {{ __('cms.profile.close_sessions') }}
                            </button>
                            <button wire:click="savePassword" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer gap-2">
                                <span wire:loading wire:target="savePassword">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <span wire:loading.remove wire:target="savePassword">{{ __('cms.profile.update_password') }}</span>
                                <span wire:loading wire:target="savePassword">{{ __('cms.profile.update_password') }}</span>
                            </button>
                        </div>
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

    function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-1.29m15.532 1.29l-3.29-1.29m0 0a3 3 0 10-4.242-4.242m0 0l-3.29 1.29m3.29-1.29L12 15.25"/>
        `;
    } else {
    passwordInput.type = 'password';
    icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
    }
</script>
