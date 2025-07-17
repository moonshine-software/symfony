@props([
    'enabled' => $isEnabled ?? true,
    'form' => '',
])
@if($enabled)
    <div
        x-data="{
            isPopover: false,
            observer: null,
            init() {
                this.observer = new ResizeObserver(entries => {
                    for (let entry of entries) {
                        const width = entry.contentRect.width;
                        this.isPopover = width < 100;
                    }
                });
                this.observer.observe(this.$el.parentElement);
            },
            destroy() {
                this.observer.disconnect();
            }
        }"
        x-init="init"
        x-on:destroy.window="destroy"
        class="search-wrapper"
    >
        <x-moonshine::popover x-show="isPopover" title="" placement="auto">
            <x-slot:trigger>
                <button class="flex justify-center w-full search-form-show">
                    <x-moonshine::icon
                        icon="magnifying-glass"
                        size="6"
                    />
                </button>
            </x-slot:trigger>

            <div {{ $attributes->class(['search']) }}>
                {!! $form ?? $slot !!}
            </div>
        </x-moonshine::popover>

        <div x-show="!isPopover">
            <div {{ $attributes->class(['search']) }}>
                {!! $form ?? $slot !!}
            </div>
        </div>
    </div>
@endif
