<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use Illuminate\Support\Collection;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Contracts\Core\DependencyInjection\RequestContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Contracts\UI\HasReactivityContract;
use MoonShine\Crud\JsonResponse;
use MoonShine\UI\Components\FieldsGroup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/admin/reactive/{pageUri}/{resourceUri?}/{resourceItem?}', name: 'moonshine.reactive')]
final class ReactiveController extends MoonShineController
{
    public function __invoke(CrudRequestContract $crudRequest, RequestContract $request): Response
    {
        $page = $crudRequest->getPage();

        /** @var ?FormBuilderContract $form */
        $form = $page->getComponents()->findForm(
            $crudRequest->getComponentName()
        );

        if (\is_null($form)) {
            return new JsonResponse();
        }

        $fields = $form
            ->getPreparedFields()
            ->onlyFields()
            ->reactiveFields();

        $casted = null;
        $except = [];

        $values = (new Collection($request->get('values', [])))->map(function (mixed $value, string $column) use ($fields, &$casted, &$except) {
            $field = $fields->findByColumn($column);

            if (! $field instanceof HasReactivityContract) {
                return $value;
            }

            return $field->prepareReactivityValue($value, $casted, $except);
        });

        $fields->fill(
            $values->toArray(),
            // TODO
            $casted ?: null
        );

        foreach ($fields as $field) {
            $fields = $field->formName($form->getName())->getReactiveCallback(
                $fields,
                data_get($values, $field->getColumn()),
                $values->toArray(),
            );
        }

        $values = $fields
            ->mapWithKeys(static fn (FieldContract $field): array => [$field->getColumn() => $field->getReactiveValue()]);

        $fields = $fields->mapWithKeys(
            static fn (FieldContract $field): array => [$field->getColumn() => (string) FieldsGroup::make([$field])->render()]
        );

        return new JsonResponse(data: [
            'form' => $form,
            'fields' => $fields,
            'values' => $values,
        ]);
    }
}