<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Symfony;

use Hiberus\Skeleton\Shared\Domain\Bus\Command\CommandBus;
use Hiberus\Skeleton\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class WebController extends ApiController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly RouterInterface $router,
        private readonly RequestStack $requestStack,
        QueryBus $queryBus,
        CommandBus $commandBus,
        ApiExceptionsHttpStatusCodeMapping $exceptionHandler
    ) {
        parent::__construct($queryBus, $commandBus, $exceptionHandler);
    }

    /**
     * @param array<mixed, string> $arguments
     *
     * @throws LoaderError|RuntimeError|SyntaxError
     */
    public function render(string $templatePath, array $arguments = []): SymfonyResponse
    {
        return new SymfonyResponse($this->twig->render($templatePath, $arguments));
    }

    public function redirect(string $routeName): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($routeName), 302);
    }

    public function redirectWithMessage(string $routeName, string $message): RedirectResponse
    {
        $this->addFlashFor('message', [$message]);

        return $this->redirect($routeName);
    }

    public function redirectWithErrors(
        string $routeName,
        ConstraintViolationListInterface $errors,
        Request $request
    ): RedirectResponse {
        $this->addFlashFor('errors', $this->formatFlashErrors($errors));
        $this->addFlashFor('inputs', $request->request->all());

        return new RedirectResponse($this->router->generate($routeName), 302);
    }

    /** @return array<string, mixed> */
    private function formatFlashErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[str_replace(['[', ']'], ['', ''], $violation->getPropertyPath())] = $violation->getMessage();
        }

        return $errors;
    }

    /** @param array<mixed> $messages */
    private function addFlashFor(string $prefix, array $messages): void
    {
        foreach ($messages as $key => $message) {
            $this->requestStack->getSession()->getFlashBag()->set($prefix . '.' . $key, $message);
        }
    }

    protected function mandatoryParams(): array
    {
        return [];
    }
}
