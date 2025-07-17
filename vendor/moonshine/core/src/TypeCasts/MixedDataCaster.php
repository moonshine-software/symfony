<?php

declare(strict_types=1);

namespace MoonShine\Core\TypeCasts;

use MoonShine\Contracts\Core\Paginator\PaginatorContract;
use MoonShine\Contracts\Core\TypeCasts\DataCasterContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;

final readonly class MixedDataCaster implements DataCasterContract
{
    public function __construct(private ?string $keyName = null)
    {
    }

    public function cast(mixed $data): DataWrapperContract
    {
        /** @var null|string|int $key */
        $key = $this->keyName && $data
            ? data_get($data, $this->keyName)
            : null;

        return new MixedDataWrapper($data, $key);
    }

    public function paginatorCast(mixed $data): ?PaginatorContract
    {
        return null;
    }
}
