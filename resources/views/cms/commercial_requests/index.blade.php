{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::CONTACT" :submodule-id="\App\Models\Submodule::COMMERCIAL_REQUESTS" subtitle="Gestión de Solicitudes Comerciales" />

        {{-- Main Unified Card: Filtros y Tabla --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="Buscar por nombre, email o teléfono..."
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-slate-300" />
                </div>
                <select wire:model.live="statusFilter" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="all">Todos los estados</option>
                    <option value="pending">Pendientes</option>
                    <option value="processing">En Proceso</option>
                    <option value="completed">Completadas</option>
                    <option value="cancelled">Canceladas</option>
                </select>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- Requests Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-semibold">
                            <th class="px-4 py-3.5">ID</th>
                            <th class="px-4 py-3.5">Cliente</th>
                            <th class="px-4 py-3.5">Contacto</th>
                            <th class="px-4 py-3.5">Ubicación</th>
                            <th class="px-4 py-3.5">Método de Entrega</th>
                            <th class="px-4 py-3.5">Método de Pago</th>
                            <th class="px-4 py-3.5 text-center">Estado</th>
                            <th class="px-4 py-3.5 text-center">Fecha</th>
                            <th class="px-4 py-3.5 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($requests as $request)
                        <tr wire:key="request-{{ $request->id }}" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-heading">{{ $request->correlative }}</span>
                                    @if($request->uuid)
                                    <span class="text-xs text-slate-400 font-mono">{{ substr($request->uuid, 0, 8) }}...</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-heading">{{ $request->full_name }}</span>
                                    <span class="text-xs text-slate-400">{{ $request->customerType->name ?? 'N/A' }}</span>
                                    @if($request->company_name)
                                    <span class="text-xs text-slate-500">{{ $request->company_name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col text-xs">
                                    <span class="text-slate-600">{{ $request->email }}</span>
                                    <span class="text-slate-500">{{ $request->phone }}</span>
                                    @if($request->whatsappNumber)
                                    <span class="text-turquesa">WhatsApp: {{ $request->whatsappNumber->phone_number }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col text-xs">
                                    <span class="text-slate-600">{{ $request->city->name ?? 'N/A' }}</span>
                                    <span class="text-slate-400">{{ $request->state->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-slate-600">{{ $request->deliveryMethod->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-slate-600">{{ $request->paymentMethod->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($request->status === 'pending') bg-yellow-50 text-yellow-600
                                    @elseif($request->status === 'processing') bg-blue-50 text-blue-600
                                    @elseif($request->status === 'completed') bg-green-50 text-green-600
                                    @elseif($request->status === 'cancelled') bg-red-50 text-red-600
                                    @endif">
                                    @if($request->status === 'pending') Pendiente
                                    @elseif($request->status === 'processing') En Proceso
                                    @elseif($request->status === 'completed') Completada
                                    @elseif($request->status === 'cancelled') Cancelada
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-slate-500">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-1">
                                    @if($request->uuid)
                                    <x-cms-tooltip text="Ver página de solicitud">
                                        <a href="{{ route('solicitud-enviada', ['uuid' => $request->uuid]) }}" target="_blank" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </x-cms-tooltip>
                                    @endif
                                    <x-cms-tooltip text="Ver detalles">
                                        <button type="button" wire:click="viewDetails({{ $request->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="Eliminar">
                                        <button onclick="deleteRequest({{ $request->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                            <td colspan="9" class="px-6 py-16 text-center">
                                <x-ui-empty-state icon="folder" title="No hay solicitudes registradas" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $requests->links() }}
        </div>
    </div>

    {{-- Modal de Detalles --}}
    @if($showDetails && $selectedRequest)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" wire:click="closeDetails">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-heading">Detalles de la Solicitud #{{ $selectedRequest->id }}</h2>
                <button wire:click="closeDetails" class="text-slate-400 hover:text-slate-600 border-none bg-transparent cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                {{-- Información del Cliente --}}
                <div class="bg-slate-50 rounded-lg p-4">
                    <h3 class="font-semibold text-heading mb-3">Información del Cliente</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-400">Nombre:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->full_name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400">Tipo:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->customerType->name ?? 'N/A' }}</span>
                        </div>
                        @if($selectedRequest->cedula)
                        <div>
                            <span class="text-slate-400">Cédula:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->cedula }}</span>
                        </div>
                        @endif
                        @if($selectedRequest->company_name)
                        <div>
                            <span class="text-slate-400">Empresa:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->company_name }}</span>
                        </div>
                        @endif
                        @if($selectedRequest->rif)
                        <div>
                            <span class="text-slate-400">RIF:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->rif }}</span>
                        </div>
                        @endif
                        <div>
                            <span class="text-slate-400">Teléfono:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->phone }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400">Email:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->email }}</span>
                        </div>
                        @if($selectedRequest->whatsappNumber)
                        <div>
                            <span class="text-slate-400">WhatsApp:</span>
                            <span class="text-turquesa ml-2">{{ $selectedRequest->whatsappNumber->phone_number }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <span class="text-slate-400 text-sm">Dirección:</span>
                        <p class="text-slate-700 text-sm mt-1">{{ $selectedRequest->address }}</p>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-400">Estado:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->state->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400">Ciudad:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->city->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Información de Envío --}}
                <div class="bg-slate-50 rounded-lg p-4">
                    <h3 class="font-semibold text-heading mb-3">Información de Envío</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-400">Método:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->deliveryMethod->name ?? 'N/A' }}</span>
                        </div>
                        @if($selectedRequest->other_delivery_company)
                        <div>
                            <span class="text-slate-400">Otra Empresa:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->other_delivery_company }}</span>
                        </div>
                        @endif
                    </div>
                    @if($selectedRequest->recipient_name)
                    <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-400">Destinatario:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->recipient_name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400">Documento:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->recipient_document }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400">Teléfono:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->recipient_phone }}</span>
                        </div>
                    </div>
                    @endif
                    @if($selectedRequest->shipping_state_id)
                    <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-400">Estado Envío:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->shippingState->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400">Ciudad Envío:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->shippingCity->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-slate-400">Agencia Destino:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->destination_agency }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Información de Pago --}}
                <div class="bg-slate-50 rounded-lg p-4">
                    <h3 class="font-semibold text-heading mb-3">Información de Pago</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-400">Método:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->paymentMethod->name ?? 'N/A' }}</span>
                        </div>
                        @if($selectedRequest->payment_receipt_number)
                        <div>
                            <span class="text-slate-400">Comprobante:</span>
                            <span class="text-slate-700 ml-2">{{ $selectedRequest->payment_receipt_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Observaciones --}}
                @if($selectedRequest->observations)
                <div class="bg-slate-50 rounded-lg p-4">
                    <h3 class="font-semibold text-heading mb-3">Observaciones</h3>
                    <p class="text-slate-700 text-sm">{{ $selectedRequest->observations }}</p>
                </div>
                @endif

                {{-- Carrito --}}
                <div class="bg-slate-50 rounded-lg p-4">
                    <h3 class="font-semibold text-heading mb-3">Productos Solicitados</h3>
                    <div class="text-sm">
                        @if($selectedRequest->cart_data)
                            @foreach($selectedRequest->cart_data as $item)
                            <div class="flex justify-between py-2 border-b border-slate-200 last:border-0">
                                <span class="text-slate-700">{{ $item['name'] ?? 'Producto' }} x{{ $item['quantity'] ?? 1 }}</span>
                                <span class="text-slate-600">{{ $item['price'] ?? 0 }}</span>
                            </div>
                            @endforeach
                        @else
                        <p class="text-slate-500">No hay información del carrito</p>
                        @endif
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <div class="flex gap-2">
                        <button wire:click="updateStatus({{ $selectedRequest->id }}, 'pending')" class="px-4 py-2 rounded-lg text-sm font-medium bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition-colors border-none cursor-pointer">
                            Pendiente
                        </button>
                        <button wire:click="updateStatus({{ $selectedRequest->id }}, 'processing')" class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors border-none cursor-pointer">
                            En Proceso
                        </button>
                        <button wire:click="updateStatus({{ $selectedRequest->id }}, 'completed')" class="px-4 py-2 rounded-lg text-sm font-medium bg-green-50 text-green-600 hover:bg-green-100 transition-colors border-none cursor-pointer">
                            Completada
                        </button>
                        <button wire:click="updateStatus({{ $selectedRequest->id }}, 'cancelled')" class="px-4 py-2 rounded-lg text-sm font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors border-none cursor-pointer">
                            Cancelada
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Scripts --}}
    <script>
        function deleteRequest(requestId) {
            window.confirmDelete({
                title: 'Eliminar Solicitud',
                text: '¿Estás seguro de que deseas eliminar esta solicitud? Esta acción no se puede deshacer.',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                onConfirm: function() {
                    Livewire.find('{{ $this->getId() }}').delete(requestId);
                }
            });
        }
    </script>
</div>
