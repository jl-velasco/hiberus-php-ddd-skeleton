<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourcing;

final class TicketUpdater
{
    public function __construct(
        private readonly EventSourcing $repository
    )
    {
    }

    public function __invoke(Ticket $ticket): void
    {
        $this->repository->save($ticket);
    }
}
