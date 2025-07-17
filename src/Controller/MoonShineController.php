<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use MoonShine\Contracts\Core\CrudResourceContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\UI\TableBuilderContract;
use MoonShine\Contracts\UI\TableRowContract;
use MoonShine\Laravel\TypeCasts\ModelCaster;
use MoonShine\Symfony\Instances;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MoonShineController extends AbstractController
{
    public function __construct(
        CoreContract $core,
        Instances $instances,
    ) {
        // $core->autoload();

        $core
            ->resources($instances->resources)
            ->pages($instances->pages);
    }

    /**
     * @throws \Throwable
     */
    protected function responseWithTable(TableBuilderContract $table, int|string|null $key = null, ?CrudResourceContract $resource = null): TableBuilderContract|TableRowContract|string
    {
        if ($key === null) {
            return $table;
        }

        // TODO
        return $table;
    }
}