<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Application;

use Hiberus\Skeleton\Shared\Domain\Exception\AlreadyStoredException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Description;
use Hiberus\Skeleton\Ticket\Domain\Status;
use Hiberus\Skeleton\Ticket\Domain\TicketUpdater as TicketUpdaterDomain;
use Hiberus\Skeleton\Ticket\Domain\Title;

final class TicketUpdater
{
    public function __construct(
        private readonly TicketUpdaterDomain $ticketUpdater,
        private readonly TicketFinder $finder,
    )
    {
    }

    /**
     * @throws InvalidValueException
     * @throws ResourceNotFoundException
     * @throws AlreadyStoredException
     */
    public function __invoke(
        Uuid        $id,
        Uuid        $userId,
        Uuid        $commentId,
        Title       $commentTitle,
        Description $commentDescription,
        ?Status     $status
    ): void
    {
        $ticket = $this->finder->__invoke($id);
        $ticket->createComment(
            $userId,
            $commentId,
            $commentTitle,
            $commentDescription,
        );

        $ticket->changeStatus($status);
        $this->ticketUpdater->__invoke($ticket);
    }
}
