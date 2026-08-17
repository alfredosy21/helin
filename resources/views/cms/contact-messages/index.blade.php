{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-3 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::CONTACT" :submodule-id="\App\Models\Submodule::CONTACT_MESSAGES" :subtitle="__('cms.contact_messages.title')" />

        {{-- Main Unified Card: Filtros y Tabla --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-3 sm:p-4 bg-white border-b border-slate-50 flex flex-col sm:flex-row gap-2 sm:gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-body">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="Buscar por nombre, email o asunto..."
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
                </div>
                <select wire:model.live="readFilter" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="all">Todos</option>
                    <option value="unread">No leídos ({{ $unreadCount }})</option>
                    <option value="read">Leídos</option>
                </select>
                <select wire:model.live="perPage" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- Messages Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2.5">ID</th>
                            <th class="px-4 py-2.5">Nombre</th>
                            <th class="px-4 py-2.5">Contacto</th>
                            <th class="px-4 py-2.5">Asunto</th>
                            <th class="px-4 py-2.5 text-center">Estado</th>
                            <th class="px-4 py-2.5 text-center">Fecha</th>
                            <th class="px-4 py-2.5 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[13px]">
                        @forelse($messages as $message)
                            <tr class="hover:bg-slate-50/60 transition-colors {{ $message->is_read ? 'text-body' : 'text-body' }}">
                                <td class="px-4 py-2.5">#{{ $message->id }}</td>
                                <td class="px-4 py-2.5">{{ $message->nombre }}</td>
                                <td class="px-4 py-2.5">
                                    <div>{{ $message->email }}</div>
                                    @if ($message->telefono)
                                        <div class="text-[13px] text-body">{{ $message->telefono }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 max-w-[220px] truncate">{{ $message->asunto }}</td>
                                <td class="px-4 py-2.5 text-center">
                                    @if ($message->is_read)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs bg-primary-500/10 text-primary-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                            Leído
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs bg-amber-50 text-amber-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                            Nuevo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-center text-xs">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button wire:click="viewDetails({{ $message->id }})" title="Ver mensaje"
                                                class="p-2 rounded-lg hover:bg-slate-100 text-body hover:text-primary transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                        </button>
                                        <button wire:click="toggleRead({{ $message->id }})" title="Marcar leído/no leído"
                                                class="p-2 rounded-lg hover:bg-slate-100 text-body hover:text-primary transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.981l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 0h.008v.008h-.008v-.008Z"/></svg>
                                        </button>
                                        <button wire:click="delete({{ $message->id }})" wire:confirm="¿Eliminar este mensaje?" title="Eliminar"
                                                class="p-2 rounded-lg hover:bg-red-50 text-body hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center">
                                    <x-ui-empty-state icon="folder" title="No hay mensajes de contacto registrados." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($messages->hasPages())
                <div class="px-4 py-3 border-t border-slate-50">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>

        {{-- Details Modal --}}
        @if ($showDetails && $selectedMessage)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:key="details-{{ $selectedMessage->id }}">
                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeDetails"></div>
                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="flex items-start justify-between gap-4 p-6 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-semibold text-heading">{{ $selectedMessage->asunto }}</h3>
                            <p class="text-[13px] text-body mt-1">{{ $selectedMessage->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <button wire:click="closeDetails" class="p-2 rounded-lg hover:bg-slate-100 text-body transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4 text-sm">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[13px] text-body uppercase tracking-wide">Nombre</p>
                                <p class="text-heading mt-0.5">{{ $selectedMessage->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-[13px] text-body uppercase tracking-wide">Teléfono</p>
                                <p class="text-heading mt-0.5">{{ $selectedMessage->telefono ?? '—' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[13px] text-body uppercase tracking-wide">Email</p>
                            <p class="text-heading mt-0.5">{{ $selectedMessage->email }}</p>
                        </div>
                        <div>
                            <p class="text-[13px] text-body uppercase tracking-wide">Mensaje</p>
                            <p class="text-heading mt-0.5 leading-relaxed whitespace-pre-line">{{ $selectedMessage->mensaje }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
