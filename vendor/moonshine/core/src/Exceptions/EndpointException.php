<?php

declare(strict_types=1);

namespace MoonShine\Core\Exceptions;

final class EndpointException extends MoonShineException
{
    public static function pageRequired(): self
    {
        return new self('Page not exists');
    }

    public static function pageOrResourceRequired(): self
    {
        return new self('Page or resource must not be null');
    }
}
