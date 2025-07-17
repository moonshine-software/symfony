<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class PageController extends MoonShineController
{
    #[Route('/admin/page/{pageUri}', name: 'moonshine.page')]
    public function default(CrudRequestContract $crudRequest): Response
    {
        return new Response(
            $crudRequest->getPage()->render(),
        );
    }

    #[Route('/admin/resource/{resourceUri}/{pageUri}/{resourceItem?}', name: 'moonshine.resource.page')]
    public function resource(CrudRequestContract $crudRequest): Response
    {
        return new Response(
            $crudRequest->getPage()->render(),
        );
    }
}