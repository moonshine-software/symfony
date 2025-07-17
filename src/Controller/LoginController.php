<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use App\MoonShine\Pages\LoginPage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class LoginController extends MoonShineController
{
    #[Route('/admin/login', name: 'moonshine.login', methods: ['GET'])]
    public function index(
        LoginPage $page,
    ): Response {
        return new Response(
            (string)$page->render(),
        );
    }

    #[Route('/admin/login', name: 'moonshine.authenticate', methods: ['POST'])]
    public function authenticate(): RedirectResponse
    {
        return new RedirectResponse('/admin');
    }
}