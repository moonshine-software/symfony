<?php

declare(strict_types=1);

namespace MoonShine\UI\Enums;

enum HtmlMode: string
{
    case INNER_HTML = 'inner_html';

    case OUTER_HTML = 'outer_html';

    case BEFORE_BEGIN = 'beforebegin';

    case AFTER_BEGIN = 'afterbegin';

    case BEFORE_END = 'beforeend';

    case AFTER_END = 'afterend';
}
