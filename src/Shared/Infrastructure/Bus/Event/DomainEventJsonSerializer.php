<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Utils;

final class DomainEventJsonSerializer
{
    public static function serialize(DomainEvent $domainEvent): string
    {
        return Utils::jsonEncode(
            [
                'data' => [
                    'id' => $domainEvent->eventId(),
                    'type' => $domainEvent::eventName(),
                    'occurred_on' => $domainEvent->occurredOn(),
                    'attributes' => array_merge($domainEvent->toPrimitives(), ['id' => $domainEvent->aggregateId()]),
                ],
                'meta' => [],
            ]
        );
    }
}
