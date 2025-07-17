<?php

declare(strict_types=1);

namespace MoonShine\Contracts\Core\Paginator;

interface PaginatorCasterContract
{
    /**
     * @return PaginatorContract<mixed>
     */
    public function cast(): PaginatorContract;
}
