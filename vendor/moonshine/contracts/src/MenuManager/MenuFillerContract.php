<?php

declare(strict_types=1);

namespace MoonShine\Contracts\MenuManager;

interface MenuFillerContract
{
    public function getTitle(): string;

    public function getUrl(): string;

    public function getBadge(): ?string;

    public function getGroup(): ?string;

    public function getGroupIcon(): ?string;

    public function isActive(): bool;

    public function skipMenu(): bool;

    public function canSee(): bool;

    public function getIcon(): ?string;

    public function getPosition(): int;
}
