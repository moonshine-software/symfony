<?php

declare(strict_types=1);

namespace MoonShine\Core\Attributes;

use Attribute;
use MoonShine\Contracts\UI\LayoutContract;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Layout
{
    public function __construct(
        /**
         * @var class-string<LayoutContract> $name
         */
        public string $name
    ) {
    }
}
