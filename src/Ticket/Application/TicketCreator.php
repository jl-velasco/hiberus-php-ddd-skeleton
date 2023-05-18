<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Application;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourcing;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Comment;
use Hiberus\Skeleton\Ticket\Domain\Ticket;

final class TicketCreator
{
    public function __construct(private readonly EventSourcing $repository)
    {
    }

    /**
     * @throws InvalidValueException
     */
    public function create(
        Uuid $id,
        Uuid $userId,
        Comment $comment
    ): void
    {
        $this->repository->save(Ticket::create($id, $userId, $comment));
    }
}
