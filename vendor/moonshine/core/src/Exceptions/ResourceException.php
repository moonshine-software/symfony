<?php

declare(strict_types=1);

namespace MoonShine\Core\Exceptions;

final class ResourceException extends MoonShineException
{
    public static function required(): self
    {
        return new self('Resource is required');
    }

    public static function notDeclared(): self
    {
        return new self('Resource is not declared. Declare the resource in the MoonShineServiceProvider');
    }

    public static function handlerContractRequired(): self
    {
        return new self('Resource with HasHandlersContract required');
    }
}
