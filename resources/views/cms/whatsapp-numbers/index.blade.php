<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::SETTINGS" :submodule-id="\App\Models\Submodule::WHATSAPP_NUMBERS" :subtitle="__('cms.whatsapp_numbers.breadcrumb')">
            @if(!$showForm)
            <x-slot:action>
                <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('cms.whatsapp_numbers.new_button') }}
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
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.whatsapp_numbers.search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-slate-300" />
                </div>
                <select wire:model.live="stateFilter" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="all">{{ __('cms.whatsapp_numbers.all_states') }}</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- WhatsApp Numbers Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-semibold">
                            <th class="px-6 py-3.5">{{ __('cms.whatsapp_numbers.phone_number') }}</th>
                            <th class="px-6 py-3.5">{{ __('cms.whatsapp_numbers.executive') }}</th>
                            <th class="px-6 py-3.5">{{ __('cms.whatsapp_numbers.state') }}</th>
                            <th class="px-6 py-3.5 text-center w-32">Estado</th>
                            <th class="px-6 py-3.5 text-right w-44">{{ __('cms.general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($whatsappNumbers as $whatsappNumber)
                        <tr wire:key="whatsapp-{{ $whatsappNumber->id }}" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fab fa-whatsapp text-[#25D366] text-lg"></i>
                                    <div>
                                        <div class="font-medium text-heading">{{ $whatsappNumber->formatted_number }}</div>
                                        @if($whatsappNumber->description)
                                            <div class="text-xs text-slate-400">{{ $whatsappNumber->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">{{ $whatsappNumber->executive_name ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($whatsappNumber->states as $state)
                                        <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-slate-100 text-slate-600">{{ $state->name }}</span>
                                    @empty
                                        <span class="text-sm text-slate-400">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggle({{ $whatsappNumber->id }})" class="cursor-pointer border-none bg-transparent">
                                    <x-ui-badge-status :active="$whatsappNumber->is_active" :active-label="__('cms.general.status_active')" :inactive-label="__('cms.general.status_inactive')" />
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-1">
                                    <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                        <button wire:click="edit({{ $whatsappNumber->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                        <button onclick="deleteWhatsAppNumber({{ $whatsappNumber->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                            <td colspan="5" class="px-6 py-16 text-center">
                                <x-ui-empty-state icon="folder" :title="__('cms.whatsapp_numbers.no_numbers')" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $whatsappNumbers->links() }}
        </div>
        @else
        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Cabecera limpia --}}
            <div class="p-6 border-b border-slate-50">
                <h2 class="text-lg font-bold text-heading">
                    {{ $editingId ? __('cms.whatsapp_numbers.edit_title') : __('cms.whatsapp_numbers.new_title') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">{{ __('cms.whatsapp_numbers.subtitle') }}</p>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6 space-y-6">

                    {{-- Toggle de estado --}}
                    <div class="flex items-center gap-3 bg-slate-50/50 border border-slate-100 p-4 rounded-lg">
                        <x-ui-toggle wire:model="is_active" :label="__('cms.general.status_active')" />
                    </div>

                    {{-- Número de teléfono --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.whatsapp_numbers.phone_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="phone_number" placeholder="{{ __('cms.whatsapp_numbers.phone_placeholder') }}"
                               class="w-full px-3 py-2.5 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300" />
                        @error('phone_number') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Ejecutivo --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.whatsapp_numbers.executive_label') }}</label>
                        <input type="text" wire:model="executive_name" placeholder="{{ __('cms.whatsapp_numbers.executive_placeholder') }}"
                               class="w-full px-3 py-2.5 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300" />
                        @error('executive_name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Estados (multi-select) --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.whatsapp_numbers.state_label') }} <span class="text-red-500">*</span></label>
                        <div class="max-h-48 overflow-y-auto bg-slate-50 border border-slate-100 rounded-lg p-3 space-y-2">
                            @foreach($states as $state)
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-100 rounded px-2 py-1 transition-colors">
                                    <input type="checkbox" value="{{ $state->id }}" wire:model="state_ids"
                                           class="rounded border-slate-300 text-primary focus:border-primary focus:ring-primary">
                                    <span class="text-sm text-slate-700">{{ $state->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-slate-400">Selecciona los estados que cubre este número.</p>
                        @error('state_ids') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.whatsapp_numbers.description_label') }}</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-3 py-2 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300 resize-none"
                                  placeholder="{{ __('cms.whatsapp_numbers.description_placeholder') }}"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                </div>

                {{-- Acciones alineadas a la derecha --}}
                <div class="p-6 border-t border-slate-50 bg-slate-50/30 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                        {{ __('cms.general.cancel') }}
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-6 py-2.5 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.whatsapp_numbers.new_button') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
    <script>
        function deleteWhatsAppNumber(id) {
        window.confirmDelete({
        title: '{{ __('cms.whatsapp_numbers.delete_title') }}',
                text: '{{ __('cms.general.delete_confirm_text') }}',
                confirmButtonText: '{{ __('cms.whatsapp_numbers.delete_button') }}',
                cancelButtonText: '{{ __('cms.general.cancel') }}',
                onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').confirmDelete(id);
                }
        });
        }
    </script>
</div>
