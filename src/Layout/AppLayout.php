<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Layout;

use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\Components\{Layout\Body, Layout\Html, Layout\Layout};
use MoonShine\Crud\Components\Fragment;
use MoonShine\Contracts\MenuManager\MenuElementContract;
use MoonShine\UI\Components\Layout\Content;
use MoonShine\UI\Components\Layout\Flash;
use MoonShine\UI\Components\Layout\Wrapper;

class AppLayout extends BaseLayout
{
    /**
     * @return list<MenuElementContract>
     */
    protected function menu(): array
    {
        return $this->autoloadMenu();
    }

    public function build(): Layout
    {
        return Layout::make([
            Html::make([
                $this->getHeadComponent(),
                Body::make([
                    Wrapper::make([
                        // $this->getTopBarComponent(),
                        $this->getSidebarComponent(),

                        Fragment::make([
                            Fragment::make([
                                Flash::make(),

                                $this->getHeaderComponent(),

                                Content::make($this->getContentComponents()),

                                $this->getFooterComponent(),
                            ])->class('layout-page')->name(self::CONTENT_FRAGMENT_NAME),
                        ])->class('flex grow overflow-auto')->customAttributes(['id' => self::CONTENT_ID]),
                    ]),
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
