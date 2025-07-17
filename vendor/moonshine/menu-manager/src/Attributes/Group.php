<?php

declare(strict_types=1);

namespace MoonShine\MenuManager\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Group
{
    public function __construct(
        public string $label,
        public ?string $icon = null,
        public bool $translatable = false,
    ) {
    }
}
