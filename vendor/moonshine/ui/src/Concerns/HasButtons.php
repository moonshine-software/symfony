<?php

declare(strict_types=1);

namespace MoonShine\UI\Concerns;

use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\Collection\ActionButtonsContract;
use MoonShine\UI\Collections\ActionButtons;

trait HasButtons
{
    /** @var list<ActionButtonContract>|ActionButtonsContract */
    protected iterable $buttons = [];

    /**
     * @param  list<ActionButtonContract>|ActionButtonsContract  $buttons
     *
     */
    public function buttons(iterable $buttons = []): static
    {
        $this->buttons = $buttons;

        return $this;
    }

    public function hasButtons(): bool
    {
        return $this->buttons !== [];
    }

    public function getButtons(DataWrapperContract $data): ActionButtonsContract
    {
        return ActionButtons::make($this->buttons)
            ->fill($data)
            ->onlyVisible()
            ->withoutBulk();
    }

    public function getBulkButtons(): ActionButtonsContract
    {
        return ActionButtons::make($this->buttons)
            ->bulk($this->getName())
            ->onlyVisible();
    }
}
