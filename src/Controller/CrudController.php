<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Contracts\Core\DependencyInjection\RequestContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Crud\JsonResponse;
use MoonShine\Crud\Resources\CrudResource;
use MoonShine\Support\Enums\ToastType;
use MoonShine\UI\Enums\HtmlMode;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
final class CrudController extends MoonShineController
{
    #[Route('/admin/resource/{resourceUri}/crud/mass-delete', name: 'moonshine.crud.massDelete', methods: ['POST'])]
    public function massDelete(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        $resource = $crudRequest->getResource();

        if ($resource === null) {
            return JsonResponse::make()->toast('Resource not found', ToastType::ERROR);
        }

        $resource->setActivePage(
            $resource->getIndexPage(),
        );

        $resource->massDelete($request->get('ids', []));

        return JsonResponse::make()
            ->toast('Deleted successfully')
            ->redirect($resource->getIndexPageUrl());
    }

    #[Route('/admin/resource/{resourceUri}/crud', name: 'moonshine.crud.store', methods: ['POST'])]
    public function store(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        return $this->storeOrUpdate($crudRequest, $request);
    }

    #[Route('/admin/resource/{resourceUri}/crud/{resourceItem}/update', name: 'moonshine.crud.update', methods: ['POST'])]
    public function update(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        return $this->storeOrUpdate($crudRequest, $request);
    }

    #[Route('/admin/resource/{resourceUri}/crud/{resourceItem}/delete', name: 'moonshine.crud.destroy', methods: ['POST'])]
    public function destroy(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        $resource = $crudRequest->getResource();

        if ($resource === null) {
            return JsonResponse::make()->toast('Resource not found', ToastType::ERROR);
        }

        $resource->setActivePage(
            $resource->getIndexPage(),
        );

        $resource->delete(
            $resource->getCaster()->cast(
                $resource->getItemOrFail(),
            ),
        );

        return JsonResponse::make()->toast('Deleted')
            ->redirect(
                $resource->getIndexPageUrl(),
            );
    }

    private function storeOrUpdate(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        $resource = $crudRequest->getResource();

        if ($resource === null) {
            return JsonResponse::make()->toast(
                'Resource not found',
                ToastType::ERROR,
            );
        }

        $item = $resource->getItemOrInstance();

        $resource->setActivePage(
            $resource->getFormPage(),
        );

        $redirectRoute = static function (CrudResource $resource) use ($request): ?string {
            if ($request->has('_without-redirect')) {
                return null;
            }

            $redirect = $request->get('_redirect', $resource->getRedirectAfterSave());

            if (\is_null($redirect) && ! $resource->isCreateInModal() && $resource->isRecentlyCreated()) {
                return $resource->getFormPageUrl($resource->getCastedData());
            }

            return $redirect;
        };

        try {
            $item = $resource->save(
                $resource->getCaster()->cast(
                    $item,
                ),
            );
        } catch (Throwable $e) {
            return $resource->modifyErrorResponse(
                new Response($e->getMessage(), 500),
                $e,
            );
        }

        $resource->setItem($item->getOriginal());

        if ($request->isAjax() || $request->isWantsJson()) {
            $data = [];
            $castedData = $resource->getCastedData();

            $resource
                ->getFormFields()
                ->onlyFields()
                ->refreshFields()
                ->fillCloned($castedData?->toArray() ?? [], $castedData)
                ->each(function (FieldContract $field) use (&$data): void {
                    $data['htmlData'][] = [
                        'html' => (string)$field
                            ->resolveRefreshAfterApply()
                            ->render(),
                        'selector' => "[data-field-selector='{$field->getNameDot()}']",
                        'htmlMode' => HtmlMode::OUTER_HTML->value,
                    ];
                });

            $redirect = $redirectRoute($resource);

            return $resource->modifySaveResponse(
                JsonResponse::make($data)
                    ->toast('Saved')
                    ->when(
                        $redirect,
                        static fn(JsonResponse $response): JsonResponse => $response->redirect($redirect),
                    )
                    ->setStatusCode($resource->isRecentlyCreated() ? Response::HTTP_CREATED : Response::HTTP_OK),
            );
        }


        if (\is_null($redirectRoute($resource))) {
            return new RedirectResponse(
                $resource->getFormPageUrl($resource->getCastedData()),
            );
        }

        return new RedirectResponse(
            $redirectRoute($resource),
        );
    }
}