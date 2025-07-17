<?php

declare(strict_types=1);

namespace MoonShine\Support\DTOs\Select;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class OptionGroup implements Arrayable
{
    public function __construct(
        private string $label,
        private Options $values,
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValues(): Options
    {
        return $this->values;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'values' => $this->getValues()->toArray(),
        ];
    }
}
