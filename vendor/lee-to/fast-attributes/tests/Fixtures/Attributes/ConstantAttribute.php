<?php

declare(strict_types=1);

namespace Leeto\FastAttributes\Tests\Fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final class ConstantAttribute
{
    public function __construct(
        public string $variable
    )
    {
    }
}
