<?php

declare(strict_types=1);

namespace MoonShine\Contracts\Core;

use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;

/**
 * @internal
 * @template TFields of FieldsContract
 *
 */
interface CrudResourceWithFieldsContract
{
    /**
     * @return TFields
     */
    public function getIndexFields(): FieldsContract;

    /**
     * @return TFields
     */
    public function getFormFields(bool $withOutside = false): FieldsContract;

    /**
     * @return TFields
     */
    public function getDetailFields(bool $withOutside = false, bool $onlyOutside = false): FieldsContract;

    /**
     * @return TFields
     */
    public function getOutsideFields(): FieldsContract;
}
