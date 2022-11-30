<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus;

use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

class TransactionSymfonyMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /** @throws \Exception|Throwable */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->connection->beginTransaction();

        try {
            $handle = $stack->next()->handle($envelope, $stack);

            $this->connection->commit();
        } catch (Throwable $error) {
            $this->connection->rollback();

            throw $error;
        }

        return $handle;
    }
}
