@props([
    'fields' => [],
])
<div class="space-elements">
    <x-moonshine::fields-group
        :components="$fields"
    />
</div>
