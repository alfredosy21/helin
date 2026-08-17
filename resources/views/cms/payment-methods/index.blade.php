{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- SECCIÓN DE LA TABLA (Se muestra solo si showForm es falso) --}}

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::SETTINGS" :submodule-id="\App\Models\Submodule::PAYMENT_METHODS" subtitle="Métodos de Pago">
            @if(!$showForm)
            <x-slot:action>
                <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[13px] font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Método de Pago
                </button>
            </x-slot:action>
            @endif
        </x-ui-section-header>
        @if(!$showForm)
        {{-- Main Unified Card --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-body">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="Buscar métodos de pago..."
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
                </div>
                <select wire:model.live="perPage" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- Payment Methods Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2.5">Método de Pago</th>
                            <th class="px-4 py-2.5 text-center w-40">Actualizado</th>
                            <th class="px-4 py-2.5 text-center w-24">Estado</th>
                            <th class="px-4 py-2.5 text-right w-40">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="payment-methods-table-body" class="divide-y divide-slate-100 text-[13px]">
                        @forelse($paymentMethods as $method)
                        <tr wire:key="method-{{ $method->id }}" data-id="{{ $method->id }}" class="sortable-row hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-4 py-2.5">
                                <div class="flex items-start gap-3">
                                    <div class="drag-handle cursor-move text-body hover:text-body mt-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-body">{{ $method->name }}</div>
                                        <div class="text-[13px] text-body line-clamp-2">{{ $method->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-[13px] text-body">
                                    {{ $method->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <x-ui-badge-status :active="$method->is_active" active-label="Activo" inactive-label="Inactivo" />
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <div class="flex justify-end gap-1">
                                    <x-cms-tooltip text="Editar">
                                        <button wire:click="edit({{ $method->id }})" class="p-2 text-body hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="Eliminar">
                                        <button onclick="deletePaymentMethod({{ $method->id }})" class="p-2 text-body hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center">
                                <x-ui-empty-state icon="folder" title="No se encontraron métodos de pago" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $paymentMethods->links() }}
        </div>
        @else
        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Cabecera limpia --}}
            <div class="p-6 border-b border-slate-50">
                <h2 class="text-lg font-bold text-heading">
                    {{ $editingId ? 'Editar Método de Pago' : 'Nuevo Método de Pago' }}
                </h2>
                <p class="text-[13px] text-body mt-1">Configura los métodos de pago disponibles para tus clientes</p>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6 space-y-6">

                    {{-- Toggle de estado --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 bg-slate-50/50 border border-slate-100 p-4 rounded-lg">
                        <x-ui-toggle wire:model="is_active" :label="__('cms.general.status_active')" />
                        <div class="sm:ml-6">
                            <x-ui-toggle wire:model="requires_receipt" label="Requiere comprobante" />
                        </div>
                    </div>

                    {{-- Información básica --}}
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Título <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="Ej: Transferencia Bancaria"
                               class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                        @error('name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>


                    {{-- Descripción --}}
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Descripción</label>
                        <textarea wire:model="description" rows="8"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"
                                  placeholder="Describe detalladamente cómo funciona este método de pago, sus características, requisitos, tiempos de procesamiento, comisiones aplicables y cualquier información relevante que los clientes necesiten saber..."></textarea>
                        @error('description') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                </div>

                {{-- Acciones alineadas a la derecha --}}
                <div class="p-6 border-t border-slate-50 bg-slate-50/30 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-slate-200 text-body bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-4 py-1.5 rounded-lg text-[13px] font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? 'Guardar' : 'Crear' }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $editingId ? 'Guardar' : 'Crear' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
    <script>
        function deletePaymentMethod(methodId) {
        window.confirmDelete({
        title: 'Eliminar Método de Pago',
                text: '¿Estás seguro de eliminar este método de pago? Esta acción no se puede deshacer.',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').confirmDelete(methodId);
                }
        });
        }

        // Drag & Drop con SortableJS
        (function() {
        let sortableInstance = null;
        function initSortable() {
        const tbody = document.getElementById('payment-methods-table-body');
        if (!tbody) return;
        if (typeof Sortable === 'undefined') return;
        if (sortableInstance) sortableInstance.destroy();
        sortableInstance = new Sortable(tbody, {
        handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-emerald-50',
                onEnd: function() {
                const rows = tbody.querySelectorAll('tr[data-id]');
                const orderedIds = Array.from(rows).map(row => parseInt(row.dataset.id));
                const component = window.Livewire ? Livewire.find('{{ $this->getId() }}') : null;
                if (component && orderedIds.length > 0) {
                component.updateOrder(orderedIds);
                }
                }
        });
        }

        // Initialize after DOM is ready
        if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSortable);
        } else {
        initSortable();
        }

        // Reinitialize after Livewire updates
        document.addEventListener('livewire:updated', initSortable);
        })();
    </script>
</div>
