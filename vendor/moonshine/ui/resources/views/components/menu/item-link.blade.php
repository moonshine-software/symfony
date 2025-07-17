@props([
    'label' => '',
    'previewLabel' => '',
    'url' => '#',
    'icon' => '',
    'badge' => false,
    'top' => false,
    'hasComponent' => false,
    'component' => null,
])
<a
    href="{{ $url }}"
    {{ $attributes?->merge(['class' => 'menu-inner-link']) }}
>
    @if($icon)
        {!! $icon!!}
    @elseif(!$top)
        <span class="menu-inner-item-char">
            {{ $previewLabel }}
        </span>
    @endif

    <span class="menu-inner-text">{{ $label }}</span>

    @if($badge !== false)
        <span class="menu-inner-counter">{{ $badge }}</span>
    @endif
</a>

@if($hasComponent)
    <template x-teleport="body">
        {!! $component !!}
    </template>
@endif
