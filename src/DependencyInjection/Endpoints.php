<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use MoonShine\Contracts\Core\PageContract;
use MoonShine\Contracts\Core\ResourceContract;
use MoonShine\Core\AbstractEndpoints;
use Symfony\Component\HttpFoundation\RedirectResponse;

final readonly class Endpoints extends AbstractEndpoints
{
    public function toPage(
        string|PageContract|null $page = null,
        ResourceContract|string|null $resource = null,
        array $params = [],
        array $extra = [],
    ): string|RedirectResponse {
        $redirect = $extra['redirect'] ?? false;
        $fragment = $extra['fragment'] ?? null;

        if (\is_array($fragment)) {
            $fragment = implode(',', array_map(
                static fn ($key, $value): string => "$key:$value",
                array_keys($fragment),
                $fragment
            ));
        }

        if ($fragment !== null && $fragment !== '') {
            $params += ['_fragment-load' => $fragment];
        }

        $url = $this->router->to($resource === null ? 'page' : 'resource.page', [
            'pageUri' => $this->router->getParam('pageUri', $this->router->extractPageUri($page)),
            'resourceUri' => $this->router->getParam('resourceUri', $this->router->extractResourceUri($resource)),
            ...$params,
            ...$extra,
        ]);

        if($redirect) {
            return new RedirectResponse($url);
        }

        return $url;
    }

    public function home(): string
    {
        return '/admin';
    }
}
