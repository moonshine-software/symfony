<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Contracts\Core\DependencyInjection\RequestContract;
use MoonShine\Contracts\UI\TableBuilderContract;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/admin/component/{pageUri}/{resourceUri?}', name: 'moonshine.component')]
final class ComponentController extends MoonShineController
{
    public function __invoke(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        $page = $crudRequest->getPage();

        $component = $page->getComponents()->findByName(
            $crudRequest->getComponentName()
        );

        if (\is_null($component)) {
            return new Response();
        }

        if ($component instanceof TableBuilderContract) {
            $component = $this->responseWithTable($component, $request->get('_key'));
        }

        if (\is_string($component)) {
            return new Response();
        }

        return new Response(
            (string) $component->render()
        );
    }
}