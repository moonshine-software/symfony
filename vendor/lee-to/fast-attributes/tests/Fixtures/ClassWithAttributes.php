<?php

declare(strict_types=1);

namespace Leeto\FastAttributes\Tests\Fixtures;

use Leeto\FastAttributes\Tests\Fixtures\Attributes\ClassAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\ConstantAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\MethodAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\ParameterAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\PropertyAttribute;

#[ClassAttribute('some value')]
final class ClassWithAttributes
{
    #[ConstantAttribute('some value')]
    const VARIABLE = 'VARIABLE';

    #[PropertyAttribute('some value')]
    public string $variable;

    #[MethodAttribute('some value')]
    public function someMethod(
        #[ParameterAttribute('some value')]
        string $variable
    ): void
    {

    }
}
