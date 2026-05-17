<!-- Modern Dashboard Design -->
<div class="min-h-screen">

    <!-- Minimalist Header -->
    <div class="bg-white dark:bg-gray-800 border-b border-slate-200/50 dark:border-gray-700/50">
        <div class="px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <!-- User Avatar -->
                    <div class="relative">
                        @if(auth()->user()->image)
                            <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-lg">
                                <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-xl">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ now()->format('d M Y') }} • {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Refresh Button -->
                <button wire:click="refreshStats" class="p-3 bg-slate-100 dark:bg-gray-700 rounded-xl hover:bg-slate-200 dark:hover:bg-gray-600 transition-colors">
                    <x-ui-icon name="refresh-cw" class="w-5 h-5 text-slate-600 dark:text-slate-400" />
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-8 py-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Users Card -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-slate-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-primary-100 dark:bg-primary-500/10 rounded-2xl flex items-center justify-center">
                                <x-ui-icon name="users" class="w-7 h-7 text-primary-600 dark:text-primary-400" />
                            </div>
                            <div class="flex items-center gap-1 px-2 py-1 bg-green-50 dark:bg-green-500/10 rounded-full">
                                <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                <span class="text-xs font-semibold text-green-700 dark:text-green-400">+{{ $stats['new_users'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('cms.dashboard.total_users') }}</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_users'] ?? 0) }}</p>
                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <x-ui-icon name="trending-up" class="w-3 h-3 text-green-500" />
                                <span>{{ $stats['new_users'] ?? 0 }} {{ __('cms.dashboard.new_this_month') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-slate-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center">
                                <x-ui-icon name="package" class="w-7 h-7 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div class="flex items-center gap-1 px-2 py-1 bg-green-50 dark:bg-green-500/10 rounded-full">
                                <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                <span class="text-xs font-semibold text-green-700 dark:text-green-400">+{{ $stats['new_products'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('cms.dashboard.products') }}</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_products'] ?? 0) }}</p>
                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <x-ui-icon name="trending-up" class="w-3 h-3 text-green-500" />
                                <span>{{ $stats['new_products'] ?? 0 }} {{ __('cms.dashboard.new_this_month') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-slate-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-purple-100 dark:bg-purple-500/10 rounded-2xl flex items-center justify-center">
                                <x-ui-icon name="folder" class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('cms.dashboard.categories') }}</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_categories'] ?? 0) }}</p>
                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <x-ui-icon name="layers" class="w-3 h-3 text-purple-500" />
                                <span>{{ __('cms.dashboard.organization_complete') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status Card -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-slate-200/50 dark:border-gray-700/50 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-amber-100 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center">
                                <x-ui-icon name="zap" class="w-7 h-7 text-amber-600 dark:text-amber-400" />
                            </div>
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('cms.dashboard.system') }}</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ __('cms.dashboard.online') }}</p>
                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <x-ui-icon name="check-circle" class="w-3 h-3 text-green-500" />
                                <span>{{ $stats['uptime'] ?? '99.9%' }} {{ __('cms.dashboard.uptime') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Live Activity Feed -->
                <div class="lg:col-span-2 bg-gradient-to-br from-slate-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-3xl shadow-sm border border-slate-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="relative">
                        <!-- Animated Background Pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div class="absolute top-0 left-0 w-32 h-32 bg-primary-500 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-0 right-0 w-24 h-24 bg-purple-500 rounded-full blur-2xl"></div>
                        </div>

                        <!-- Header -->
                        <div class="relative p-8 border-b border-slate-200/30 dark:border-gray-700/30">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                                            <x-ui-icon name="pulse" class="w-6 h-6 text-white animate-pulse" />
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800 animate-pulse"></div>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                            {{ __('cms.dashboard.live_feed') }}
                                        </h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('cms.dashboard.real_time') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider">{{ __('cms.dashboard.live') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Cards -->
                        <div class="relative p-6 space-y-4 max-h-96 overflow-y-auto">
                            @forelse ($recentActivity as $activity)
                                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-slate-200/30 dark:border-gray-700/50 hover:shadow-md hover:border-slate-300/50 dark:hover:border-gray-600/50 transition-all duration-300">
                                    <!-- Activity Indicator -->
                                    <div class="absolute top-6 left-6 w-1 h-16 bg-gradient-to-b from-blue-500 to-transparent rounded-full opacity-20"></div>

                                    <div class="flex items-start gap-4">
                                        <!-- User Avatar -->
                                        <div class="relative flex-shrink-0">
                                            <div class="w-12 h-12 bg-gradient-to-br from-{{ $activity['icon'] === 'log-in' ? 'green' : ($activity['icon'] === 'log-out' ? 'red' : 'blue') }}-400 to-{{ $activity['icon'] === 'log-in' ? 'emerald' : ($activity['icon'] === 'log-out' ? 'orange' : 'indigo') }}-600 rounded-full flex items-center justify-center shadow-lg">
                                                <span class="text-white font-bold text-lg">{{ substr($activity['user'], 0, 1) }}</span>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center">
                                                <x-ui-icon name="{{ $activity['icon'] }}" class="w-2 h-2 text-slate-600 dark:text-slate-400" />
                                            </div>
                                        </div>

                                        <!-- Activity Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="space-y-2">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $activity['user'] }}</span>
                                                        <div class="w-1 h-1 bg-slate-400 dark:bg-slate-600 rounded-full"></div>
                                                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider">{{ $activity['time'] }}</span>
                                                    </div>
                                                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                                        {{ $activity['description'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16">
                                    <div class="relative inline-block">
                                        <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center">
                                            <x-ui-icon name="monitor-off" class="w-12 h-12 text-slate-400 dark:text-slate-500" />
                                        </div>
                                        <div class="absolute inset-0 rounded-full border-2 border-slate-200 dark:border-gray-600 animate-ping opacity-20"></div>
                                    </div>
                                    <div class="mt-6 space-y-2">
                                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('cms.dashboard.system_waiting') }}</h4>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                                            {{ __('cms.dashboard.no_activity') }}
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Distribution -->
                <div class="space-y-8">
                    <!-- Quick Actions -->
                    <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 shadow-lg relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>

                        <div class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                    <x-ui-icon name="zap" class="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ __('cms.general.quick_actions') }}</h3>
                                    <p class="text-sm text-white/80">{{ __('cms.dashboard.quick_actions_subtitle') }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('catalog.products.create') }}" class="group flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-2xl backdrop-blur-sm transition-all">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <x-ui-icon name="plus" class="w-5 h-5 text-white" />
                                    </div>
                                    <span class="text-xs font-semibold text-white uppercase tracking-wider">{{ __('cms.general.product') }}</span>
                                </a>
                                <a href="{{ route('catalog.categories.create') }}" class="group flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-2xl backdrop-blur-sm transition-all">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <x-ui-icon name="folder" class="w-5 h-5 text-white" />
                                    </div>
                                    <span class="text-xs font-semibold text-white uppercase tracking-wider">{{ __('cms.general.category') }}</span>
                                </a>
                                <a href="{{ route('catalog.brands.create') }}" class="group flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-2xl backdrop-blur-sm transition-all">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <x-ui-icon name="tag" class="w-5 h-5 text-white" />
                                    </div>
                                    <span class="text-xs font-semibold text-white uppercase tracking-wider">{{ __('cms.general.brand') }}</span>
                                </a>
                                <a href="{{ route('settings.index') }}" class="group flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-2xl backdrop-blur-sm transition-all">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <x-ui-icon name="settings" class="w-5 h-5 text-white" />
                                    </div>
                                    <span class="text-xs font-semibold text-white uppercase tracking-wider">{{ __('cms.general.config') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Distribution -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-slate-200/50 dark:border-gray-700/50 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-500/10 rounded-2xl flex items-center justify-center">
                                <x-ui-icon name="pie-chart" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('cms.general.distribution') }}</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('cms.general.inventory_by_category') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($inventoryDistribution as $item)
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $item->name }}</span>
                                        <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $item->products_count }}</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-purple-500 to-blue-500 rounded-full transition-all duration-500"
                                             @class([
                                                 'w-0',
                                                 'w-[5%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 5,
                                                 'w-[10%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 10,
                                                 'w-[15%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 15,
                                                 'w-[20%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 20,
                                                 'w-[25%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 25,
                                                 'w-[30%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 30,
                                                 'w-[35%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 35,
                                                 'w-[40%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 40,
                                                 'w-[45%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 45,
                                                 'w-[50%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 50,
                                                 'w-[55%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 55,
                                                 'w-[60%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 60,
                                                 'w-[65%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 65,
                                                 'w-[70%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 70,
                                                 'w-[75%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 75,
                                                 'w-[80%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 80,
                                                 'w-[85%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 85,
                                                 'w-[90%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 90,
                                                 'w-[95%]' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 95,
                                                 'w-full' => $item->products_count / ($stats['total_products'] ?: 1) * 100 >= 100,
                                             ])></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
