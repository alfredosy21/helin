@extends('cms.layouts.auth')

@section('title', __('cms.lock.title'))

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-grid-slate-100 dark:bg-grid-slate-900 opacity-50"></div>

    <!-- Glass Card -->
    <div class="relative w-full max-w-md">
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-2xl shadow-slate-900/10 dark:shadow-black/20 border border-white/20 dark:border-slate-700/20 p-8">

            <!-- Lock Icon & User Info -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/25 mb-4">
                    <x-ui-icon name="lock" class="text-white text-2xl" />
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                    {{ __('cms.lock.title') }}
                </h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    {{ __('cms.lock.subtitle') }}
                </p>
                @if($user)
                    <div class="mt-4 flex items-center justify-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Unlock Form -->
            <form wire:submit="unlock" class="space-y-6">

                <!-- Form Fields -->
                <div class="space-y-5">
                    <!-- Email Field (pre-filled) -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('cms.lock.email') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-ui-icon name="mail" class="h-5 w-5 text-slate-400 group-focus-within:text-primary-500 transition-colors" />
                            </div>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                value="{{ $user ? $user->email : old('email') }}"
                                class="w-full pl-10 pr-4 py-3 bg-white/50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm"
                                placeholder="{{ __('cms.lock.email_placeholder') }}"
                                autocomplete="email"
                                readonly
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('cms.general.password') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-ui-icon name="key" class="h-5 w-5 text-slate-400 group-focus-within:text-primary-500 transition-colors" />
                            </div>
                            <input
                                wire:model="password"
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="w-full pl-10 pr-12 py-3 bg-white/50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm {{ $errors->get('password') ? 'border-red-500' : '' }}"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                autofocus
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="this.parentElement.querySelector('input').type = this.parentElement.querySelector('input').type === 'password' ? 'text' : 'password'"
                            >
                                <div>
                                    <svg class="w-4 h-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="bg-primary-50 dark:bg-primary-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-ui-icon name="shield" class="w-4 h-4 text-primary-400" />
                        </div>
                        <div class="ml-2">
                            <p class="text-xs text-primary-800 dark:text-primary-200">
                                {{ __('cms.lock.unlock_notice') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Unlock Button -->
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading wire:target="unlock" class="mr-2">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('cms.general.unlocking') }}
                        </span>
                        <span wire:loading.remove wire:target="unlock" class="mr-2">{{ __('cms.lock.unlock_session') }}</span>
                        <x-ui-icon name="unlock" class="w-4 h-4" />
                    </button>

                    <!-- Logout Button -->
                    <button wire:click="logout"
                            class="w-full flex justify-center items-center py-3 px-4 border border-slate-300 dark:border-slate-600 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-300 bg-white/50 dark:bg-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-600/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200 backdrop-blur-sm"
                    >
                        <span class="mr-2">{{ __('cms.lock.close_session') }}</span>
                        <x-ui-icon name="log-out" class="w-4 h-4" />
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
