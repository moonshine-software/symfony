<?php

declare(strict_types=1);

namespace MoonShine\Contracts\Core;

/**
 * @internal
 */
interface CrudResourceWithSearchContract
{
    public function hasSearch(): bool;

    /**
     * @return string[]
     */
    public function getSearchColumns(): array;
}
