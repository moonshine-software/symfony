<?php

declare(strict_types=1);

namespace MoonShine\ColorManager;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Support\Traits\Conditionable;
use MoonShine\Contracts\ColorManager\ColorManagerContract;

/**
 * @method self primary(string $value, ?int $shade = null, bool $dark = false)
 * @method self secondary(string $value, ?int $shade = null, bool $dark = false)
 * @method self body(string $value, ?int $shade = null, bool $dark = false)
 * @method self dark(string $value, int|string|null $shade = null, bool $dark = false)
 * @method self successBg(string $value, ?int $shade = null, bool $dark = false)
 * @method self successText(string $value, ?int $shade = null, bool $dark = false)
 * @method self warningBg(string $value, ?int $shade = null, bool $dark = false)
 * @method self warningText(string $value, ?int $shade = null, bool $dark = false)
 * @method self error(string $value, ?int $shade = null, bool $dark = false)
 * @method self errorBg(string $value, ?int $shade = null, bool $dark = false)
 * @method self errorText(string $value, ?int $shade = null, bool $dark = false)
 * @method self infoBg(string $value, ?int $shade = null, bool $dark = false)
 * @method self infoText(string $value, ?int $shade = null, bool $dark = false)
 */
final class ColorManager implements ColorManagerContract
{
    use Conditionable;

    public const TEXT = '255, 255, 255';

    public const BG = '27, 37, 59';

    public const DEFAULT = [
        'primary' => '120, 67, 233',
        'secondary' => '236, 65, 118',
        'body' => self::BG,
        'dark' => [
            'DEFAULT' => '30, 31, 67',
            50 => '83, 103, 132', // search, toasts, progress bars
            100 => '74, 90, 121', // dividers
            200 => '65, 81, 114', // dividers
            300 => '53, 69, 103', // borders
            400 => '48, 61, 93', // dropdowns, buttons, pagination
            500 => '41, 53, 82', // buttons default bg
            600 => '40, 51, 78', // table row
            700 => '39, 45, 69', // background content
            800 => self::BG, // background sidebar
            900 => '15, 23, 42', // background
        ],

        'success-bg' => '0, 170, 0',
        'success-text' => self::TEXT,
        'warning-bg' => '255, 220, 42',
        'warning-text' => '139, 116, 0',
        'error' => '224, 45, 45',
        'error-bg' => '224, 45, 45',
        'error-text' => self::TEXT,
        'info-bg' => '0, 121, 255',
        'info-text' => self::TEXT,
    ];

    public const DARK = [
        'body' => self::BG,
        'success-bg' => '17, 157, 17',
        'success-text' => '178, 255, 178',
        'warning-bg' => '225, 169, 0',
        'warning-text' => '255, 255, 199',
        'error' => '185, 28, 28',
        'error-bg' => '190, 10, 10',
        'error-text' => '255, 197, 197',
        'info-bg' => '38, 93, 205',
        'info-text' => '179, 220, 255',
    ];

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $colors = self::DEFAULT;

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $darkColors = self::DARK;

    /**
     * @param  string  $value
     */
    public function background(string $value): static
    {
        return $this
            ->set('body', $value)
            ->set('dark.800', $value)
            ->set('body', $value, dark: true);
    }

    /**
     * @param  string  $value
     */
    public function tableRow(string $value): static
    {
        return $this
            ->set('dark.600', $value);
    }

    /**
     * @param  string  $value
     */
    public function borders(string $value): static
    {
        return $this
            ->set('dark.300', $value);
    }

    /**
     * @param  string  $value
     */
    public function dropdowns(string $value): static
    {
        return $this
            ->set('dark.400', $value);
    }

    /**
     * @param  string  $value
     */
    public function buttons(string $value): static
    {
        return $this
            ->set('dark.50', $value)
            ->set('dark.500', $value)
            ->dropdowns($value);
    }

    /**
     * @param  string  $value
     */
    public function dividers(string $value): static
    {
        return $this
            ->set('dark.100', $value)
            ->set('dark.200', $value);
    }

    /**
     * @param  string  $value
     */
    public function content(string $value): static
    {
        return $this
            ->set('dark.700', $value)
            ->set('dark.900', $value);
    }

    /**
     * @param  string  $name
     * @param  string|array<string|int, string>  $value
     *
     */
    public function set(string $name, string|array $value, bool $dark = false): static
    {
        /** @phpstan-ignore-next-line */
        data_set($this->{$dark ? 'darkColors' : 'colors'}, $name, $value);

        return $this;
    }

    /**
     * @api
     * @param array<string, string|array<string|int, string>> $colors
     */
    public function bulkAssign(array $colors, bool $dark = false): static
    {
        foreach ($colors as $name => $color) {
            $this->set($name, $color, $dark);
        }

        return $this;
    }

    public function get(string $name, ?int $shade = null, bool $dark = false, bool $hex = true): string
    {
        $data = $dark ? $this->darkColors : $this->colors;
        $value = $data[$name];
        $value = \is_null($shade)
            ? $value
            : $value[$shade];

        $hexValue = \is_array($value) ? $value['DEFAULT'] : $value;

        return $hex ?
            ColorMutator::toHEX($hexValue)
            : $hexValue;
    }

    /**
     * @return array<string, string>
     */
    public function getAll(bool $dark = false): array
    {
        $colors = [];
        $data = $dark ? $this->darkColors : $this->colors;

        $formatRgb = static fn (string $rgb): string => str_replace(['rgb(', ')'], ['', ''], $rgb);

        foreach ($data as $name => $shades) {
            if (! \is_array($shades)) {
                $colors[$name] = $formatRgb(ColorMutator::toRGB($shades));
            } else {
                foreach ($shades as $shade => $color) {
                    $colors["$name-$shade"] = $formatRgb(ColorMutator::toRGB($color));
                }
            }
        }

        return $colors;
    }

    /**
     * @param array{value: string, shade: int|string|null, dark: bool}|array{string, int|string|null, bool} $arguments
     */
    public function __call(string $name, array $arguments): static
    {
        $value = $arguments['value'] ?? $arguments[0] ?? '';
        $shade = $arguments['shade'] ?? $arguments[1] ?? false;
        $dark = $arguments['dark'] ?? $arguments[2] ?? false;

        $this->set(
            name: Str::of($name)
                ->kebab()
                ->when(
                    $shade,
                    static fn (Stringable $str) => $str->append(".$shade")
                )
                ->value(),
            value: $value,
            dark: $dark,
        );

        return $this;
    }

    public function toHtml(): string
    {
        $values = static function (array $data): string {
            /** @var Collection<string, string> $collection */
            $collection = new Collection($data);

            return $collection
                ->implode(static fn (string $value, string $name): string => "--$name:$value;", PHP_EOL);
        };

        return <<<HTML
        <style>
            :root {
            {$values($this->getAll())}
            }
            :root.dark {
            {$values($this->getAll(dark: true))}
            }
        </style>
        HTML;
    }
}
