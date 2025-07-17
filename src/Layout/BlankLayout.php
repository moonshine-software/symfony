<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Layout;

use MoonShine\UI\Components\Layout\{Body, Html, Layout};
use MoonShine\UI\Components\Components;

final class BlankLayout extends BaseLayout
{
    public function build(): Layout
    {
        return Layout::make([
            Html::make([
                $this->getHeadComponent(),
                Body::make([
                    Components::make($this->getPage()->getComponents()),
                ]),
            ])
                ->customAttributes([
                    'lang' => $this->getHeadLang(),
                ])
                ->withAlpineJs()
                ->withThemes($this->isAlwaysDark()),
        ]);
    }
}
