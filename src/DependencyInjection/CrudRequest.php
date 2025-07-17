<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;


use Illuminate\Support\Str;
use MoonShine\Contracts\Core\CrudResourceContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Contracts\Core\PageContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CrudRequest implements CrudRequestContract
{
    public function __construct(private CoreContract $core) {

    }

    public function getResource(): ?CrudResourceContract
    {
        $uri = $this->getResourceUri();

        if($uri === null) {
            return null;
        }

        return $this->core->getResources()->findByUri($uri);
    }

    public function getResourceUri(): ?string
    {
        $parts = explode('/', trim($this->core->getRequest()->getPath(), '/'));

        if(count($parts) < 2) {
            return null;
        }

        if(in_array($parts[1], ['component', 'reactive', 'method'], true)) {
            return $parts[3] ?? null;
        }

        return $parts[2] ?? null;
    }

    public function hasResource(): bool
    {
        return $this->getResource() !== null;
    }

    public function findPage(): ?PageContract
    {
        $uri = $this->getPageUri();

        if($uri === null) {
            return null;
        }

        if ($this->hasResource()) {
            return $this->getResource()
                ?->getPages()
                ?->findByUri($uri);
        }

        return $this->core->getPages()->findByUri($uri);
    }

    public function getPage(): PageContract
    {
        $page = $this->findPage();

        if($page === null) {
            throw new NotFoundHttpException();
        }

        return $page;
    }

    public function getPageUri(): ?string
    {
        $parts = explode('/', trim($this->core->getRequest()->getPath(), '/'));

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

    public function getItemID(): int|string|null
    {
        $id = $this->core->getRequest()->get('resourceItem');

        if($id !== null) {
            return $id;
        }

        $parts = explode('/', trim($this->core->getRequest()->getPath(), '/'));

        return $parts[4] ?? null;
    }

    public function getComponentName(): string
    {
        return Str::of($this->core->getRequest()->get('_component_name'))
            ->before('-unique-')
            ->value();
    }

    public function getFragmentLoad(): ?string
    {
        return $this->core->getRequest()->getScalar('_fragment-load');
    }

    public function isFragmentLoad(?string $name = null): bool
    {
        $fragment = $this->getFragmentLoad();

        if (! \is_null($fragment) && ! \is_null($name)) {
            return $fragment === $name;
        }

        return ! \is_null($fragment);
    }

    // TODO
    public function isMoonShineRequest(): bool
    {
        return true;
    }

    public function getParentResourceId(): ?string
    {
        return $this->core->getRequest()->getScalar('_parentId');
    }

    public function getParentRelationName(): ?string
    {
        if (\is_null($parentResource = $this->getParentResourceId())) {
            return null;
        }

        return Str::of($parentResource)
            ->replace('-' . $this->getParentRelationId(), '')
            ->camel()
            ->value();
    }

    public function getParentRelationId(): int|string|null
    {
        return
            \is_null($parentResource = $this->getParentResourceId())
                ? null
                : Str::of($parentResource)->after('-')->value();
    }
}