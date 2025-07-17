@props([
    'components' => [],
])
<!-- Menu horizontal -->
<aside {{ $attributes->merge(['class' => 'layout-menu-horizontal']) }}>
    <x-moonshine::components
        :components="$components"
    />

    {{ $slot ?? '' }}
</aside>
<!-- END: Menu horizontal -->
