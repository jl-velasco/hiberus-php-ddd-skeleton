<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain\Events;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

final class TicketStatusChangedDomainEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        private readonly int $status,
        string $eventId = null,
        string $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'hiberus.hiberus_skeleton.event.ticket_status.changed';
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
    ): TicketStatusChangedDomainEvent {
        return new self(
            $aggregateId,
            $body['status'],
            $eventId,
            $occurredOn
        );
    }

    /** @return array<string, int> */
    public function toPrimitives(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
