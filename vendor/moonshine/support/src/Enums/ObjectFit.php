<?php

declare(strict_types=1);

namespace MoonShine\Support\Enums;

enum ObjectFit: string
{
    case CONTAIN = 'contain';
    case COVER = 'cover';
    case FILL = 'fill';
    case NONE = 'none';
    case SCALE_DOWN = 'scale-down';
}
