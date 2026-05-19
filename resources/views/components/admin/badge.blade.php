@props([
    'type' => 'success'
])

<span {{ $attributes->merge(['class' => 'badge badge-' . $type]) }}>
    <span class="badge-dot"></span>
    {{ $slot }}
</span>
