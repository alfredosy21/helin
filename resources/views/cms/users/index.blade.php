{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-[#f8fafc] relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb Refinado --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
             <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1 font-medium tracking-wide uppercase">
                <span>Escritorio</span>
                <span class="text-slate-300">/</span>
                <span class="text-primary-600 font-semibold">Administradores</span>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                Gestión de <span class="text-primary-600">Administradores</span>
            </h1>
        </div>

            {{-- Botón Principal Nativo con el Color Institucional Forzado --}}
            <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                </svg>
                Nuevo administrador
            </button>
        </div>

        {{-- Main Unified Card: Filtros y Tabla en una sola estructura --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="Buscar por nombre, correo o rol..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-[#222] placeholder-[#c0c1c6]" />
                </div>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">10 por pág.</option>
                    <option value="20">20 por pág.</option>
                    <option value="50">50 por pág.</option>
                </select>
            </div>

            {{-- Users Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[#c0c1c6] text-xs font-semibold">
                            <th class="px-6 py-3.5 text-center w-20">Admin</th>
                            <th class="px-6 py-3.5">Información</th>
                            <th class="px-6 py-3.5">Rol de seguridad</th>
                            <th class="px-6 py-3.5">Estado</th>
                            <th class="px-6 py-3.5 text-right w-32">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 flex justify-center">
                                    @if($user->image)
                                        <div class="w-9 h-9 rounded-lg overflow-hidden border border-slate-100">
                                            <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 font-semibold text-xs border border-slate-200">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-[#222]">{{ $user->name }}</span>
                                        <span class="text-xs text-[#c0c1c6]">{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 bg-slate-50 border border-slate-100 rounded text-xs text-slate-600 font-medium">
                                        {{ $user->role->name ?? 'Sin rol' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-primary' : 'bg-slate-300' }}"></span>
                                        <span class="text-xs font-medium {{ $user->is_active ? 'text-slate-700' : 'text-slate-400' }}">
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </td>
                                {{-- Botonera Nativa Forzada con Iconos de Lápiz y Basura Puros --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        {{-- Botón de Lápiz (Editar) --}}
                                        <button wire:click="edit({{ $user->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer" title="Editar usuario">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                        {{-- Botón de Basura (Eliminar) --}}
                                        <button onclick="confirmDelete(this, {{ $user->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer" title="Eliminar usuario">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-[#c0c1c6]">
                                        <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.25a2.25 2.25 0 0 1-2.25 2.25H2.25A2.25 2.25 0 0 1 0 20.25v-4.25A2.25 2.25 0 0 1 2.25 13.5A2.25 2.25 0 0 0 2.25 11.25V7.104a2.25 2.25 0 0 1 .515-1.425l3.525-4.406A2.25 2.25 0 0 1 8.012 1.5h7.976a2.25 2.25 0 0 1 1.722.813l3.525 4.406a2.25 2.25 0 0 1 .515 1.425v4.146ZM12 3v3.75m0-3.75a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Z"/></svg>
                                        <p class="text-xs font-medium">No se encontraron registros</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($users->hasPages())
                <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Formulario Lateral Nativo Limpio --}}
    @if($showForm)
    <div class="fixed inset-0 z-[100] flex items-center justify-end">
        <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-xs" wire:click="cancel"></div>

        <div class="relative w-full max-w-md h-full bg-white shadow-xl flex flex-col border-l border-slate-100">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-base font-bold text-[#222]">
                        {{ $editingId ? 'editar' : 'nuevo' }} usuario
                    </h2>
                    <p class="text-xs text-[#c0c1c6]">Configuración de acceso administrativo</p>
                </div>
                <button wire:click="cancel" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors border-none bg-transparent cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Nombre completo</label>
                    <input type="text" wire:model="name" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                    @error('name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">email administrativo</label>
                    <input type="email" wire:model="email" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />
                    @error('email') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">asignar rol</label>
                    <select wire:model="rol_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                        <option value="">Seleccione un rol...</option>
                        @foreach($roles as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('rol_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-slate-50 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">credenciales</label>
                        <button type="button" wire:click="generatePassword" class="text-xs font-medium text-primary hover:underline bg-transparent border-none p-0 cursor-pointer">
                            generar aleatoria
                        </button>
                    </div>

                    <div class="space-y-1.5">
                        <input type="text" placeholder="contraseña de acceso" wire:model="password" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors" />

                        @if($suggestedPassword && !$password)
                            <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg">
                                <p class="text-xs font-medium text-primary">sugerencia segura:</p>
                                <code class="text-xs font-mono text-[#222]">{{ $suggestedPassword }}</code>
                            </div>
                        @endif
                    </div>
                    @error('password') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    <p class="text-xs text-[#c0c1c6] italic">
                        {{ $editingId ? 'dejar en blanco para mantener la actual.' : 'Mínimo 8 caracteres.' }}
                    </p>
                </div>
            </div>

            <div class="p-6 border-t border-slate-50 bg-slate-50/50 flex gap-3">
                <button wire:click="cancel" class="flex-1 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors py-2 cursor-pointer">
                    Cancelar
                </button>
                <button wire:click="save" wire:loading.attr="disabled" class="flex-1 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors py-2 border-none cursor-pointer">
                    <span wire:loading.remove wire:target="save">Guardar cambios</span>
                    <span wire:loading wire:target="save">Procesando...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- JavaScript de SweetAlert2 --}}
    <script>
        function confirmDelete(button, userId) {
            Swal.fire({
                title: '¿eliminar este usuario?',
                text: "Esta acción no se puede deshacer y revocará todo acceso.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#09B6A2',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-xl border border-slate-100',
                    title: 'text-base font-bold text-[#222]',
                    htmlContainer: 'text-xs text-slate-500',
                    confirmButton: '!rounded-lg !px-4 !py-2 !text-xs !font-medium shadow-none',
                    cancelButton: '!rounded-lg !px-4 !py-2 !text-xs !font-medium !text-slate-600 shadow-none border border-slate-100'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.find('{{ $this->getId() }}').confirmDelete(userId);
                }
            });
        }
    </script>
</div>
