<?php

declare(strict_types=1);

namespace MoonShine\Contracts\Core;

use MoonShine\Support\Enums\Action;

/**
 * @internal
 */
interface CrudResourceWithActionsContract
{
    public function hasAction(Action ...$actions): bool;

    public function hasAnyAction(Action ...$actions): bool;
}
