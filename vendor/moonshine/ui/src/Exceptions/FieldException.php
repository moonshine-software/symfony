<?php

declare(strict_types=1);

namespace MoonShine\UI\Exceptions;

use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Core\Exceptions\MoonShineException;

final class FieldException extends MoonShineException
{
    /**
     * @param  class-string<FieldContract>  $fieldClass
     */
    public static function resourceRequired(string $fieldClass, ?string $fieldIdentification = null): self
    {
        return new self(
            "Resource is required for $fieldClass"
            . ($fieldIdentification ? " ($fieldIdentification)" : ""),
        );
    }

    public static function notFound(): self
    {
        return new self('Field not found');
    }

    /**
     * @param  class-string<FieldContract>  $fieldClass
     */
    public static function reactivityNotSupported(string $fieldClass, ?string $note = null): self
    {
        return new self(
            \sprintf("The %s%s does not support reactivity", $fieldClass, \is_null($note) ? '' : "($note)")
        );
    }
}
