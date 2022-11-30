<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\App\Controller\Home;

use Hiberus\Skeleton\Shared\Infrastructure\Symfony\WebController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class HomeGetWebController extends WebController
{
    /** @throws LoaderError|RuntimeError|SyntaxError */
    public function __invoke(Request $request): Response
    {
        return $this->render(
            'pages/home.html.twig',
            [
                'title' => 'Welcome',
                'description' => 'Web Hiberus DDD skeleton',
            ]
        );
    }

    protected function exceptions(): array
    {
        return [];
    }
}
