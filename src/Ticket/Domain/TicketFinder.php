<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain;

use Hiberus\Skeleton\Shared\Domain\Aggregate\AggregateRoot;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourcing;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

final class TicketFinder
{
    public function __construct(
        private readonly EventSourcing $repository
    )
    {
    }

    public function __invoke(Uuid $id): Ticket|AggregateRoot
    {
        return $this->repository->load($id, Ticket::class);
    }
}
