<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\DynamicComponent;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;
use MoonShine\Contracts\Core\DependencyInjection\ViewRendererContract;
use Symfony\Component\DependencyInjection\Container as SymfonyContainer;

final class ViewRenderer implements ViewRendererContract
{
    public function __construct(private SymfonyContainer $container) {}

    public function render(string $view, array $data = []): Renderable
    {
        $projectDir = $this->container->get('kernel')?->getProjectDir();
        $root = __DIR__ . '/../../vendor/moonshine/ui';

        $path = $root . '/resources/views';
        $pathAnonymous = $root . '/resources/views/components';

        $cache = $projectDir . '/var/cache';

        $compiler = new BladeCompiler(
            files: new Filesystem(),
            cachePath: $cache,
        );

        $compiler->componentNamespace('MoonShine\UI\Components', 'moonshine');
        $compiler->anonymousComponentNamespace($pathAnonymous, 'moonshine');


        $compiler->directive('csrf', fn() => '');
        $compiler->directive(
            'defineEvent',
            static fn ($e): string => "<?php echo MoonShine\Support\AlpineJs::eventBlade($e); ?>"
        );

        $compiler->directive(
            'defineEventWhen',
            static fn ($e): string => "<?php echo MoonShine\Support\AlpineJs::eventBladeWhen($e); ?>"
        );

        $blade = new CompilerEngine(
            compiler: $compiler,
            files: new Filesystem()
        );


        $engines = new EngineResolver();
        $engines->register('blade', fn () => $blade);

        $factory = new Factory(
            engines: $engines,
            finder: new FileViewFinder(
                files: new Filesystem(),
                paths: [$path, $pathAnonymous],
                extensions: ['blade.php']
            ),
            events: new Dispatcher(),
        );

        $factory = $factory->addNamespace('moonshine', $path);
        $factory = $factory->addNamespace('moonshine', $pathAnonymous);
        $factory = $factory->addNamespace(
            '__components',
            $projectDir . '/var/cache/compiled'
        );

        Container::getInstance()->bind(\Illuminate\Contracts\View\Factory::class, fn () => $factory);
        Container::getInstance()->bind(View::class, fn () => $factory);
        Container::getInstance()->bind('view.finder', function ($app) {
            return new FileViewFinder($app['files'], $app['config']['view.paths']);
        });
        Container::getInstance()->singleton('blade.compiler', function ($app) {
            return tap(new BladeCompiler(
                $app['files'],
                $app['config']['view.compiled'],
                $app['config']->get('view.relative_hash', false) ? $app->basePath() : '',
                $app['config']->get('view.cache', true),
                $app['config']->get('view.compiled_extension', 'php'),
            ), function ($blade) {
                $blade->component('dynamic-component', DynamicComponent::class);
            });
        });
        Container::getInstance()->bind('view', View::class);
        Container::getInstance()->bind('config', Configurator::class);
        Container::getInstance()->bind(Application::class, fn () => new class {
            public function getNamespace(): string
            {
                return 'App';
            }
        });

        return $factory->make($view, $data);
    }
}
