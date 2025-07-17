<?php

declare(strict_types=1);

namespace MoonShine\Symfony;


final class Instances
{
    public function __construct(public iterable $resources, public iterable $pages)
    {
        $this->resources = $resources instanceof \Traversable ? iterator_to_array($resources) : $resources;
        $this->pages = $pages instanceof \Traversable ? iterator_to_array($pages) : $pages;
    }
}