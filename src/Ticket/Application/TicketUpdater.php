<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Application;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Comment;
use Hiberus\Skeleton\Ticket\Domain\Status;
use Hiberus\Skeleton\Ticket\Domain\TicketUpdater as TicketUpdaterDomain;

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
     */
    public function __invoke(Uuid $id, Comment $comment, ?Status $status): void
    {
        $ticket = $this->finder->__invoke($id);
        $ticket->createComment($comment);
        $ticket->changeStatus($status);

        $this->ticketUpdater->__invoke($ticket);
    }
}
