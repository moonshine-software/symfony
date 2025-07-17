@props([
    'path' => '',
    'icon' => '',
    'size' => 5,
    'color' => '',
])
<div {{ $attributes->class([
    'w-' . ($size ?? 5),
    'h-' . ($size ?? 5),
    'text-current' => empty($color),
    "text-$color" => !empty($color),
]) }}>
    @if($slot?->isNotEmpty())
        {!! $slot !!}
    @else
        @includeWhen($icon, "$path.$icon")
    @endif
</div>
