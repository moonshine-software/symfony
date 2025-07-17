<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use Leeto\FastAttributes\Attributes;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Contracts\Core\DependencyInjection\RequestContract;
use MoonShine\Crud\JsonResponse;
use MoonShine\Support\Attributes\AsyncMethod;
use MoonShine\Support\Enums\ToastType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
#[Route('/admin/method/{pageUri}/{resourceUri?}', name: 'moonshine.method')]
final class MethodController extends MoonShineController
{
    public function __invoke(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        $toast = [
            'type' => 'info',
            'message' => $request->getScalar('message', ''),
        ];

        try {
            $method = $request->getScalar('method');
            $page = $crudRequest->getPage();
            $pageOrResource = $crudRequest->hasResource()
                ? $crudRequest->getResource()
                : $page;

            $target = method_exists($page, $method) ? $page : $pageOrResource;

            if (! Attributes::for($target, AsyncMethod::class)->method($method)->first() instanceof AsyncMethod) {
                throw new \RuntimeException("$method does not exist");
            }

            // TODO DI resolve
            $result = $target?->{$method}($crudRequest);

            $toast = $request->getSession('toast', $toast);
        } catch (Throwable $e) {
            $result = $e;
        }


        if ($result instanceof SymfonyJsonResponse) {
            return $result;
        }

        if ($result instanceof BinaryFileResponse || $result instanceof StreamedResponse) {
            return $result;
        }

        if (\is_string($result)) {
            return new Response($result);
        }

        $redirect = $result instanceof RedirectResponse ? $result->getTargetUrl() : null;

        return JsonResponse::make()
            ->setStatusCode($result instanceof Throwable ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_OK)
            ->when(
                $redirect,
                fn(JsonResponse $response) => $response->redirect($result instanceof RedirectResponse ? $result->getTargetUrl() : null)
            )
            ->toast($result instanceof Throwable ? $result->getMessage() : $toast['message'], $result instanceof Throwable ? ToastType::ERROR : ToastType::from($toast['type']));
    }
}