<?php

declare(strict_types=1);

namespace MoonShine\Support\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use MoonShine\Contracts\UI\ComponentAttributesBagContract;
use MoonShine\Support\Components\MoonShineComponentAttributeBag;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class FileItem implements Arrayable
{
    public function __construct(
        private string $fullPath,
        private string $rawValue,
        private string $name,
        private ComponentAttributesBagContract $attributes = new MoonShineComponentAttributeBag(),
        private ?FileItemExtra $extra = null,
    ) {
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function getRawValue(): string
    {
        return $this->rawValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExtra(): ?FileItemExtra
    {
        return $this->extra;
    }

    public function getAttributes(): ComponentAttributesBagContract
    {
        return $this->attributes;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'full_path' => $this->getFullPath(),
            'raw_value' => $this->getRawValue(),
            'name' => $this->getName(),
            'attributes' => $this->getAttributes(),
            'extra' => $this->getExtra()?->toArray(),
        ];
    }
}
