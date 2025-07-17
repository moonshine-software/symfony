@props([
    'value'
])
<button
    class="expansion"
    type="button"
    @click.prevent="$refs.extensionInput.stepDown()"
    :disabled="$refs.extensionInput.disabled || $refs.extensionInput.readOnly"
>
    <span>
        <x-moonshine::icon
            icon="minus-small"
            size="4"
        />
    </span>
</button>

<button
    class="expansion"
    type="button"
    @click.prevent="$refs.extensionInput.stepUp()"
    :disabled="$refs.extensionInput.disabled || $refs.extensionInput.readOnly"
>
    <span>
        <x-moonshine::icon
            icon="plus-small"
            size="4"
        />
    </span>
</button>
