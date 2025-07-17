<?php

declare(strict_types=1);

namespace MoonShine\Symfony;

use MoonShine\AssetManager\AssetManager;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\AssetManager\AssetManagerContract;
use MoonShine\Contracts\AssetManager\AssetResolverContract;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\Core\DependencyInjection\AppliesRegisterContract;
use MoonShine\Contracts\Core\DependencyInjection\CacheAttributesContract;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\Core\DependencyInjection\OptimizerCollectionContract;
use MoonShine\Contracts\Core\DependencyInjection\RequestContract;
use MoonShine\Contracts\Core\DependencyInjection\RouterContract;
use MoonShine\Contracts\Core\DependencyInjection\TranslatorContract;
use MoonShine\Contracts\Core\DependencyInjection\ViewRendererContract;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Contracts\Core\ResourceContract;
use MoonShine\Contracts\MenuManager\MenuAutoloaderContract;
use MoonShine\Contracts\MenuManager\MenuManagerContract;
use MoonShine\Core\Collections\OptimizerCollection;
use MoonShine\Core\Support\CacheAttributes;
use MoonShine\Crud\Collections\Fields;
use MoonShine\Crud\Contracts\Notifications\MoonShineNotificationContract;
use MoonShine\Crud\Contracts\Page\DetailPageContract;
use MoonShine\Crud\Contracts\Page\FormPageContract;
use MoonShine\Crud\Contracts\Page\IndexPageContract;
use MoonShine\Crud\Notifications\MemoryNotification;
use MoonShine\Crud\Pages\DetailPage;
use MoonShine\Crud\Pages\FormPage;
use MoonShine\Crud\Pages\IndexPage;
use MoonShine\MenuManager\MenuAutoloader;
use MoonShine\MenuManager\MenuManager;
use MoonShine\Symfony\Command\InstallCommand;
use MoonShine\Symfony\Command\OptimizeCommand;
use MoonShine\Symfony\Controller\MoonShineController;
use MoonShine\Symfony\DependencyInjection\AssetResolver;
use MoonShine\Symfony\DependencyInjection\Configurator;
use MoonShine\Symfony\DependencyInjection\CrudRequest;
use MoonShine\Symfony\DependencyInjection\MoonShine;
use MoonShine\Symfony\DependencyInjection\Request;
use MoonShine\Symfony\DependencyInjection\RequestFactory;
use MoonShine\Symfony\DependencyInjection\Router;
use MoonShine\Symfony\DependencyInjection\Translator;
use MoonShine\Symfony\DependencyInjection\ViewRenderer;
use MoonShine\Symfony\Layout\AppLayout;
use MoonShine\UI\Applies\AppliesRegister;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class MoonShineBundle extends AbstractBundle
{
    protected string $name = 'moonshine';

    protected string $version = '0.0.1';

    public function getPath(): string
    {
        $reflected = new \ReflectionObject($this);

        return \dirname($reflected->getFileName(), 2);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition
            ->rootNode()
            ->children()
            ->scalarNode('namespace')->defaultValue('App\MoonShine')->end()
            ->scalarNode('title')->defaultValue('MoonShine')->end()
            ->scalarNode('logo')->defaultValue('/vendor/moonshine/logo-app.svg')->end()
            ->scalarNode('layout')->defaultValue(AppLayout::class)->end()
            ->end()
            ->end();
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass());
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $this->commands($container, $builder);
        $this->routing($container);
        $this->core($config, $builder);
        $this->request($builder);
        $this->layouts($container, $builder);
        $this->view($builder);
        $this->fields($builder);
        $this->assets($builder);
        $this->menu($builder);
        $this->cache($builder);
        $this->notifications($builder);
    }

    private function commands(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container
            ->services()
            ->set(InstallCommand::class)
            ->public()
            ->arg(0, $builder->getParameter('kernel.project_dir'))
            ->tag('console.command');
    }

    private function routing(ContainerConfigurator $container): void
    {
        $services = $container->services();

        $services
            ->set(Routing::class)
            ->tag('routing.loader');

        $services
            ->load('MoonShine\\Symfony\\Controller\\', $this->getPath() . '/src/Controller/*')
            ->autowire()
            ->autoconfigure();
    }

    private function request(ContainerBuilder $builder): void
    {
        $builder
            ->register(ServerRequestInterface::class, RequestFactory::class)
            ->addTag('moonshine.request_factory')
            ->setFactory([RequestFactory::class, 'create'])
            ->setAutowired(true);

        $builder
            ->register(RequestContract::class, Request::class)
            ->addTag('moonshine.request')
            ->setPublic(true)
            ->setAutowired(true);

        $builder
            ->register(CrudRequestContract::class, CrudRequest::class)
            ->addTag('moonshine.crud_request')
            ->setPublic(true)
            ->setAutowired(true);
    }

    private function core(array $config, ContainerBuilder $builder): void
    {
        $builder->registerForAutoconfiguration(ResourceContract::class)
            ->addTag('moonshine.resource');

        $builder->registerForAutoconfiguration(PageContract::class)
            ->addTag('moonshine.page');

        $builder->register(Instances::class)
            ->addArgument(new TaggedIteratorArgument('moonshine.resource'))
            ->addArgument(new TaggedIteratorArgument('moonshine.page'))
        ;

        $builder
            ->register(CoreContract::class, MoonShine::class)
            ->addArgument(new Reference('service_container'))
            ->addTag('moonshine.core')
            ->setPublic(true)
            ->setAutowired(true);


        $builder
            ->register(RouterContract::class, Router::class)
            ->addTag('moonshine.router')
            ->setAutowired(true);

        $builder
            ->register(ConfiguratorContract::class, Configurator::class)
            ->addArgument(new Reference('service_container'))
            ->addArgument($config)
            ->addTag('moonshine.configurator')
            ->setAutowired(true);

        $builder
            ->register(TranslatorContract::class, Translator::class)
            ->addTag('moonshine.translator')
            ->setAutowired(true);
    }

    private function layouts(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services();

        $services
            ->load('MoonShine\\Symfony\\Layout\\', $this->getPath() . '/src/Layout/*')
            ->autowire()
            ->autoconfigure()
            ->public();

        $builder
            ->register(IndexPageContract::class, IndexPage::class)
            ->setAutowired(true);

        $builder
            ->register(DetailPageContract::class, DetailPage::class)
            ->setAutowired(true);

        $builder
            ->register(FormPageContract::class, FormPage::class)
            ->setAutowired(true);
    }

    private function view(ContainerBuilder $builder): void
    {
        $builder
            ->register(ViewRendererContract::class, ViewRenderer::class)
            ->addArgument(new Reference('service_container'))
            ->addTag('moonshine.view_renderer')
            ->setAutowired(true);
    }

    private function fields(ContainerBuilder $builder): void
    {
        $builder
            ->register(FieldsContract::class, Fields::class)
            ->addTag('moonshine.fields_collection')
            ->setPublic(true)
            ->setShared(false)
            ->setAutowired(true);

        $builder
            ->register(AppliesRegisterContract::class, AppliesRegister::class)
            ->addTag('moonshine.applies')
            ->setPublic(true)
            ->setAutowired(true);
    }

    private function assets(ContainerBuilder $builder): void
    {
        $builder
            ->register(AssetManagerContract::class, AssetManager::class)
            ->addTag('moonshine.asset_manager')
            ->setPublic(true)
            ->setAutowired(true);

        $builder
            ->register(AssetResolverContract::class, AssetResolver::class)
            ->addTag('moonshine.asset_resolver')
            ->setAutowired(true);

        $builder
            ->register(ColorManagerContract::class, ColorManager::class)
            ->addTag('moonshine.color_manager')
            ->setPublic(true)
            ->setAutowired(true);
    }

    private function menu(ContainerBuilder $builder): void
    {
        $builder
            ->register(MenuManagerContract::class, MenuManager::class)
            ->addTag('moonshine.menu_manager')
            ->setPublic(true)
            ->setAutowired(true);

        $builder
            ->register(MenuAutoloaderContract::class, MenuAutoloader::class)
            ->addTag('moonshine.menu_autoloader')
            ->setAutowired(true);
    }

    private function cache(ContainerBuilder $builder): void
    {
        $builder
            ->register(CacheInterface::class, Psr16Cache::class)
            ->setArguments([new Reference('cache.app')])
            ->setAutowired(true);

        $builder
            ->register(OptimizerCollectionContract::class, OptimizerCollection::class)
            ->addArgument('/var/cache')
            ->addTag('moonshine.optimizer')
            ->setAutowired(true);

        $builder
            ->register(CacheAttributesContract::class, CacheAttributes::class)
            ->addTag('moonshine.cache_attributes')
            ->setPublic(true)
            ->setAutowired(true);
    }

    private function notifications(ContainerBuilder $builder): void
    {
        $builder
            ->register(MoonShineNotificationContract::class, MemoryNotification::class)
            ->addTag('moonshine.notifications')
            ->setPublic(true)
            ->setAutowired(true);
    }
}
