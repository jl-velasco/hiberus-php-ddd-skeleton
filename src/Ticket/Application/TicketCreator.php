<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Application;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourcing;
use Hiberus\Skeleton\Shared\Domain\Exception\AlreadyStoredException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Comment;
use Hiberus\Skeleton\Ticket\Domain\Description;
use Hiberus\Skeleton\Ticket\Domain\Ticket;
use Hiberus\Skeleton\Ticket\Domain\Title;

final class TicketCreator
{
    public function __construct(private readonly EventSourcing $repository)
    {
    }

    /**
     * @throws InvalidValueException
     * @throws AlreadyStoredException
     */
    public function create(
        Uuid        $id,
        Uuid        $userId,
        Uuid        $commentId,
        Title       $commentTitle,
        Description $commentDescription,
    ): void
    {
        $this->repository->save(
            Ticket::create(
                $id,
                $userId,
                $commentId,
                $commentTitle,
                $commentDescription,
            )
        );
    }
}
