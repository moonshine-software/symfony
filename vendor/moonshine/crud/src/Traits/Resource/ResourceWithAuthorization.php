<?php

declare(strict_types=1);

namespace MoonShine\Crud\Traits\Resource;

use MoonShine\Core\Exceptions\ResourceException;
use MoonShine\Crud\Exceptions\CrudResourceException;
use MoonShine\Support\Enums\Ability;

trait ResourceWithAuthorization
{
    protected bool $withPolicy = false;

    /**
     * @return list<Ability>
     */
    public function getGateAbilities(): array
    {
        return [
            Ability::VIEW_ANY,
            Ability::VIEW,
            Ability::CREATE,
            Ability::UPDATE,
            Ability::DELETE,
            Ability::MASS_DELETE,
            Ability::RESTORE,
            Ability::FORCE_DELETE,
        ];
    }

    /**
     * @throws ResourceException
     */
    public function can(string|Ability $ability): bool
    {
        $abilityEnum = $ability instanceof Ability ? $ability : Ability::tryFrom($ability);

        if (\is_null($abilityEnum) || ! \in_array($abilityEnum, $this->getGateAbilities(), true)) {
            throw CrudResourceException::abilityNotFound($abilityEnum ?? $ability);
        }

        return $this->isCan($ability);
    }

    protected function isCan(Ability $ability): bool
    {
        return true;
    }

    public function isWithPolicy(): bool
    {
        return $this->withPolicy;
    }
}
