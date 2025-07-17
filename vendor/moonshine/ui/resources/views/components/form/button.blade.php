@props([
    'icon' => '',
    'raw' => false,
])
<button
    {{ $attributes->class($raw ? [] : ['btn'])
        ->merge(['type' => 'button']) }}
>
    {{ $icon ?? '' }}
    {{ $slot ?? '' }}
</button>
