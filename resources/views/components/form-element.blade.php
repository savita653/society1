<div {{ $type == 'float' ? $attributes->merge(['class' => 'form-label-group']) : $attributes->merge(['class' => 'form-group']) }}>
    @if($type != 'float')
        {{ $label }}
    @endif
    {{ $slot }}
    @if($type == 'float')
        {{ $label }}
    @endif
</div>