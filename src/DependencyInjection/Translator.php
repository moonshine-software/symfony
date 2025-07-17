<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use Countable;
use MoonShine\Contracts\Core\DependencyInjection\TranslatorContract;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Translator implements TranslatorContract
{
    public function __construct(private TranslatorInterface $translator)
    {

    }

    public function get(string $key, array $replace = [], ?string $locale = null): mixed
    {
        $key = str_replace('moonshine::', '', $key);

        return $this->translator->trans($key, $replace, 'moonshine', locale: 'en');
    }

    public function choice(
        string $key,
        Countable|float|int|array $number,
        array $replace = [],
        ?string $locale = null
    ): string {
        $key = str_replace('moonshine::', '', $key);

        return $this->translator->trans($key, $replace, 'moonshine', locale: $locale);
    }

    public function getLocale(): string
    {
        return $this->translator->getLocale();
    }

    public function all(?string $locale = null): array
    {
        return [];
    }
}
