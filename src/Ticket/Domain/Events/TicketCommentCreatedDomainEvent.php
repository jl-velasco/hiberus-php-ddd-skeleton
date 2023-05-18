<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain\Events;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

final class TicketCommentCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        private readonly string $id,
        private readonly string $userId,
        private readonly string $title,
        private readonly string $description,
        string $eventId = null,
        string $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'hiberus.hiberus_skeleton.event.ticket_comment.created';
    }

    /**
     * @param array<mixed> $body
     *
     * @throws InvalidValueException
     */
    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): TicketCommentCreatedDomainEvent {
        return new self(
            $aggregateId,
            $body['id'],
            $body['user_id'],
            $body['title'],
            $body['description'],
            $eventId,
            $occurredOn
        );
    }

    /** @return array<string, string> */
    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
