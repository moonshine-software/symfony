<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Layout;

use MoonShine\Crud\Components\Layout\Locales;
use MoonShine\Crud\Components\Layout\Notifications;
use MoonShine\Crud\Components\Layout\Search;
use MoonShine\Crud\Layouts\AbstractLayout;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Layout\Burger;
use MoonShine\Crud\Components\Fragment;
use MoonShine\UI\Components\Layout\Footer;
use MoonShine\UI\Components\Layout\Header;
use MoonShine\UI\Components\Layout\Logo;
use MoonShine\UI\Components\Layout\Meta;
use MoonShine\UI\Components\Layout\TopBar;
use MoonShine\UI\Components\Title;
use MoonShine\UI\Components\When;
use MoonShine\Symfony\DependencyInjection\MoonShine;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Breadcrumbs;
use MoonShine\UI\Components\Components;
use MoonShine\UI\Components\Layout\Assets;
use MoonShine\UI\Components\Layout\Favicon;
use MoonShine\UI\Components\Layout\Head;
use MoonShine\UI\Components\Layout\Menu;
use MoonShine\UI\Components\Layout\Sidebar;
use MoonShine\UI\Components\Layout\ThemeSwitcher;

/**
 * @extends AbstractLayout<MoonShine>
 */
abstract class BaseLayout extends AbstractLayout
{
    public const CONTENT_FRAGMENT_NAME = '_content';

    public const CONTENT_ID = '_moonshine-content';

    protected function getFaviconComponent(): Favicon
    {
        return Favicon::make();
    }

    protected function getHeadComponent(): Head
    {
        return Head::make([
            Meta::make()->customAttributes([
                'name' => 'csrf-token',
                'content' => '',
            ]),
            $this->getFaviconComponent()->bodyColor($this->getColorManager()->get('body')),

            Fragment::make([
                Assets::make(),
            ])->name('assets'),
        ])
            ->bodyColor($this->getColorManager()->get('body'))
            ->title($this->getPage()->getTitle() ?: $this->getCore()->getConfig()->getTitle());
    }

    protected function getLogoComponent(): Logo
    {
        return Logo::make(
            $this->getHomeUrl(),
            $this->getLogo(),
            $this->getLogo(small: true),
        );
    }

    protected function getProfileComponent(bool $sidebar = false): Fragment
    {
        return Fragment::make();
    }

    /**
     * @return list<ComponentContract>
     */
    protected function sidebarSlot(): array
    {
        return [];
    }

    /**
     * @return list<ComponentContract>
     */
    protected function sidebarTopSlot(): array
    {
        return [];
    }

    protected function getSidebarComponent(): Sidebar
    {
        return Sidebar::make([
            Fragment::make([
                Fragment::make([
                    $this->getLogoComponent()->minimized(),
                ])->class('menu-heading-logo'),

                Fragment::make([
                    ThemeSwitcher::make(),
                    ...$this->sidebarTopSlot(),
                    Fragment::make([
                        Burger::make(),
                    ])->class('menu-heading-burger'),
                ])->class('menu-heading-actions'),
            ])->class('menu-heading')->name('sidebar-top'),

            Fragment::make([
                ...$this->sidebarSlot(),
                Menu::make(),
                When::make(
                    fn (): bool => $this->isProfileEnabled(),
                    fn (): array => [
                        $this->getProfileComponent(sidebar: true),
                    ],
                ),
            ])->customAttributes([
                'class' => 'menu',
                ':class' => "asideMenuOpen && '_is-opened'",
            ])->name('sidebar-content'),
        ])->collapsed();
    }

    /**
     * @return list<ComponentContract>
     */
    protected function topBarSlot(): array
    {
        return [];
    }

    protected function getTopBarComponent(): Topbar
    {
        return TopBar::make([
            Fragment::make([
                $this->getLogoComponent()->minimized(),
            ])->class('menu-logo')->name('topbar-logo'),

            Fragment::make([
                Menu::make()->top(),
            ])->class('menu-navigation')->name('topbar-menu'),

            Fragment::make([
                ...$this->topBarSlot(),
                When::make(
                    fn (): bool => $this->isProfileEnabled(),
                    fn (): array => [
                        $this->getProfileComponent(),
                    ],
                ),

                Fragment::make()->class('menu-inner-divider'),
                ThemeSwitcher::make(),

                Fragment::make([
                    Burger::make(),
                ])->class('menu-burger'),
            ])->class('menu-actions')->name('topbar-actions'),
        ])->customAttributes([
            ':class' => "asideMenuOpen && '_is-opened'",
        ]);
    }

    protected function getHeaderComponent(): Header
    {
        return Header::make([
            Breadcrumbs::make($this->getPage()->getBreadcrumbs())->prepend($this->getHomeUrl(), icon: 'home'),
            $this->getSearchComponent(),
            When::make(
                fn (): bool => $this->isUseNotifications(),
                static fn (): array => [Notifications::make()],
            ),
            Locales::make(),
        ]);
    }

    protected function getSearchComponent(): ComponentContract
    {
        return Search::make();
    }

    protected function getFooterMenu(): array
    {
        return [
            'https://moonshine-laravel.com/docs' => 'Documentation',
        ];
    }

    protected function getFooterCopyright(): string
    {
        return \sprintf(
            <<<'HTML'
                &copy; 2021-%d Made with ❤️ by
                <a href="https://cutcode.dev"
                    class="font-semibold text-primary hover:text-secondary"
                    target="_blank"
                >
                    CutCode
                </a>
                HTML,
            date('Y'),
        );
    }

    protected function getFooterComponent(): Footer
    {
        return Footer::make()
            ->copyright($this->getFooterCopyright())
            ->menu($this->getFooterMenu());
    }

    protected function getHeadLang(): string
    {
        return str_replace('_', '-', $this->getCore()->getTranslator()->getLocale());
    }

    protected function getLogo(bool $small = false): string
    {
        $logo = $small ? 'logo-small.svg' : 'logo.svg';

        return $this->getAssetManager()->getAsset(
            $this->getCore()->getConfig()->getLogo($small) ?? "vendor/moonshine/$logo",
        );
    }

    /**
     * @return list<ComponentContract>
     */
    protected function getContentComponents(): array
    {
        $components = [
            Components::make(
                $this->getPage()->getComponents(),
            ),
        ];

        if ($this->withTitle()) {
            $hasSubtitle = $this->withSubTitle() && $this->getPage()->getSubtitle() !== '';

            return [
                Title::make($this->getPage()->getTitle())->class($hasSubtitle ? '' : 'mb-6'),
                $hasSubtitle ? Heading::make($this->getPage()->getSubtitle())->class('mb-6') : null,
                ...$components,
            ];
        }

        return $components;
    }

    protected function getHomeUrl(): string
    {
        return $this->getCore()->getRouter()->getEndpoints()->home();
    }

    protected function withTitle(): bool
    {
        return true;
    }

    protected function withSubTitle(): bool
    {
        return true;
    }

    protected function isAuthEnabled(): bool
    {
        return $this->getCore()->getConfig()->isAuthEnabled();
    }

    protected function isProfileEnabled(): bool
    {
        return $this->getCore()->getConfig()->isUseProfile();
    }

    protected function isUseNotifications(): bool
    {
        return $this->isAuthEnabled() && $this->getCore()->getConfig()->isUseNotifications();
    }

    protected function isAlwaysDark(): bool
    {
        return false;
    }
}
