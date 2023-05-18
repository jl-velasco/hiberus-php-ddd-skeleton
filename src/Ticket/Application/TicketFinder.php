<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Application;

use Hiberus\Skeleton\Ticket\Domain\Ticket;
use Hiberus\Skeleton\Ticket\Domain\TicketFinder as TicketFinderDomain;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

final class TicketFinder
{
    public function __construct(private readonly TicketFinderDomain $ticketFinder)
    {
    }

    /** @throws ResourceNotFoundException */
    public function __invoke(Uuid $id): Ticket
    {
        return $this->ticketFinder->__invoke($id);
    }
}
