@props([
    'label' => '',
    'tag' => 'h1',
])
<div class="heading">
    <{{ $tag }} {{ $attributes }}>
        {{ $label !== '' ? $label : ($slot ?? '') }}
    </{{ $tag }}>
</div>
