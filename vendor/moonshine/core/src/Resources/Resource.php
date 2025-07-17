<?php

declare(strict_types=1);

namespace MoonShine\Core\Resources;

use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\Core\DependencyInjection\RouterContract;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Contracts\Core\PagesContract;
use MoonShine\Contracts\Core\ResourceContract;
use MoonShine\Core\Pages\Pages;
use MoonShine\Core\Traits\WithAssets;
use MoonShine\Core\Traits\WithCore;
use MoonShine\Core\Traits\WithUriKey;
use MoonShine\Support\Concerns\MenuFillerConcern;

/**
 * @template TPage of PageContract = PageContract
 * @template TCore of CoreContract = CoreContract
 *
 * @implements ResourceContract<TPage, TCore>
 */
abstract class Resource implements ResourceContract
{
    use WithCore;
    use WithUriKey;
    use WithAssets;
    use MenuFillerConcern;

    protected string $title = '';

    protected ?Pages $pages = null;

    protected bool $booted = false;

    protected bool $loaded = false;

    public function __construct(
        CoreContract $core,
    ) {
        $this->setCore($core);
        $this->booted();
    }

    /**
     * @return list<class-string<PageContract>>
     */
    abstract protected function pages(): array;

    /**
     * @return Pages<TPage>
     */
    public function getPages(): PagesContract
    {
        if (! \is_null($this->pages)) {
            return $this->pages;
        }

        $this->pages = Pages::make($this->pages())
            ->map(fn (string $page) => $this->getCore()->getContainer()->get($page))
            ->setResource($this);

        return $this->pages;
    }

    public function flushState(): void
    {
        //
    }

    protected function onBoot(): void
    {
        //
    }

    public function booted(): static
    {
        if ($this->booted) {
            return $this;
        }

        $this->setupTraits('boot');
        $this->onBoot();

        $this->booted = true;

        return $this;
    }

    protected function onLoad(): void
    {
        //
    }

    public function loaded(): static
    {
        if ($this->loaded) {
            return $this;
        }

        $this->setupTraits('load');
        $this->onLoad();

        $this->loaded = true;

        return $this;
    }

    protected function setupTraits(string $prefix): void
    {
        $class = static::class;

        $booted = [];
        /** @var list<object|string> $traits */
        $traits = class_uses_recursive($class);

        foreach ($traits as $trait) {
            $method = $prefix . class_basename($trait);

            if (method_exists($class, $method) && ! \in_array($method, $booted, true)) {
                $this->{$method}();

                $booted[] = $method;
            }
        }
    }

    public function getTitle(): string
    {
        return $this->title ?: class_basename($this);
    }

    public function getRouter(): RouterContract
    {
        return (clone $this->getCore()->getRouter())->withResource($this);
    }

    public function getUrl(): string
    {
        return $this->getRouter()
            ->withPage($this->getPages()->first())
            ->to('resource.page')
        ;
    }

    public function isActive(): bool
    {
        return $this->getCore()->getRouter()->extractResourceUri() === $this->getUriKey();
    }
}
