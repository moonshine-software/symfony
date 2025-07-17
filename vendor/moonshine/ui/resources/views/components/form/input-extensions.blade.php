@props([
    'extensions' => null,
])
@if($extensions && $extensions->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'form-group form-group-expansion']) }}>
        {{ $slot ?? '' }}

        <div class="expansion-wrapper">
            @foreach($extensions as $extension)
                {!! $extension !!}
            @endforeach
        </div>
    </div>
@else
    {{ $slot ?? '' }}
@endif
