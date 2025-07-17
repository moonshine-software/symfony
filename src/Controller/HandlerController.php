<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Contracts\Core\DependencyInjection\RequestContract;
use MoonShine\Crud\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class HandlerController extends MoonShineController
{
    #[Route('/admin/resource/{resourceUri}/{handlerUri}/handler', name: 'moonshine.handler', methods: ['GET'])]
    public function __invoke(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        return JsonResponse::make()->toast('Test');
    }
}