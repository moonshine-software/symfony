<?php

declare(strict_types=1);

namespace MoonShine\Symfony\DependencyInjection;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class RequestFactory
{
    public static function create(): ServerRequestInterface
    {
        $symfonyRequest = SymfonyRequest::createFromGlobals();

        $psr17Factory = new Psr17Factory();

        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        return $psrHttpFactory->createRequest($symfonyRequest);
    }
}
