<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain\Events;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

final class UserUpdatedNameDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        private readonly string $name,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'hiberus.hiberus_skeleton.event.user_name.updated';
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
    ): UserUpdatedNameDomainEvent {
        return new self(
            $aggregateId,
            $body['name'],
            $eventId,
            $occurredOn
        );
    }

    /** @return array<string, string> */
    public function toPrimitives(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
