{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-gradient-to-br from-slate-50 via-white to-blue-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-blue-900/20 relative">

    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-indigo-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 3s;"></div>
    </div>

    <!-- Header Section -->
    <div class="relative z-10 p-6 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <nav class="flex items-center text-sm text-slate-500">
                    <x-ui-icon name="users" class="w-4 h-4 mr-2" />
                    <span class="font-bold uppercase tracking-tighter">Administración</span>
                    <x-ui-icon name="chevron-right" class="w-3 h-3 mx-2" />
                    <span class="italic text-blue-600">Directorio de Usuarios</span>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    Gestión de <span class="text-blue-600">Acceso</span>
                </h1>
            </div>

            <x-ui-button wire:click="create" class="!rounded-2xl bg-slate-900 dark:bg-blue-600 text-white shadow-xl shadow-blue-500/20 group hover:scale-105 transition-transform">
                <x-ui-icon name="user-plus" class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" />
                Nuevo Administrador
            </x-ui-button>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl p-4 rounded-[2rem] border border-white dark:border-gray-700/50 shadow-sm flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <x-ui-icon name="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" />
                <input type="text" wire:model.live="search" placeholder="Buscar por nombre, correo o rol..."
                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-900 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all text-sm shadow-inner" />
            </div>
            <select wire:model.live="perPage" class="bg-white dark:bg-gray-900 border-none rounded-2xl px-6 py-3 text-sm focus:ring-2 focus:ring-blue-500 shadow-inner">
                <option value="10">10 por pág.</option>
                <option value="20">20 por pág.</option>
                <option value="50">50 por pág.</option>
            </select>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="relative z-10 px-6">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-2xl rounded-[2.5rem] border border-white dark:border-gray-700/50 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-gray-900/50 text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-8 py-6 text-center">Admin</th>
                            <th class="px-6 py-6">Información</th>
                            <th class="px-6 py-6">Rol de Seguridad</th>
                            <th class="px-6 py-6">Estado</th>
                            <th class="px-8 py-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700/50">
                        @forelse($users as $user)
                            <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                <td class="px-8 py-5 flex justify-center">
                                    @if($user->image)
                                        <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-lg shadow-blue-500/20">
                                            <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</span>
                                        <span class="text-xs text-slate-500 italic">{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-white dark:bg-gray-700 border border-slate-100 dark:border-gray-600 rounded-full text-[10px] font-black uppercase text-blue-600 tracking-tighter">
                                        {{ $user->role->name ?? 'Sin Rol' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                                        <span class="text-[10px] font-bold uppercase {{ $user->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="edit({{ $user->id }})" class="p-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                            <x-ui-icon name="edit-3" class="w-5 h-5" />
                                        </button>
                                        <button onclick="openDeleteModal(<?php echo $user->id; ?>)" class="p-2 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                            <x-ui-icon name="trash-2" class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-40">
                                        <x-ui-icon name="inbox" class="w-12 h-12 mb-4" />
                                        <p class="font-black uppercase tracking-widest text-sm">No se encontraron registros</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-6 bg-slate-50/50 dark:bg-gray-900/50 border-t border-slate-100 dark:border-gray-700/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Right Side Modal (Slide-over) -->
    @if($showForm)
    <div class="fixed inset-0 z-[100] flex items-center justify-end">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="cancel"></div>

        <div class="relative w-full max-w-lg h-full bg-white dark:bg-gray-900 shadow-2xl flex flex-col transform transition-transform duration-300">
            <div class="p-8 border-b border-slate-100 dark:border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">
                        {{ $editingId ? 'Editar' : 'Nuevo' }} <span class="text-blue-600">Usuario</span>
                    </h2>
                    <p class="text-xs text-slate-500 italic">Configuración de acceso administrativo</p>
                </div>
                <button wire:click="cancel" class="p-2 hover:bg-slate-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                    <x-ui-icon name="x" class="w-6 h-6" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                {{-- Form Sections --}}
                <div class="space-y-6">
                    <div class="space-y-2">
                        <x-ui-input label="Nombre Completo" wire:model="name" icon="user" />
                        @error('name') <span class="text-xs text-red-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui-input label="Email Administrativo" wire:model="email" icon="mail" />
                        @error('email') <span class="text-xs text-red-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-black text-slate-700 dark:text-slate-300 uppercase tracking-tighter">Asignar Rol</label>
                        <select wire:model="rol_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-800 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione un rol...</option>
                            @foreach($roles as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('rol_id') <span class="text-xs text-red-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-sm font-black text-slate-700 dark:text-slate-300 uppercase tracking-tighter">Credenciales</label>
                            <button type="button" wire:click="generatePassword" class="text-[10px] font-black text-blue-600 uppercase hover:underline">
                                Generar Aleatoria
                            </button>
                        </div>
                        <div class="relative">
                            <x-ui-input type="text" placeholder="Contraseña de acceso" wire:model="password" icon="lock" />
                            @if($suggestedPassword && !$password)
                                <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-500/10 rounded-xl border border-blue-100 dark:border-blue-500/20">
                                    <p class="text-[10px] text-blue-600 font-bold uppercase">Sugerencia segura:</p>
                                    <code class="text-xs font-mono dark:text-white">{{ $suggestedPassword }}</code>
                                </div>
                            @endif
                        </div>
                        @error('password') <span class="text-xs text-red-500 font-bold italic">{{ $message }}</span> @enderror
                        <p class="text-[10px] text-slate-400 mt-2 italic">
                            {{ $editingId ? 'Dejar en blanco para mantener la actual.' : 'Mínimo 8 caracteres.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-8 border-t border-slate-100 dark:border-gray-800 bg-slate-50/50 dark:bg-gray-800/30 flex gap-4">
                <x-ui-button wire:click="cancel" variant="secondary" class="flex-1 !rounded-2xl font-bold">
                    Cancelar
                </x-ui-button>
                <x-ui-button wire:click="save" wire:loading.attr="disabled" class="flex-1 !rounded-2xl bg-slate-900 dark:bg-blue-600 text-white font-bold shadow-lg">
                    <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                    <span wire:loading wire:target="save">Procesando...</span>
                </x-ui-button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Confirmación de Eliminación -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-500/20 mb-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">¿Eliminar este usuario?</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Esta acción no se puede deshacer. El usuario será eliminado permanentemente del sistema y perderá acceso a la plataforma.</p>

                    <div class="flex gap-3">
                        <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors bg-slate-100 dark:bg-gray-700 rounded-xl">
                            Cancelar
                        </button>
                        <button onclick="confirmDelete()" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">
                            Eliminar Usuario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteUserId = null;

        function openDeleteModal(userId) {
            deleteUserId = userId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteUserId = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function confirmDelete() {
            if (deleteUserId) {
                Livewire.find('<?= $this->getId() ?>').confirmDelete(deleteUserId);
                closeDeleteModal();
            }
        }
    </script>
</div>
