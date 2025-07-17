<?php

declare(strict_types=1);

namespace MoonShine\Symfony;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class Routing extends Loader
{
    public function load($resource, ?string $type = null): RouteCollection
    {
        $routes = new RouteCollection();

        $importedRoutes = $this->import('@moonshine/config/routes.php', 'php');

        $routes->addCollection($importedRoutes);

        return $routes;
    }

    public function supports($resource, ?string $type = null): bool
    {
        return 'moonshine.routes' === $type;
    }
}