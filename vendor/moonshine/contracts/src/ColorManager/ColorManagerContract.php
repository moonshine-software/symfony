<?php

declare(strict_types=1);

namespace MoonShine\Contracts\ColorManager;

use Illuminate\Contracts\Support\Htmlable;

interface ColorManagerContract extends Htmlable
{
    public function get(string $name, ?int $shade = null, bool $dark = false, bool $hex = true): string;

    /**
     * @return array<string, string>
     */
    public function getAll(bool $dark = false): array;

    /**
     * @param  string  $name
     * @param  string|array<string|int, string>  $value
     *
     */
    public function set(string $name, string|array $value, bool $dark = false): static;
}
