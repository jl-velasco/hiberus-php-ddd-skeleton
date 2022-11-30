<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Date;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

abstract class DomainEvent
{
    private string $eventId;
    private string $occurredOn;

    /** @throws InvalidValueException */
    public function __construct(
        private readonly string $aggregateId,
        string $eventId = null,
        string $occurredOn = null
    ) {
        $this->eventId = $eventId ?: Uuid::random()->value();
        $this->occurredOn = $occurredOn ?: (new Date())->stringDateTime();
    }

    /** @param array<string, mixed> $body */
    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    /** @return array<string, mixed> */
    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
