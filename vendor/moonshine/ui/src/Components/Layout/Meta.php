<?php

declare(strict_types=1);

namespace MoonShine\UI\Components\Layout;

use Closure;
use MoonShine\UI\Components\MoonShineComponent;

final class Meta extends MoonShineComponent
{
    protected function resolveRender(): Closure
    {
        return function (): string {
            $name = $this->getName();
            $attributes = $this->getAttributes()->toHtml();

            if ($this->getAttributes()->has('name')) {
                return "<meta $attributes />";
            }

            return "<meta name=\"$name\" $attributes />";
        };
    }
}
