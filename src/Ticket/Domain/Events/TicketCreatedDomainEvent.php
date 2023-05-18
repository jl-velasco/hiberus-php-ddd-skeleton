<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain\Events;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

final class TicketCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        private readonly string $userId,
        string $eventId = null,
        string $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'hiberus.hiberus_skeleton.event.user.created';
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
    ): TicketCreatedDomainEvent {
        return new self(
            $aggregateId,
            $body['user_id'],
            $eventId,
            $occurredOn
        );
    }

    /** @return array<string, int|string> */
    public function toPrimitives(): array
    {
        return [
            'user_id' => $this->userId,
        ];
    }
}
