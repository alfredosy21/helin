{{--
@props([
    'model',                  // wire:model property name for the upload (e.g. 'image')
    'currentModel' => null,   // property name holding an existing stored path (e.g. 'current_image')
    'preview' => null,        // pass the Livewire temp upload property, e.g. :preview="$image"
    'currentImage' => null,   // pass the existing stored path, e.g. :current-image="$current_image"
    'label' => null,
    'hint' => 'JPG, PNG (Máx. 2MB)',
    'accept' => 'image/*',
    'multiple' => false,
    'height' => 'h-24',
])
--}}
<div class="space-y-1.5">
    @if($label)
        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $label }}</label>
    @endif
    <div class="relative">
        @if($preview && !$multiple)
            <div class="mb-3 relative">
                <img src="{{ $preview->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg border border-line">
                <button type="button" wire:click="$set('{{ $model }}', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                    <x-ui-icon name="x" class="w-4 h-4" />
                </button>
            </div>
        @elseif($currentImage)
            <div class="mb-3 relative">
                <img src="{{ asset('storage/' . $currentImage) }}" class="w-full h-32 object-cover rounded-lg border border-line">
                @if($currentModel)
                    <button type="button" wire:click="$set('{{ $currentModel }}', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                        <x-ui-icon name="x" class="w-4 h-4" />
                    </button>
                @endif
            </div>
        @endif

        <label class="flex flex-col items-center justify-center w-full {{ $height }} border-2 border-dashed border-line rounded-lg cursor-pointer hover:border-primary-500 hover:bg-primary-500/5 transition-colors bg-soft/50">
            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                <x-ui-icon name="upload" class="w-6 h-6 text-slate-400 mb-1" />
                <p class="text-xs text-slate-500">{{ $slot->isNotEmpty() ? $slot : __('cms.general.select_image') }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $hint }}</p>
            </div>
            <input type="file" wire:model="{{ $model }}" class="hidden" accept="{{ $accept }}" {{ $multiple ? 'multiple' : '' }} />
        </label>
        @error($model)
            <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span>
        @enderror
    </div>
</div>
