<?php

declare(strict_types=1);

namespace MoonShine\MenuManager;

use Closure;

/**
 * @method static static make(Closure|string $label = '')
 */
class MenuDivider extends MenuElement
{
    protected string $view = 'moonshine::components.menu.divider';

    final public function __construct(Closure|string $label = '')
    {
        parent::__construct();

        $this->setLabel($label);
    }

    public function isActive(): bool
    {
        return false;
    }

    protected function viewData(): array
    {
        return [
            'label' => $this->getLabel(),
        ];
    }
}
