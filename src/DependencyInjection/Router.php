<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use MoonShine\Contracts\Core\CrudResourceContract;
use MoonShine\Contracts\Core\DependencyInjection\EndpointsContract;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Contracts\Core\ResourceContract;
use MoonShine\Core\AbstractRouter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class Router extends AbstractRouter
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function to(string $name = '', array $params = []): string
    {
        return $this->urlGenerator->generate(
            $this->getName($name),
            $this->getParams($params),
        );
    }

    public function getEndpoints(): EndpointsContract
    {
        return new Endpoints($this, $this->urlGenerator);
    }

    public function extractPageUri(?PageContract $page = null): ?string
    {
        if($page !== null) {
            return $page->getUriKey();
        }

        $parts = explode('/', trim($this->urlGenerator->getContext()->getPathInfo(), '/'));

        if(count($parts) < 2) {
            return null;
        }

        if($parts[1] === 'page') {
            return $parts[2] ?? null;
        }

        if(in_array($parts[1], ['component', 'reactive', 'method'], true)) {
            return $parts[2] ?? null;
        }

        return $parts[3] ?? null;
    }

    public function extractResourceUri(?ResourceContract $resource = null): ?string
    {
        if($resource !== null) {
            return $resource->getUriKey();
        }

        $parts = explode('/', trim($this->urlGenerator->getContext()->getPathInfo(), '/'));

        if(count($parts) < 2) {
            return null;
        }

        if(in_array($parts[1], ['component', 'reactive', 'method'], true)) {
            return $parts[3] ?? null;
        }

        return $parts[2] ?? null;
    }

    public function extractResourceItem(
        int|string|null $key = null,
        ?CrudResourceContract $resource = null,
    ): string|int|null {
        if ($key !== null) {
            return $key;
        }

        if($resource?->getCastedData()?->getKey() !== null) {
            return $resource?->getCastedData()?->getKey();
        }

        $id = $this->getParam('resourceItem');

        if($id !== null) {
            return $id;
        }

        $parts = explode('/', trim($this->urlGenerator->getContext()->getPathInfo(), '/'));

        return $parts[4] ?? null;
    }
}
