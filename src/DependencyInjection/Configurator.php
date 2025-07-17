<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use MoonShine\Contracts\Core\PageContract;
use Illuminate\Support\Arr;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Symfony\Layout\AppLayout;
use Symfony\Component\DependencyInjection\Container as SymfonyContainer;

// TODO
final class Configurator implements ConfiguratorContract
{
    public function __construct(
        private SymfonyContainer $container,
        private array $items = [],
    ) {}

    public function getNamespace(string $path = '', ?string $base = null): string
    {
        $base ??= $this->get('namespace');

        $path = str_replace('/', '\\', $path);

        return $base . '\\' . trim($path, '\\');
    }

    public function getTitle(): string
    {
        return $this->get('title');
    }

    public function getLogo(): string
    {
        return $this->get('logo');
    }

    public function isUseProfile(): bool
    {
        return false;
    }

    public function isAuthEnabled(): bool
    {
        return false;
    }

    public function isUseNotifications()
    {
        return true;
    }

    public function getLayout(): string
    {
        return $this->get('layout', AppLayout::class);
    }

    public function getForm(string $name, string $default, ...$parameters): FormBuilderContract
    {
        $class = $this->get("forms.$name", $default);

        return \call_user_func(
            new $class(...$parameters)
        );
    }

    public function getPage(string $name, string $default, ...$parameters): PageContract
    {
        $class = $this->get("pages.$name", $default);

        return $this->container->get($class);
    }

    public function getLocales(): array
    {
        return [];
    }

    public function getDisk(): string
    {
        return '';
    }

    public function getDiskOptions(): array
    {
        return [];
    }

    public function has(string $key): bool
    {
        return Arr::has($this->items, $key);
    }

    // TODO
    public function get(string $key, mixed $default = null): mixed
    {
        if($key === 'view.compiled') {
            return __DIR__ . '/../../../../var/cache/compiled';
        }

        return value(
            Arr::get($this->items, $key, $default)
        );
    }

    public function set(string $key, mixed $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->set($offset, null);
    }

    public function getPages(): array
    {
        return [];
    }

    public function getLocale(): string
    {
        return 'ru';
    }

    public function getLocaleKey(): string
    {
        return 'locale';
    }
}
