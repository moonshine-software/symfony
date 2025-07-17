<?php

declare(strict_types=1);

namespace MoonShine\Support\Enums;

enum TextWrap: string
{
    case CLAMP = 'clamp';

    case ELLIPSIS = 'ellipsis';
}
