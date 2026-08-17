<!-- Modern Dashboard Design -->
<div class="min-h-screen bg-soft">

    <!-- Main Content -->
    <div class="px-3 sm:px-6 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
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
                    icon="users"
                    :value="number_format($stats['total_users'] ?? 0)"
                    label="Usuarios"
                    :trend="($stats['new_users'] ?? 0) > 0 ? '+' . $stats['new_users'] . ' este mes' : null"
                    :trend-up="true"
                />

                <x-ui-stat-card
                    icon="bookmark"
                    :value="number_format($stats['total_brands'] ?? 0)"
                    label="Marcas"
                />
            </div>

            <!-- Secondary Stats Row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                <x-ui-stat-card
                    icon="file-text"
                    :value="number_format($stats['total_commercial_requests'] ?? 0)"
                    label="Solicitudes Comerciales"
                    :trend="($stats['pending_commercial_requests'] ?? 0) > 0 ? $stats['pending_commercial_requests'] . ' pendientes' : null"
                    :trend-up="false"
                />

                <x-ui-stat-card
                    icon="mail"
                    :value="number_format($stats['total_contact_messages'] ?? 0)"
                    label="Mensajes de Contacto"
                    :trend="($stats['unread_contact_messages'] ?? 0) > 0 ? $stats['unread_contact_messages'] . ' sin leer' : null"
                    :trend-up="false"
                />

                <x-ui-stat-card
                    icon="book-open"
                    :value="number_format($stats['total_resources'] ?? 0)"
                    label="Recursos"
                />

                <x-ui-stat-card
                    icon="message-circle"
                    :value="number_format($stats['total_testimonials'] ?? 0)"
                    label="Testimonios"
                />
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">

                <!-- Live Activity Feed -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-line overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-line">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="pulse" class="w-5 h-5 text-primary-600" />
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-heading">{{ __('cms.dashboard.live_feed') }}</h3>
                                    <p class="text-[13px] text-body">{{ __('cms.dashboard.real_time') }}</p>
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
                                                <span class="text-[10px] text-body font-medium uppercase tracking-wider">{{ $activity['time'] }}</span>
                                            </div>
                                            <p class="text-[13px] text-body leading-relaxed">
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
                <div class="space-y-4 sm:space-y-6">
                    <!-- Quick Actions - Catalog -->
                    <div class="bg-primary-500/5 rounded-xl p-4 sm:p-6 border border-primary-500/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                <x-ui-icon name="zap" class="w-5 h-5 text-primary-600" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-heading">{{ __('cms.general.quick_actions') }}</h3>
                                <p class="text-[13px] text-body">{{ __('cms.dashboard.quick_actions_subtitle') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 sm:gap-3">
                            <a href="{{ route('catalog.products.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="package" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Producto</span>
                            </a>
                            <a href="{{ route('catalog.family.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="folder" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Familia</span>
                            </a>
                            <a href="{{ route('catalog.brands.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="bookmark" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Marca</span>
                            </a>
                            <a href="{{ route('catalog.lines.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="layers" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Línea</span>
                            </a>
                            <a href="{{ route('blog.articles.create') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="file-text" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Artículo</span>
                            </a>
                            <a href="{{ route('testimonials.index') }}" class="group flex flex-col items-center gap-2 p-3 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="message-circle" class="w-4 h-4 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Testimonio</span>
                            </a>
                        </div>
                    </div>

                    <!-- Module Shortcuts -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 border border-line">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                <x-ui-icon name="grid" class="w-5 h-5 text-primary-600" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-heading">Módulos</h3>
                                <p class="text-[13px] text-body">Acceso rápido</p>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <a href="{{ route('sections.index') }}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="layout" class="w-4 h-4 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[13px] text-body group-hover:text-heading transition-colors">Secciones</span>
                                </div>
                                <span class="text-[11px] text-body">{{ $stats['total_sections'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('menu.index') }}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="menu" class="w-4 h-4 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[13px] text-body group-hover:text-heading transition-colors">Menú Web</span>
                                </div>
                                <span class="text-[11px] text-body">{{ $stats['total_menus'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('resources.index') }}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="book-open" class="w-4 h-4 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[13px] text-body group-hover:text-heading transition-colors">Recursos</span>
                                </div>
                                <span class="text-[11px] text-body">{{ $stats['total_resources'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('commercial-requests.index') }}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="file-text" class="w-4 h-4 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[13px] text-body group-hover:text-heading transition-colors">Solicitudes</span>
                                </div>
                                <span class="text-[11px] text-body">{{ $stats['total_commercial_requests'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('contact-messages.index') }}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="mail" class="w-4 h-4 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[13px] text-body group-hover:text-heading transition-colors">Mensajes</span>
                                </div>
                                <span class="text-[11px] text-body">{{ $stats['total_contact_messages'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('settings.index') }}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="settings" class="w-4 h-4 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[13px] text-body group-hover:text-heading transition-colors">Configuración</span>
                                </div>
                                <x-ui-icon name="chevron-right" class="w-4 h-4 text-body" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
