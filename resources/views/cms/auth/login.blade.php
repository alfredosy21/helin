<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-md shadow-primary-500/10 border border-line p-8">

            <!-- Title Section -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-heading">
                    {{ $isLocked ? __('cms.login.unlock_session') : __('cms.login.welcome_back') }}
                </h1>
                <p class="mt-2 text-sm text-body">
                    {{ $isLocked ? __('cms.login.enter_password_continue') : __('cms.login.enter_credentials') }}
                </p>
            </div>

            <!-- Login Form -->
            <form wire:submit.prevent="{{ $isLocked ? 'unlock' : 'login' }}" class="space-y-6">
                <div class="space-y-5">
                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
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
                                class="w-full pl-10 pr-4 py-3 bg-white border border-line rounded-xl text-slate-900 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all {{ $errors->has('email') ? 'border-red-500' : '' }}"
                                placeholder="{{ __('cms.login.email') }}"
                                >
                        </div>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
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
                                class="w-full pl-10 pr-12 py-3 bg-white border border-line rounded-xl text-slate-900 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all {{ $errors->has('password') ? 'border-red-500' : '' }}"
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
                            class="h-4 w-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500"
                            onchange="console.log('Remember checkbox changed:', this.checked)"
                            >
                        <label for="remember" class="ml-2 block text-sm text-slate-700 cursor-pointer hover:text-primary-600 transition-colors">
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
                        class="w-full flex justify-center items-center py-3 px-4 rounded-xl text-white bg-primary-500 hover:bg-primary-600 transition-all disabled:opacity-50"
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
