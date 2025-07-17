<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use MoonShine\Contracts\AssetManager\AssetResolverContract;

final class AssetResolver implements AssetResolverContract
{
    public function get(string $path): string
    {
        return '/' . ltrim($path, '/');
    }

    public function getDev(string $path): string
    {
        return $path;
    }

    public function isDev(): bool
    {
        return false;
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function getHotFile(): string
    {
        return '';
    }
}
