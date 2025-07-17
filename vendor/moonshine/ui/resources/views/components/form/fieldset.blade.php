@props([
    'label' => '',
])
<fieldset {{ $attributes }}>
    <legend>{!! $label !!}</legend>

    {{ $slot }}
</fieldset>
