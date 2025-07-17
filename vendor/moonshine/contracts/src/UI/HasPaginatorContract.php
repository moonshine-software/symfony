<?php

declare(strict_types=1);

namespace MoonShine\Contracts\UI;

use MoonShine\Contracts\Core\Paginator\PaginatorContract;

/**
 * @template TData of mixed
 */
interface HasPaginatorContract
{
    /** @param PaginatorContract<TData> $paginator  */
    public function paginator(PaginatorContract $paginator): static;

    public function hasPaginator(): bool;

    public function isSimplePaginator(): bool;

    /** @return  PaginatorContract<TData>|null  */
    public function getPaginator(bool $async = false): ?PaginatorContract;
}
