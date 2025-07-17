<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Layout;

use MoonShine\Crud\Traits\WithComponentsPusher;
use MoonShine\UI\Components\Components;
use MoonShine\UI\Components\FlexibleRender;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Layout\{Body, Div, Html, Layout};

final class LoginLayout extends BaseLayout
{
    use WithComponentsPusher;

    protected ?string $title = null;

    protected ?string $description = null;

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title ?? $this->getCore()->getTranslator()->get(
            'moonshine::ui.login.title',
            ['moonshine_title' => $this->getCore()->getConfig()->getTitle()],
        );
    }

    public function getDescription(): string
    {
        return $this->description ?? $this->getCore()->getTranslator()->get('moonshine::ui.login.description');
    }

    public function build(): Layout
    {
        return Layout::make([
            Html::make([
                $this->getHeadComponent(),
                Body::make([
                    Div::make([
                        Div::make([
                            $this->getLogoComponent(),
                        ])->class('authentication-logo'),

                        Div::make([
                            Div::make([
                                Heading::make(
                                    $this->getTitle(),
                                ),
                                Div::make([
                                    FlexibleRender::make(
                                        $this->getDescription(),
                                    ),
                                ])->class('description'),
                            ])->class('authentication-header'),

                            Components::make($this->getPage()->getComponents()),
                        ])->class('authentication-content'),

                        ...$this->getPushedComponents(),
                    ])->class('authentication'),
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