@extends('cms.layouts.auth')

@section('title', 'Página No Encontrada')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-blue-900 dark:to-indigo-900">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-grid-slate-100 dark:bg-grid-slate-900 opacity-50"></div>

    <!-- Error Content -->
    <div class="relative w-full max-w-md">
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-2xl shadow-slate-900/10 dark:shadow-black/20 border border-white/20 dark:border-slate-700/20 p-8 text-center">

            <!-- Error Icon -->
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-red-500/25">
                <div class="text-white">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <div class="text-6xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent mb-4">
                404
            </div>

            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                Página No Encontrada
            </h1>

            <!-- Error Description -->
            <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                Lo sentimos, la página que estás buscando no existe o ha sido movida.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <!-- Go Home Button -->
                <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-blue-500/25 transform hover:scale-[1.02]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a2 2 0 002 2h10a2 2 0 002-2V10"></path>
                    </svg>
                    Ir al Inicio
                </a>

                <!-- Back Button -->
                <button onclick="history.back()" class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver Atrás
                </button>
            </div>

            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Si crees que esto es un error, por favor contacta al administrador del sistema.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="mt-6 flex justify-center space-x-4">
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
