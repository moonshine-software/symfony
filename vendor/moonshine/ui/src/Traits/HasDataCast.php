<?php

declare(strict_types=1);

namespace MoonShine\UI\Traits;

use MoonShine\Contracts\Core\TypeCasts\DataCasterContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Core\TypeCasts\MixedDataCaster;

/**
 * @template TData of mixed = mixed
 * @template TCaster of DataCasterContract<TData> = DataCasterContract
 * @template TWrapper of DataWrapperContract<TData> = DataWrapperContract
 *
 */
trait HasDataCast
{
    /** @var TCaster|null */
    protected ?DataCasterContract $cast = null;

    protected ?string $castKeyName = null;

    public function getCastKeyName(): ?string
    {
        return $this->castKeyName;
    }

    public function castKeyName(string $name): static
    {
        $this->castKeyName = $name;

        return $this;
    }

    public function hasCast(): bool
    {
        return ! \is_null($this->cast);
    }

    /**
     * @param  TCaster  $cast
     */
    public function cast(DataCasterContract $cast): static
    {
        $this->cast = $cast;

        return $this;
    }

    /**
     * @return TCaster
     */
    public function getCast(): DataCasterContract
    {
        return $this->cast;
    }

    /**
     * @param  TWrapper  $data
     */
    public function unCastData(DataWrapperContract $data): array
    {
        return $data->toArray();
    }

    /**
     * @param  TData|TWrapper  $data
     *
     * @return TWrapper
     */
    public function castData(mixed $data): DataWrapperContract
    {
        if ($data instanceof DataWrapperContract) {
            return $data;
        }

        if (! $this->hasCast()) {
            $this->cast(new MixedDataCaster($this->getCastKeyName()));
        }

        return $this->getCast()->cast($data);
    }
}
