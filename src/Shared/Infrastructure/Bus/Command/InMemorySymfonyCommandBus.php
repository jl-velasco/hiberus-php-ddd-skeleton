<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Command;

use Hiberus\Skeleton\Shared\Domain\Bus\Command\Command;
use Hiberus\Skeleton\Shared\Domain\Bus\Command\CommandBus;
use Hiberus\Skeleton\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use Hiberus\Skeleton\Shared\Infrastructure\Bus\TransactionSymfonyMiddleware;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class InMemorySymfonyCommandBus implements CommandBus
{
    private MessageBus $bus;

    /** @param iterable<mixed> $commandHandlers */
    public function __construct(iterable $commandHandlers, TransactionSymfonyMiddleware $transactionMiddleware)
    {
        $this->bus = new MessageBus(
            [
                $transactionMiddleware,
                new HandleMessageMiddleware(
                    new HandlersLocator(CallableFirstParameterExtractor::forCallables($commandHandlers))
                ),
            ]
        );
    }

    /** @throws \Throwable */
    public function dispatch(Command $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
