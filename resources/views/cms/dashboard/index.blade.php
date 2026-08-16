<!-- Modern Dashboard Design -->
<div class="min-h-screen bg-soft">

    <!-- Minimalist Header -->
    <div class="bg-white border-b border-line">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- User Avatar -->
                    <div class="relative">
                        @if(auth()->user()->image)
                        <div class="w-12 h-12 rounded-xl overflow-hidden ring-2 ring-primary-500/20">
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center ring-2 ring-primary-500/20">
                            <span class="text-white font-bold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        @endif
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>

                    <!-- Welcome Text -->
                    <div>
                        <h1 class="text-xl font-bold text-heading">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="text-xs text-slate-400">
                            {{ now()->format('d M Y') }} • {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Refresh Button -->
                <button wire:click="refreshStats" class="p-2 bg-primary-500/5 rounded-lg hover:bg-primary-500/10 transition-colors border-none cursor-pointer">
                    <x-ui-icon name="refresh-cw" class="w-5 h-5 text-primary-600" />
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-6 py-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-ui-stat-card
                    icon="users"
                    :value="number_format($stats['total_users'] ?? 0)"
                    label="Usuarios Totales"
                    :trend="($stats['new_users'] ?? 0) > 0 ? '+' . $stats['new_users'] . ' este mes' : null"
                    :trend-up="true"
                />

                <x-ui-stat-card
                    icon="package"
                    :value="number_format($stats['total_products'] ?? 0)"
                    label="Productos"
                    :trend="($stats['new_products'] ?? 0) > 0 ? '+' . $stats['new_products'] . ' este mes' : null"
                    :trend-up="true"
                />

                <x-ui-stat-card
                    icon="folder"
                    :value="number_format($stats['total_categories'] ?? 0)"
                    label="Categorías"
                />

                <x-ui-stat-card
                    icon="check-circle"
                    :value="$stats['uptime'] ?? '99.9%'"
                    label="Sistema Operativo"
                />
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Live Activity Feed -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-line overflow-hidden">
                    <div class="p-6 border-b border-line">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="pulse" class="w-5 h-5 text-primary-600" />
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-heading">{{ __('cms.dashboard.live_feed') }}</h3>
                                    <p class="text-xs text-slate-400">{{ __('cms.dashboard.real_time') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                                <span class="text-[10px] font-semibold text-primary-600 uppercase tracking-wider">{{ __('cms.dashboard.live') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Cards -->
                    <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                        @forelse ($recentActivity as $activity)
                        <div class="bg-white rounded-xl p-4 border border-line">
                            <div class="flex items-start gap-3">
                                <!-- User Avatar -->
                                <div class="relative flex-shrink-0">
                                    <div class="w-10 h-10 bg-primary-500/10 rounded-full flex items-center justify-center">
                                        <span class="text-primary-700 font-bold text-sm">{{ substr($activity['user'], 0, 1) }}</span>
                                    </div>
                                </div>

                                <!-- Activity Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-sm text-heading">{{ $activity['user'] }}</span>
                                                <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">{{ $activity['time'] }}</span>
                                            </div>
                                            <p class="text-xs text-body leading-relaxed">
                                                {{ $activity['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <x-ui-empty-state
                            icon="monitor-off"
                            :title="__('cms.dashboard.system_waiting')"
                            :description="__('cms.dashboard.no_activity')"
                        />
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="space-y-8">
                    <!-- Quick Actions -->
                    <div class="bg-primary-500/5 rounded-xl p-6 border border-primary-500/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                <x-ui-icon name="zap" class="w-5 h-5 text-primary-600" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-heading">{{ __('cms.general.quick_actions') }}</h3>
                                <p class="text-xs text-slate-400">{{ __('cms.dashboard.quick_actions_subtitle') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('catalog.products.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="package" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider">{{ __('cms.general.product') }}</span>
                            </a>
                            <a href="{{ route('catalog.family.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="folder" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider">{{ __('cms.general.family') }}</span>
                            </a>
                            <a href="{{ route('catalog.brands.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="bookmark" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider">{{ __('cms.general.brand') }}</span>
                            </a>
                            <a href="{{ route('settings.index') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="settings" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider">{{ __('cms.general.config') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
