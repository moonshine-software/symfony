<?php

declare(strict_types=1);

namespace MoonShine\Contracts\Core;

use MoonShine\Support\Enums\Ability;

/**
 * @internal
 */
interface CrudResourceWithAuthorizationContract
{
    /**
     * @return list<Ability>
     */
    public function getGateAbilities(): array;

    public function can(string|Ability $ability): bool;
}
