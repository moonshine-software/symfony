<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use Illuminate\Support\Arr;
use MoonShine\Core\AbstractRequest;

final class Request extends AbstractRequest
{
    public function getSession(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    public function getFormErrors(?string $bag = null): array
    {
        return [];
    }

    public function getOld(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    public function getFile(string $key): mixed
    {
        return Arr::get($this->getRequest()->getUploadedFiles(), $key);
    }
}
