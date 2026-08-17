<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 bg-grid-slate-100 dark:bg-grid-slate-900 opacity-50 pointer-events-none"></div>

    {{-- Glass Card --}}
    <div class="relative w-full max-w-md">
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700/20 p-8">

            {{-- Logo Section --}}
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-2xl bg-[rgb(9,182,162)] shadow-lg shadow-[rgb(9,182,162)]/25 mb-4">
                    <span class="text-white font-bold text-2xl">H</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ __('cms.forgot_password.title') }}
                </h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    {{ __('cms.forgot_password.subtitle') }}
                </p>
            </div>

            {{-- Success State --}}
            @if($requestSent)
            <div class="text-center space-y-6">
                <div class="mx-auto w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">{{ __('cms.forgot_password.success_title') }}</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        {{ __('cms.forgot_password.success_message') }}
                    </p>
                </div>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center w-full py-3 px-4 rounded-xl text-white bg-[rgb(9,182,162)] hover:bg-[rgb(6,122,108)] transition-all text-sm font-semibold">
                    {{ __('cms.forgot_password.back_to_login') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
            @else
            {{-- Form --}}
            <form wire:submit.prevent="sendResetLink" class="space-y-6">
                <div class="group">
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('cms.forgot_password.email_label') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-ui-icon name="mail" class="h-5 w-5 text-slate-400" />
                        </div>
                        <input
                            wire:model.live="email"
                            id="email"
                            type="email"
                            required
                            class="w-full pl-10 pr-4 py-3 bg-white/50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-0 focus:border-slate-200 dark:focus:border-slate-600 transition-all {{ $errors->has('email') ? 'border-red-500' : '' }}"
                            placeholder="{{ __('cms.forgot_password.email_placeholder') }}"
                            >
                    </div>
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center py-3 px-4 rounded-xl text-white bg-[rgb(9,182,162)] hover:bg-[rgb(6,122,108)] transition-all disabled:opacity-50 text-sm font-semibold"
                        >
                        <span wire:loading wire:target="sendResetLink" class="mr-2">
                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="sendResetLink">
                            {{ __('cms.forgot_password.send_button') }}
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        {{ __('cms.forgot_password.back_to_login') }}
                    </a>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
