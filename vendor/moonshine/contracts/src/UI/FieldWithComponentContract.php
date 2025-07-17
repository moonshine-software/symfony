<?php

declare(strict_types=1);

namespace MoonShine\Contracts\UI;

/**
 * @template TComponent of ComponentContract
 */
interface FieldWithComponentContract
{
    /**
     * @return TComponent
     */
    public function getComponent(): ComponentContract;
}
