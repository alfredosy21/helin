{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-3 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">

            {{-- SECCIÓN DE LA TABLA (Se muestra solo si showForm es falso) --}}

            {{-- Header Section & Breadcrumb Refinado --}}
            <x-ui-section-header :module-id="\App\Models\Module::ADMINISTRATORS" :submodule-id="\App\Models\Submodule::USERS" :subtitle="__('cms.users.title')">
                @if(!$showForm)
                <x-slot:action>
                    {{-- Botón Principal Nativo con el Color Institucional Forzado --}}
                    <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[13px] font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                        </svg>
                        {{ __('cms.users.new_button') }}
                    </button>
                </x-slot:action>
                @endif
            </x-ui-section-header>
        @if(!$showForm)
            {{-- Main Unified Card: Filtros y Tabla en una sola estructura --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

                {{-- Search & Filter Section --}}
                <div class="p-3 sm:p-4 bg-white border-b border-slate-50 flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <div class="relative flex-1">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-body">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                        </span>
                        <input type="text" wire:model.live="search" placeholder="{{ __('cms.users.search_placeholder') }}"
                            class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
                    </div>
                    <select wire:model.live="perPage" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                        <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                        <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                        <option value="50">{{ __('cms.tables.per_page_50') }}</option>
                    </select>
                </div>

                {{-- Users Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                                <th class="px-4 py-2.5">{{ __('cms.users.name_label') }}</th>
                                <th class="px-4 py-2.5">{{ __('cms.users.email_label') }}</th>
                                <th class="px-4 py-2.5 w-40">{{ __('cms.tables.role_security') }}</th>
                                <th class="px-4 py-2.5 w-32">{{ __('cms.tables.status') }}</th>
                                <th class="px-4 py-2.5 text-right w-32">{{ __('cms.tables.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-[13px]">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                                    <td class="px-4 py-2.5">
                                        <span class="text-body block truncate">{{ $user->name }}</span>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <span class="text-[13px] text-body block truncate">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <span class="px-2.5 py-0.5 bg-slate-50 border border-slate-100 rounded text-xs text-body font-medium">
                                            {{ $user->role->name ?? __('cms.users.no_role') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <x-ui-badge-status :active="$user->is_active" :active-label="__('cms.general.status_active')" :inactive-label="__('cms.general.status_inactive')" />
                                    </td>
                                    <td class="px-4 py-2.5 text-right">
                                        <div class="flex justify-end gap-1">
                                            {{-- Botón de Lápiz (Editar) --}}
                                            <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                                <button wire:click="edit({{ $user->id }})" class="p-2 text-body hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                                    </svg>
                                                </button>
                                            </x-cms-tooltip>
                                            {{-- Botón de Basura (Eliminar) --}}
                                            <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                                <button onclick="deleteUser({{ $user->id }})" class="p-2 text-body hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                                    <td colspan="5" class="px-4 py-8 text-center">
                                        <x-ui-empty-state icon="folder" :title="__('cms.general.no_records')" />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                {{ $users->links() }}
            </div>
        @else
            {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
            <div class="space-y-4 sm:space-y-5">

                {{-- Información del usuario --}}
                <x-ui-form-card title="{{ $editingId ? __('cms.users.edit_title') : __('cms.users.new_title') }}" description="{{ __('cms.users.subtitle') }}" icon="user">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-body uppercase tracking-wider block">{{ __('cms.users.name_label') }} <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="name" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                                @error('name') <span class="text-xs text-red-500 font-medium italic block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-semibold text-body uppercase tracking-wider block">{{ __('cms.users.email_label') }} <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="email" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                                @error('email') <span class="text-xs text-red-500 font-medium italic block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider block">{{ __('cms.users.role_label') }} <span class="text-red-500">*</span></label>
                            <select wire:model="rol_id" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                <option value="">{{ __('cms.users.role_placeholder') }}</option>
                                @foreach($roles as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('rol_id') <span class="text-xs text-red-500 font-medium italic block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </x-ui-form-card>

                {{-- Seguridad --}}
                <x-ui-form-card title="{{ __('cms.users.credentials') }}" description="{{ __('cms.users.password_placeholder') }}" icon="lock">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider block">{{ __('cms.users.credentials') }} <span class="text-red-500">*</span></label>
                            <button type="button" wire:click="generatePassword" class="text-xs font-medium text-primary hover:underline bg-transparent border-none p-0 cursor-pointer">
                                {{ __('cms.users.generate_password') }}
                            </button>
                        </div>

                        <div class="space-y-1.5">
                            <input type="text" placeholder="{{ __('cms.users.password_placeholder') }}" wire:model="password" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />

                            @if($suggestedPassword && !$password)
                                <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg mt-2 animate-in fade-in duration-150">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-medium text-primary">{{ __('cms.users.password_suggestion') }}</p>
                                            <code class="text-xs font-mono text-heading mt-1 block">{{ $suggestedPassword }}</code>
                                        </div>
                                        <button type="button" onclick="copySuggestedPassword('{{ $suggestedPassword }}')" class="ml-3 p-2 text-body hover:text-primary hover:bg-slate-100 rounded-lg transition-colors border-none bg-transparent cursor-pointer" title="Copiar contraseña">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @error('password') <span class="text-xs text-red-500 font-medium italic block mt-1">{{ $message }}</span> @enderror
                        <p class="text-[11px] text-body italic">
                            {{ $editingId ? __('cms.users.password_hint_edit') : __('cms.users.password_hint_new') }}
                        </p>
                    </div>
                </x-ui-form-card>

                {{-- Estado --}}
                <x-ui-form-card title="{{ __('cms.general.status_active') }}" description="{{ __('cms.users.subtitle') }}" icon="toggle-left">
                    <div class="space-y-4">
                        <x-ui-toggle wire:model="is_active" :label="__('cms.general.status_active')" />
                    </div>
                </x-ui-form-card>

                {{-- Acciones --}}
                <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                    <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg text-[13px] font-medium border border-slate-200 text-body bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                        {{ __('cms.general.cancel') }}
                    </button>
                    <button type="button" wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-5 py-2 rounded-lg text-[13px] font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="save">{{ __('cms.general.save') }}</span>
                        <span wire:loading wire:target="save">{{ __('cms.general.save') }}</span>
                    </button>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    function deleteUser(userId) {
        window.confirmDelete({
            title: '{{ __('cms.users.delete_title') }}',
            text: '{{ __('cms.users.delete_warning') }}',
            confirmButtonText: '{{ __('cms.general.yes_delete') }}',
            cancelButtonText: '{{ __('cms.general.cancel') }}',
            onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').confirmDelete(userId);
            }
        });
    }

    function copySuggestedPassword(password) {
        // Copiar al portapapeles
        navigator.clipboard.writeText(password).then(function() {
            // Llenar el input de contraseña
            const passwordInput = document.querySelector('input[wire\\:model="password"]');
            if (passwordInput) {
                passwordInput.value = password;
                // Disparar evento de Livewire para actualizar el modelo
                passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            // Mostrar feedback visual
            const button = event.currentTarget;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
            button.setAttribute('title', '¡Contraseña copiada y aplicada!');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.setAttribute('title', 'Copiar contraseña');
            }, 2000);
        }).catch(function(err) {
            console.error('Error al copiar contraseña:', err);
        });
    }
</script>
