<?php

declare(strict_types=1);

namespace MoonShine\Crud\Concerns\Page;

use MoonShine\Crud\Contracts\Page\IndexPageContract;
use MoonShine\Crud\QueryTags\QueryTag;

/**
 * @mixin IndexPageContract
 */
trait HasQueryTags
{
    /**
     * @return list<QueryTag>
     */
    public function getQueryTags(): array
    {
        return $this->queryTags();
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [];
    }

    public function hasQueryTags(): bool
    {
        return $this->queryTags() !== [];
    }
}
