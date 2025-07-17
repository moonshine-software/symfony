@props([
    'type' => 'default',
    'content' => '',
    'duration' => null,
    'showOnCreate' => true
])

@if($showOnCreate)
<div x-data
     x-init="$nextTick(() => { $dispatch('toast', {type: '{{ $type }}', text: '{{ $content }}', duration: {{ $duration ?? 'null' }}}) })"
></div>
@else
    <div x-data="{ show(){$dispatch('toast', {type: '{{ $type }}', text: '{{ $content }}', duration: {{ $duration ?? 'null' }}})} }">
        {{ $slot ?? '' }}
    </div>
@endif

