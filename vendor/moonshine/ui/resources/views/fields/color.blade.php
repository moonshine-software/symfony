@props([
    'value' => '',
])
<div x-data="{ color: '{!! $value ?? "#000000" !!}' }">
    <div class="form-color">
        <div class="form-color-thumb">
            <x-moonshine::form.input
                :attributes="$attributes->except('type')->merge([
                    'type' => 'color',
                ])"
                x-model:value="color"
            />
        </div>

        <x-moonshine::form.input
            :attributes="$attributes->except(['type'])->merge([
                'type' => 'text',
                'placeholder' => '#000000',
            ])"
            x-model:value="color"
        />
    </div>
</div>
