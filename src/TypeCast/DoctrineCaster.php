<?php

declare(strict_types=1);

namespace MoonShine\Symfony\TypeCast;

use MoonShine\Contracts\Core\Paginator\PaginatorContract;
use MoonShine\Contracts\Core\TypeCasts\DataCasterContract;

/**
 * @template  T of object
 *
 * @implements DataCasterContract<T>
 */
final readonly class DoctrineCaster implements DataCasterContract
{
    public function __construct(
        /** @var class-string<T> $class */
        private string $class,
    ) {}

    /** @return class-string<T> $class */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return DoctrineDataWrapper<T>
     */
    public function cast(mixed $data): DoctrineDataWrapper
    {
        /** @var T $item */
        if(\is_array($data)) {
            $data = new ($this->getClass())(...$data);
        }

        /** @var DoctrineDataWrapper<T> */
        /** @noRector */
        return new DoctrineDataWrapper($data);
    }

    public function paginatorCast(mixed $data): ?PaginatorContract
    {
        return null;
    }
}
