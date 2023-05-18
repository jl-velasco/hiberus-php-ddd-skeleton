<?php

declare(strict_types = 1);

namespace Hiberus\Skeleton\Ticket\Application;

use Hiberus\Skeleton\Shared\Domain\Bus\Command\CommandHandler;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Comment;
use Hiberus\Skeleton\Ticket\Domain\Description;
use Hiberus\Skeleton\Ticket\Domain\Status;
use Hiberus\Skeleton\Ticket\Domain\Title;

final class UpsertTicketCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly TicketCreator $creator,
        private readonly TicketUpdater $updater,
    )
    {
    }

    /** @throws InvalidValueException */
    public function __invoke(UpsertTicketCommand $command): void
    {
        $id = new Uuid($command->id());
        $userId = new Uuid($command->userId());
        $commentId = new Uuid($command->commentId());
        $commentTitle = new Title($command->commentTitle());
        $commentDescription = new Description($command->commentDescription());
        $comment = new Comment(
            $commentId,
            $userId,
            $commentTitle,
            $commentDescription,
        );
        $status = $command->status() !== null ? Status::tryFrom((int) $command->status()) : null;

        try {
            $this->updater->__invoke($id, $comment, $status);
        } catch (ResourceNotFoundException) {
            $this->creator->create($id, $userId, $comment);
        }
    }
}
