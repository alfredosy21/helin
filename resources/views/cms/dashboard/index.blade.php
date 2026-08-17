<!-- Modern Dashboard Design -->
<div class="min-h-screen bg-soft">

    <!-- Main Content -->
    <div class="px-3 sm:px-6 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">

            <!-- Compact Stats Cards (4 only) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <x-ui-stat-card
                    icon="package"
                    :value="number_format($stats['total_products'] ?? 0)"
                    label="Productos"
                    :trend="($stats['new_products'] ?? 0) > 0 ? '+' . $stats['new_products'] . ' este mes' : null"
                    :trend-up="true"
                />

                <x-ui-stat-card
                    icon="file-text"
                    :value="number_format($stats['total_commercial_requests'] ?? 0)"
                    label="Solicitudes"
                    :trend="($stats['pending_commercial_requests'] ?? 0) > 0 ? $stats['pending_commercial_requests'] . ' pendientes' : null"
                    :trend-up="false"
                />

                <x-ui-stat-card
                    icon="mail"
                    :value="number_format($stats['total_contact_messages'] ?? 0)"
                    label="Mensajes"
                    :trend="($stats['unread_contact_messages'] ?? 0) > 0 ? $stats['unread_contact_messages'] . ' sin leer' : null"
                    :trend-up="false"
                />

                <x-ui-stat-card
                    icon="users"
                    :value="number_format($stats['total_users'] ?? 0)"
                    label="Usuarios"
                    :trend="($stats['new_users'] ?? 0) > 0 ? '+' . $stats['new_users'] . ' este mes' : null"
                    :trend-up="true"
                />
            </div>

            <!-- Recent Commercial Requests Table -->
            <div class="bg-white rounded-xl border border-line overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-line flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary-500/10 rounded-lg flex items-center justify-center">
                            <x-ui-icon name="file-text" class="w-4 h-4 text-primary-600" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-heading">{{ __('cms.dashboard.recent_requests') }}</h3>
                            <p class="text-[11px] text-body">{{ __('cms.dashboard.recent_requests_desc') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('commercial-requests.index') }}" class="text-[11px] font-semibold text-primary-600 hover:text-primary-700 uppercase tracking-wider">
                        {{ __('cms.dashboard.view_all') }}
                    </a>
                </div>

                <div class="overflow-x-auto">
                    @forelse ($recentRequests as $request)
                    <div class="flex items-center gap-3 px-4 sm:px-5 py-3 border-b border-line/60 hover:bg-slate-50/50 transition-colors">
                        <!-- Correlative + Status -->
                        <div class="flex-shrink-0 w-28">
                            <p class="text-[12px] font-bold text-heading truncate">{{ $request->correlative }}</p>
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider
                                @if($request->status === 'pending') text-amber-600
                                @elseif($request->status === 'processing') text-blue-600
                                @elseif($request->status === 'completed') text-green-600
                                @elseif($request->status === 'cancelled') text-red-500
                                @else text-body @endif">
                                {{ __('cms.dashboard.' . $request->status) }}
                            </span>
                        </div>

                        <!-- Customer -->
                        <div class="flex-1 min-w-0">
                            <p class="text-[12px] font-semibold text-heading truncate">{{ $request->full_name }}</p>
                            <p class="text-[11px] text-body truncate">
                                @if($request->customerType)<span class="inline-flex items-center gap-1">{{ $request->customerType->name }}</span> · @endif
                                {{ $request->email }}
                            </p>
                        </div>

                        <!-- Location -->
                        <div class="hidden md:block flex-shrink-0 w-32">
                            <p class="text-[11px] text-body truncate">{{ $request->state?->name ?? '—' }}</p>
                            <p class="text-[11px] text-body truncate">{{ $request->city?->name ?? '—' }}</p>
                        </div>

                        <!-- Delivery -->
                        <div class="hidden lg:block flex-shrink-0 w-28">
                            <p class="text-[11px] text-body truncate">{{ $request->deliveryMethod?->name ?? '—' }}</p>
                            <p class="text-[11px] text-body truncate">{{ $request->paymentMethod?->name ?? '—' }}</p>
                        </div>

                        <!-- Date -->
                        <div class="flex-shrink-0 w-24 text-right">
                            <p class="text-[11px] text-body">{{ $request->created_at->format('d/m/Y') }}</p>
                            <p class="text-[10px] text-body">{{ $request->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-[13px] text-body italic">{{ __('cms.dashboard.no_requests') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Contact Messages Table -->
            <div class="bg-white rounded-xl border border-line overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-line flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary-500/10 rounded-lg flex items-center justify-center">
                            <x-ui-icon name="mail" class="w-4 h-4 text-primary-600" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-heading">{{ __('cms.dashboard.recent_messages') }}</h3>
                            <p class="text-[11px] text-body">{{ __('cms.dashboard.recent_messages_desc') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('contact-messages.index') }}" class="text-[11px] font-semibold text-primary-600 hover:text-primary-700 uppercase tracking-wider">
                        {{ __('cms.dashboard.view_all') }}
                    </a>
                </div>

                <div class="overflow-x-auto">
                    @forelse ($recentMessages as $message)
                    <div class="flex items-center gap-3 px-4 sm:px-5 py-3 border-b border-line/60 hover:bg-slate-50/50 transition-colors">
                        <!-- Read/Unread indicator -->
                        <div class="flex-shrink-0">
                            <span class="inline-block w-2 h-2 rounded-full {{ $message->is_read ? 'bg-slate-300' : 'bg-primary-500' }}"></span>
                        </div>

                        <!-- Name + Email -->
                        <div class="flex-1 min-w-0">
                            <p class="text-[12px] font-semibold text-heading truncate">{{ $message->nombre }}</p>
                            <p class="text-[11px] text-body truncate">{{ $message->email }}</p>
                        </div>

                        <!-- Phone -->
                        <div class="hidden sm:block flex-shrink-0 w-28">
                            <p class="text-[11px] text-body truncate">{{ $message->telefono ?? '—' }}</p>
                        </div>

                        <!-- Subject -->
                        <div class="hidden md:block flex-1 min-w-0 max-w-[200px]">
                            <p class="text-[12px] font-medium text-heading truncate">{{ $message->asunto }}</p>
                            <p class="text-[11px] text-body truncate">{{ \Illuminate\Support\Str::limit($message->mensaje, 60) }}</p>
                        </div>

                        <!-- Status -->
                        <div class="flex-shrink-0 w-20 text-center">
                            <span class="text-[10px] font-semibold uppercase tracking-wider {{ $message->is_read ? 'text-body' : 'text-primary-600' }}">
                                {{ $message->is_read ? __('cms.dashboard.read') : __('cms.dashboard.unread') }}
                            </span>
                        </div>

                        <!-- Date -->
                        <div class="flex-shrink-0 w-24 text-right">
                            <p class="text-[11px] text-body">{{ $message->created_at->format('d/m/Y') }}</p>
                            <p class="text-[10px] text-body">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-[13px] text-body italic">{{ __('cms.dashboard.no_messages') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Content Grid: Activity Feed + Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

                <!-- Live Activity Feed -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-line overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-line">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="pulse" class="w-4 h-4 text-primary-600" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-heading">{{ __('cms.dashboard.live_feed') }}</h3>
                                    <p class="text-[11px] text-body">{{ __('cms.dashboard.real_time') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                                <span class="text-[10px] font-semibold text-primary-600 uppercase tracking-wider">{{ __('cms.dashboard.live') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Cards -->
                    <div class="p-4 space-y-2 max-h-80 overflow-y-auto">
                        @forelse ($recentActivity as $activity)
                        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                            <!-- User Avatar -->
                            <div class="relative flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-500/10 rounded-full flex items-center justify-center">
                                    <span class="text-primary-700 font-bold text-[11px]">{{ substr($activity['user'], 0, 1) }}</span>
                                </div>
                            </div>

                            <!-- Activity Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-[12px] text-heading">{{ $activity['user'] }}</span>
                                    <span class="text-[10px] text-body font-medium">{{ $activity['time'] }}</span>
                                </div>
                                <p class="text-[12px] text-body leading-relaxed mt-0.5">
                                    {{ $activity['description'] }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="py-6 text-center">
                            <p class="text-[13px] text-body italic">{{ __('cms.dashboard.no_activity') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="space-y-4">
                    <!-- Quick Actions - Catalog -->
                    <div class="bg-primary-500/5 rounded-xl p-4 border border-primary-500/10">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                <x-ui-icon name="zap" class="w-4 h-4 text-primary-600" />
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-heading">{{ __('cms.general.quick_actions') }}</h3>
                                <p class="text-[11px] text-body">{{ __('cms.dashboard.quick_actions_subtitle') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <a href="{{ route('catalog.products.create') }}" class="group flex flex-col items-center gap-1.5 p-2.5 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-7 h-7 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="package" class="w-3.5 h-3.5 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Producto</span>
                            </a>
                            <a href="{{ route('catalog.family.create') }}" class="group flex flex-col items-center gap-1.5 p-2.5 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-7 h-7 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="folder" class="w-3.5 h-3.5 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Familia</span>
                            </a>
                            <a href="{{ route('catalog.brands.create') }}" class="group flex flex-col items-center gap-1.5 p-2.5 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-7 h-7 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="bookmark" class="w-3.5 h-3.5 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Marca</span>
                            </a>
                            <a href="{{ route('catalog.lines.create') }}" class="group flex flex-col items-center gap-1.5 p-2.5 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-7 h-7 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="layers" class="w-3.5 h-3.5 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Línea</span>
                            </a>
                            <a href="{{ route('testimonials.index') }}" class="group flex flex-col items-center gap-1.5 p-2.5 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-7 h-7 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="message-circle" class="w-3.5 h-3.5 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Testimonio</span>
                            </a>
                            <a href="{{ route('resources.index') }}" class="group flex flex-col items-center gap-1.5 p-2.5 bg-white hover:bg-primary-500/10 border border-primary-500/10 rounded-lg transition-colors">
                                <div class="w-7 h-7 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="book-open" class="w-3.5 h-3.5 text-primary-600" />
                                </div>
                                <span class="text-[10px] font-semibold text-primary-700 uppercase tracking-wider text-center">Recurso</span>
                            </a>
                        </div>
                    </div>

                    <!-- Module Shortcuts -->
                    <div class="bg-white rounded-xl p-4 border border-line">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 bg-primary-500/10 rounded-lg flex items-center justify-center">
                                <x-ui-icon name="grid" class="w-4 h-4 text-primary-600" />
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-heading">Secciones del Sitio</h3>
                                <p class="text-[11px] text-body">Gestiona el contenido público</p>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <a href="{{ route('sections.index') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="layout" class="w-3.5 h-3.5 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[12px] text-body group-hover:text-heading transition-colors">Secciones</span>
                                </div>
                                <span class="text-[11px] text-body">{{ $stats['total_sections'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('menu.index') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="menu" class="w-3.5 h-3.5 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[12px] text-body group-hover:text-heading transition-colors">Menú Web</span>
                                </div>
                                <span class="text-[11px] text-body">{{ $stats['total_menus'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('settings.index') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500/10 transition-colors">
                                        <x-ui-icon name="settings" class="w-3.5 h-3.5 text-body group-hover:text-primary-600 transition-colors" />
                                    </div>
                                    <span class="text-[12px] text-body group-hover:text-heading transition-colors">Configuración</span>
                                </div>
                                <x-ui-icon name="chevron-right" class="w-3.5 h-3.5 text-body" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
