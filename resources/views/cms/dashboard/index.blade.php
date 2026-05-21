<!-- Modern Dashboard Design -->
<div class="min-h-screen bg-[#f8fafc]">

    <!-- Minimalist Header -->
    <div class="bg-white border-b border-slate-100">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- User Avatar -->
                    <div class="relative">
                        @if(auth()->user()->image)
                        <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100">
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        @endif
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>

                    <!-- Welcome Text -->
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="text-xs text-[#c0c1c6]">
                            {{ now()->format('d M Y') }} • {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Refresh Button -->
                <button wire:click="refreshStats" class="p-2 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors border-none cursor-pointer">
                    <x-ui-icon name="refresh-cw" class="w-5 h-5 text-slate-500" />
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Users Card -->
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <x-ui-icon name="users" class="w-5 h-5 text-blue-600" />
                        </div>
                        <div class="flex items-center gap-1 px-2 py-0.5 bg-blue-100 rounded-full">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                            <span class="text-[10px] font-semibold text-blue-700">+{{ $stats['new_users'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-blue-400 uppercase tracking-wide">Usuarios Totales</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_users'] ?? 0) }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <x-ui-icon name="trending-up" class="w-3 h-3 text-blue-500" />
                            <span>{{ $stats['new_users'] ?? 0 }} nuevos este mes</span>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <x-ui-icon name="package" class="w-5 h-5 text-emerald-600" />
                        </div>
                        <div class="flex items-center gap-1 px-2 py-0.5 bg-emerald-100 rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            <span class="text-[10px] font-semibold text-emerald-700">+{{ $stats['new_products'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-emerald-400 uppercase tracking-wide">Productos</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_products'] ?? 0) }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <x-ui-icon name="trending-up" class="w-3 h-3 text-emerald-500" />
                            <span>{{ $stats['new_products'] ?? 0 }} nuevos este mes</span>
                        </div>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-purple-50 rounded-xl p-6 border border-purple-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <x-ui-icon name="folder" class="w-5 h-5 text-purple-600" />
                        </div>
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-purple-400 uppercase tracking-wide">Categorías</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_categories'] ?? 0) }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <x-ui-icon name="layers" class="w-3 h-3 text-purple-500" />
                            <span>Organización completa</span>
                        </div>
                    </div>
                </div>

                <!-- System Status Card -->
                <div class="bg-green-50 rounded-xl p-6 border border-green-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <x-ui-icon name="zap" class="w-5 h-5 text-green-600" />
                        </div>
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-medium text-green-400 uppercase tracking-wide">Sistema</p>
                        <p class="text-2xl font-bold text-green-600">Online</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <x-ui-icon name="check-circle" class="w-3 h-3 text-green-500" />
                            <span>99.9% uptime</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Live Activity Feed -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="pulse" class="w-5 h-5 text-slate-600" />
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">{{ __('cms.dashboard.live_feed') }}</h3>
                                    <p class="text-xs text-[#c0c1c6]">{{ __('cms.dashboard.real_time') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-[10px] font-semibold text-green-600 uppercase tracking-wider">{{ __('cms.dashboard.live') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Cards -->
                    <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                        @forelse ($recentActivity as $activity)
                        <div class="bg-white rounded-xl p-4 border border-slate-100">
                            <div class="flex items-start gap-3">
                                <!-- User Avatar -->
                                <div class="relative flex-shrink-0">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-slate-600 font-bold text-sm">{{ substr($activity['user'], 0, 1) }}</span>
                                    </div>
                                </div>

                                <!-- Activity Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-sm text-slate-900">{{ $activity['user'] }}</span>
                                                <span class="text-[10px] text-[#c0c1c6] font-medium uppercase tracking-wider">{{ $activity['time'] }}</span>
                                            </div>
                                            <p class="text-xs text-slate-600 leading-relaxed">
                                                {{ $activity['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto">
                                <x-ui-icon name="monitor-off" class="w-8 h-8 text-slate-300" />
                            </div>
                            <div class="mt-4 space-y-1">
                                <h4 class="text-sm font-semibold text-slate-900">{{ __('cms.dashboard.system_waiting') }}</h4>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                                    {{ __('cms.dashboard.no_activity') }}
                                </p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="space-y-8">
                    <!-- Quick Actions -->
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <x-ui-icon name="zap" class="w-5 h-5 text-blue-600" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">{{ __('cms.general.quick_actions') }}</h3>
                                <p class="text-xs text-[#c0c1c6]">{{ __('cms.dashboard.quick_actions_subtitle') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('catalog.products.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-blue-200">
                                    <x-ui-icon name="package" class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-blue-700 uppercase tracking-wider">{{ __('cms.general.product') }}</span>
                            </a>
                            <a href="{{ route('catalog.categories.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-blue-200">
                                    <x-ui-icon name="folder" class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-blue-700 uppercase tracking-wider">{{ __('cms.general.category') }}</span>
                            </a>
                            <a href="{{ route('catalog.brands.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-blue-200">
                                    <x-ui-icon name="tag" class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-blue-700 uppercase tracking-wider">{{ __('cms.general.brand') }}</span>
                            </a>
                            <a href="{{ route('settings.index') }}" class="group flex flex-col items-center gap-2 p-3 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-blue-200">
                                    <x-ui-icon name="settings" class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-blue-700 uppercase tracking-wider">{{ __('cms.general.config') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
