<?php
declare(strict_types = 1);

namespace Hiberus\Skeleton\Ticket\Domain;

use Hiberus\Skeleton\Shared\Domain\Collection;
use Hiberus\Skeleton\Shared\Domain\Exception\AlreadyStoredException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Date;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Events\TicketCommentCreatedDomainEvent;
use Hiberus\Skeleton\Ticket\Domain\Events\TicketCreatedDomainEvent;

/** @extends  Collection<int, Comment> */
class Comments extends Collection
{
    protected function type(): string
    {
        return Comment::class;
    }

    /** @throws InvalidValueException|AlreadyStoredException */
    public function create(
        Uuid $aggregataId,
        Comment $comment
    ): void
    {
        foreach ($this->getIterator() as $item) {
            if ($item->id()->value() === $comment->id()->value()) {
                throw new AlreadyStoredException(sprintf('Comment <%s> already exists', $comment->id()->value()));
            }
        }

        $this->record(
            new TicketCommentCreatedDomainEvent(
                $aggregataId->value(),
                $comment->id()->value(),
                $comment->userId()->value(),
                $comment->title()->value(),
                $comment->description()->value(),
            )
        );
    }

    /** @throws InvalidValueException */
    public function applyTicketCommentCreatedDomainEvent(TicketCommentCreatedDomainEvent $event): void
    {
        $data = $event->toPrimitives();

        $this->add(
            new Comment(
                new Uuid($data['id']),
                new Uuid($data['user_id']),
                new Title($data['title']),
                new Description($data['description']),
                new Date($event->occurredOn())
            )
        );
    }
}
