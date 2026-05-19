@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button'
])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn-admin btn-admin-' . $variant . ($size === 'sm' ? ' btn-admin-sm' : '')]) }}>
    {{ $slot }}
</button>
