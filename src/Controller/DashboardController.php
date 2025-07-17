<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Controller;

use App\MoonShine\Pages\Dashboard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class DashboardController extends MoonShineController
{
    #[Route('/admin', name: 'moonshine.index')]
    public function index(
        Dashboard $page,
    ): Response {
        return new Response(
            (string)$page->render(),
        );
    }
}