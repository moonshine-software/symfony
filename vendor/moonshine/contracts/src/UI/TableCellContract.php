<?php

declare(strict_types=1);

namespace MoonShine\Contracts\UI;

interface TableCellContract extends ComponentContract
{
    public function getIndex(): int;
}
