<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Todo el contenido debe estar aquí dentro --}}

    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-grid-slate-100 dark:bg-grid-slate-900 opacity-50 pointer-events-none"></div>

    <!-- Glass Card -->
    <div class="relative w-full max-w-md">
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700/20 p-8">

            <!-- Logo Section -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-2xl bg-[rgb(9,182,162)] shadow-lg shadow-[rgb(9,182,162)]/25 mb-4">
                    <span class="text-white font-bold text-2xl">H</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $isLocked ? __('cms.login.unlock_session') : __('cms.login.welcome_back') }}
                </h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    {{ $isLocked ? __('cms.login.enter_password_continue') : __('cms.login.enter_credentials') }}
                </p>
            </div>

            <!-- Login Form -->
            <form wire:submit.prevent="{{ $isLocked ? 'unlock' : 'login' }}" class="space-y-6">
                <div class="space-y-5">
                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('cms.login.email') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-ui-icon name="mail" class="h-5 w-5 text-slate-400" />
                            </div>
                            <input
                                wire:model="email"
                                id="email"
                                type="email"
                                required
                                @if($isLocked) readonly @endif
                                class="w-full pl-10 pr-4 py-3 bg-white/50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-0 focus:border-slate-200 dark:focus:border-slate-600 transition-all {{ $errors->has('email') ? 'border-red-500' : '' }}"
                                placeholder="{{ __('cms.login.email') }}"
                            >
                        </div>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('cms.login.password') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-ui-icon name="lock" class="h-5 w-5 text-slate-400" />
                            </div>
                            <input
                                wire:model="password"
                                id="password"
                                type="password"
                                required
                                class="w-full pl-10 pr-12 py-3 bg-white/50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-0 focus:border-slate-200 dark:focus:border-slate-600 transition-all {{ $errors->has('password') ? 'border-red-500' : '' }}"
                                placeholder="••••••••"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                                <x-ui-icon name="eye" class="h-5 w-5 text-slate-400 hover:text-slate-600" />
                            </button>
                        </div>
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if(!$isLocked)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            wire:model="remember"
                            id="remember"
                            type="checkbox"
                            class="h-4 w-4 text-primary-600 border-slate-300 rounded focus:ring-blue-500"
                            onchange="console.log('Remember checkbox changed:', this.checked)"
                        >
                        <label for="remember" class="ml-2 block text-sm text-slate-700 dark:text-slate-300 cursor-pointer hover:text-primary-600 transition-colors">
                            {{ __('cms.login.remember_me') }}
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('cms.login.forgot_password') }}</a>
                    </div>
                </div>
                @endif

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center py-3 px-4 rounded-xl text-white bg-[rgb(9,182,162)] hover:bg-[rgb(6,122,108)] transition-all disabled:opacity-50"
                    >
                        <span wire:loading wire:target="{{ $isLocked ? 'unlock' : 'login' }}" class="mr-2">
                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>

                        <span>
                            {{ $isLocked ? __('cms.login.unlock_button') : __('cms.login.login_button') }}
                        </span>

                        <x-ui-icon name="arrow-right" class="w-4 h-4 ml-2" />
                    </button>
                </div>
            </form>
        </div>
    </div>

    </div>
