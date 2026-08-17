{{ 
$attributes->merge([
    'for' => $for ?? null,
    'class' => trim("
        block text-sm font-medium text-gray-700
        " . ($required ? 'text-red-500' : '') . "
        " . ($attributes->get('class') ?? '')
    ")
]) 
}}>
{{ $slot }}
@if($required)
    <span class="text-red-500">*</span>
@endif
</label>
