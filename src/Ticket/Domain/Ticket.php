<?php
declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain;

use Hiberus\Skeleton\Shared\Domain\Aggregate\AggregateRoot;
use Hiberus\Skeleton\Shared\Domain\Exception\AlreadyStoredException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Date;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Events\TicketCreatedDomainEvent;
use Hiberus\Skeleton\Ticket\Domain\Events\TicketStatusChangedDomainEvent;

class Ticket extends AggregateRoot
{
    private Uuid $id;
    private Uuid $userId;
    private Status $status;
    private Comments $comments;
    private Date $createdAt;

    public function id(): Uuid
    {
        return $this->id;
    }

    public function userId(): Uuid
    {
        return $this->userId;
    }

    public function status(): Status
    {
        return $this->status;
    }

    public function comments(): Comments
    {
        return $this->comments;
    }

    public function createdAt(): Date
    {
        return $this->createdAt;
    }

    protected function getChildEntities(): array
    {
        return [$this->comments];
    }

    /**
     * @throws InvalidValueException
     * @throws AlreadyStoredException
     */
    public static function create(
        Uuid        $id,
        Uuid        $userId,
        Uuid        $commentId,
        Title       $commentTitle,
        Description $commentDescription
    ): self
    {
        $ticket = new self();

        $ticket->record(
            new TicketCreatedDomainEvent(
                $id->value(),
                $userId->value()
            )
        );

        $ticket->createComment(
            $userId,
            $commentId,
            $commentTitle,
            $commentDescription
        );

        return $ticket;
    }

    /**
     * @throws InvalidValueException
     * @throws AlreadyStoredException
     */
    public function createComment(
        Uuid        $userId,
        Uuid        $commentId,
        Title       $commentTitle,
        Description $commentDescription
    ): void
    {
        $this->comments->create(
            $this->id(),
            new Comment(
                $commentId,
                $userId,
                $commentTitle,
                $commentDescription
            )
        );
    }

    /**
     * @throws InvalidValueException
     */
    public function changeStatus(?Status $status): void
    {
        if ($status === null || $status->equals($this->status)) {
            return;
        }

        $this->record(
            new TicketStatusChangedDomainEvent(
                $this->id()->value(),
                $status->value
            )
        );
    }

    /**
     * @throws InvalidValueException
     */
    public function applyTicketCreatedDomainEvent(TicketCreatedDomainEvent $event): void
    {
        $data = $event->toPrimitives();
        $this->id = new Uuid($event->aggregateId());
        $this->userId = new Uuid((string)$data['user_id']);
        $this->status = Status::OPEN;
        $this->comments = new Comments([]);
        $this->createdAt = new Date($event->occurredOn());
    }

    public function applyTicketStatusChangedDomainEvent(TicketStatusChangedDomainEvent $event): void
    {
        $data = $event->toPrimitives();
        $this->status = Status::tryFrom($data['status']);
    }
}