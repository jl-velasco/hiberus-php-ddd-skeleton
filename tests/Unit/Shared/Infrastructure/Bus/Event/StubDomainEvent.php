<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Unit\Shared\Infrastructure\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

class StubDomainEvent extends DomainEvent
{
    public static function eventName(): string
    {
        return 'domain.event.stub';
    }

    /** @throws InvalidValueException */
    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self($aggregateId, $eventId, $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [];
    }

    public static function originName(): string
    {
        return 'test';
    }

}
