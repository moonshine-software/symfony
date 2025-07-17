<?php

declare(strict_types=1);

namespace MoonShine\UI\Fields;

use Closure;

/**
 * @method static static make(Closure|string|null $label = 'ID', ?string $column = 'id', ?Closure $formatted = null)
 */
class ID extends Hidden
{
    protected string $field = 'id';

    protected Closure|string $label = 'ID';

    protected function resolveOnApply(): ?Closure
    {
        return function ($item) {
            if ($this->getRequestValue() !== false) {
                data_set($item, $this->getColumn(), $this->getRequestValue());
            }

            return $item;
        };
    }
}
