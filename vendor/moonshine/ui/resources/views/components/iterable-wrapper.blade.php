@props([
    'searchable' => false,
    'searchUrl' => '',
    'searchValue' => '',
    'searchPlaceholder' => '',
    'topLeft' => null,
    'topRight' => null,
])

<x-moonshine::layout.flex justify-align="start">
    @if($searchable)
        <x-moonshine::form
            raw
            action="{{ $searchUrl }}"
            @submit.prevent="asyncFormRequest"
        >
            <x-moonshine::form.input
                name="search"
                type="search"
                value="{{ $searchValue }}"
                placeholder="{{ $searchPlaceholder }}"
            />
        </x-moonshine::form>
    @endif

    {!! $topLeft ?? '' !!}
</x-moonshine::layout.flex>

<x-moonshine::layout.flex justify-align="end">
    {!! $topRight ?? '' !!}
</x-moonshine::layout.flex>

<x-moonshine::loader x-show="loading" />
<div x-show="!loading">
    {{ $slot }}
</div>
