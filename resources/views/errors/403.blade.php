@extends('cms.layouts.auth')

@section('title', __('cms.errors.403_title'))

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-red-50 via-orange-50 to-amber-100 dark:from-red-900 dark:via-orange-900 dark:to-amber-900">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-grid-red-100 dark:bg-grid-red-900 opacity-50"></div>

    <!-- Error Content -->
    <div class="relative w-full max-w-md">
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-2xl shadow-slate-900/10 dark:shadow-black/20 border border-white/20 dark:border-slate-700/20 p-8 text-center">

            <!-- Error Icon -->
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-red-600 to-orange-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-red-500/25">
                <div class="text-white">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <div class="text-6xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent mb-4">
                403
            </div>

            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                {{ __('cms.errors.403_title') }}
            </h1>

            <!-- Error Description -->
            <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                {{ __('cms.errors.403_description') }}
            </p>

            <!-- Additional Info -->
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm text-red-800 dark:text-red-200">
                            {{ __('cms.errors.403_admin_required') }}
                        </p>
                        <p class="text-xs text-red-700 dark:text-red-300 mt-1">
                            {{ __('cms.errors.403_contact_admin') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <!-- Go Back Button -->
                <button onclick="history.back()" class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('cms.errors.go_back') }}
                </button>

                <!-- Go Home Button -->
                <a href="{{ route('dashboard') }}" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 shadow-lg hover:shadow-red-500/25">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a2 2 0 002 2h10a2 2 0 002-2v-10a2 2 0 00-2-2H5a2 2 0 00-2 2v-10a2 2 0 00-2-2H2a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                    </svg>
                    {{ __('cms.errors.go_home') }}
                </a>
            </div>

            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('cms.errors.403_help') }}
                </p>
            </div>

            <!-- Quick Links -->
            <div class="mt-6 flex justify-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-sm text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                    {{ __('cms.errors.dashboard') }}
                </a>
                <a href="{{ route('profile.show') }}" class="text-sm text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                    {{ __('cms.errors.my_profile') }}
                </a>
                @if(auth()->check())
                    <a href="{{ route('logout') }}" class="text-sm text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        {{ __('cms.errors.logout') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        {{ __('cms.errors.login') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
