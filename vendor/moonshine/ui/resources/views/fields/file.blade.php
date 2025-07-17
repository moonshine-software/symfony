@props([
    'value' => '',
    'files' => [],
    'isRemovable' => false,
    'canDownload' => false,
    'removableAttributes' => null,
    'hiddenAttributes' => null,
    'dropzoneAttributes' => null,
])
<x-moonshine::form.file
    :attributes="$attributes"
    :files="$files"
    :removable="$isRemovable"
    :removableAttributes="$removableAttributes"
    :hiddenAttributes="$hiddenAttributes"
    :dropzoneAttributes="$dropzoneAttributes"
    :imageable="false"
    :download="$canDownload"
/>
