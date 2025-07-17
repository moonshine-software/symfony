<?php

declare(strict_types=1);

namespace MoonShine\Contracts\Core;

/**
 * @internal
 */
interface CrudResourceWithModalsContract
{
    public function isCreateInModal(): bool;

    public function isEditInModal(): bool;

    public function isDetailInModal(): bool;
}
