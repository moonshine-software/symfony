<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use MoonShine\Contracts\Core\DependencyInjection\StorageContract;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Contracts\Core\ResourceContract;
use MoonShine\Core\Core;
use MoonShine\Core\Storage\FileStorage;
use Symfony\Contracts\Service\ResetInterface;

final class MoonShine extends Core implements ResetInterface
{
    public function runningUnitTests(): bool
    {
        return false;
    }

    public function runningInConsole(): bool
    {
        return false;
    }

    public function isLocal(): bool
    {
        return false;
    }

    public function isProduction(): bool
    {
        return false;
    }

    public function getContainer(?string $id = null, mixed $default = null, ...$parameters): mixed
    {
        if($id === null) {
            return $this->container;
        }

        return $this->container->get($id);
    }

    public function getStorage(...$parameters): StorageContract
    {
        return new FileStorage();
    }

    public function autoload(?string $namespace = null): static
    {
        $namespace ??= $this->getConfig()->getNamespace();

        $pages = $this->getOptimizer()->getType(PageContract::class, $namespace);
        $resources = $this->getOptimizer()->getType(ResourceContract::class, $namespace);

        return $this
            ->pages($pages)
            ->resources($resources);
    }

    public function reset(): void
    {
        $this->flushState();
    }
}
