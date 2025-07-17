<?php

declare(strict_types=1);

namespace MoonShine\Symfony\TypeCast;

use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;

/**
 * @template T of object
 *
 * @implements DataWrapperContract<T>
 */
final readonly class DoctrineDataWrapper implements DataWrapperContract
{
    public function __construct(private object $entity)
    {
    }

    public function getOriginal(): object
    {
        return $this->entity;
    }

    public function getKey(): int|string|null
    {
        return $this->entity->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->entity->id,
            'name' => $this->entity->name,
        ];
    }
}
