@props([
    'name' => '',
    'precognitive' => false,
    'hideSubmit' => false,
    'raw' => false,
    'fields' => [],
    'submit' => '',
    'buttons' => [],
    'errors' => [],
    'errorsAbove' => true,
])
@if($errorsAbove)
    <x-moonshine::form.all-errors :errors="$errors" />
@endif

<x-moonshine::form
    :attributes="$attributes"
    :name="$name"
    :precognitive="$precognitive"
    :errors="$errors"
    :raw="$raw"
>
    <x-moonshine::fields-group
        :components="$fields"
    />

    <x-slot:buttons>
        {!! $submit !!}

        @if($buttons->isNotEmpty())
            <x-moonshine::action-group
                :actions="$buttons"
            />
        @endif
    </x-slot:buttons>
</x-moonshine::form>
