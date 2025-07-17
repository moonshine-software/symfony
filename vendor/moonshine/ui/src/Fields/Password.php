<?php

declare(strict_types=1);

namespace MoonShine\UI\Fields;

use Closure;
use Illuminate\Contracts\Hashing\Hasher;
use MoonShine\Support\Enums\TextWrap;

class Password extends Text
{
    protected string $type = 'password';

    protected bool $hasOld = false;

    protected ?TextWrap $textWrap = null;

    protected function resolvePreview(): string
    {
        return '***';
    }

    protected function resolveValue(): string
    {
        return '';
    }

    protected function resolveOnApply(): ?Closure
    {
        return function ($item) {
            if ($this->getRequestValue()) {
                data_set(
                    $item,
                    $this->getColumn(),
                    $this->getCore()->getContainer(Hasher::class)->make($this->getRequestValue())
                );
            }

            return $item;
        };
    }
}
