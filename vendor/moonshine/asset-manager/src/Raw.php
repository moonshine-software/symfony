<?php

declare(strict_types=1);

namespace MoonShine\AssetManager;

use MoonShine\Contracts\AssetManager\AssetElementContract;
use MoonShine\Support\Traits\Makeable;

/**
 * @method static static make(string $content)
 */
final readonly class Raw implements AssetElementContract
{
    use Makeable;

    public function __construct(
        private string $content,
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function toHtml(): string
    {
        return $this->getContent();
    }

    public function __toString(): string
    {
        return $this->getContent();
    }
}
