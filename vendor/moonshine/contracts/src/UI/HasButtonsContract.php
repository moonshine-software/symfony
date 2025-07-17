<?php

declare(strict_types=1);

namespace MoonShine\Contracts\UI;

use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\Collection\ActionButtonsContract;

interface HasButtonsContract
{
    /**
     * @param  list<ActionButtonContract>|ActionButtonsContract  $buttons
     *
     */
    public function buttons(iterable $buttons = []): static;

    public function hasButtons(): bool;

    public function getButtons(DataWrapperContract $data): ActionButtonsContract;
}
