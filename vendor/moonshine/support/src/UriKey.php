<?php

declare(strict_types=1);

namespace MoonShine\Support;

use Illuminate\Support\Str;

final readonly class UriKey
{
    /**
     * @param  class-string  $class
     */
    public function __construct(private string $class)
    {
    }

    public function generate(): string
    {
        return Str::of($this->class)
            ->classBasename()
            ->kebab()
            ->value();
    }
}
