<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\RabbitMq;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\DomainEventJsonSerializer;

final class RabbitMqEventBus
{
    public function __construct(private readonly RabbitMqConnection $connection)
    {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->publishEvent($event);
        }
    }

    private function publishEvent(DomainEvent $event): void
    {
        $body = DomainEventJsonSerializer::serialize($event);
        $routingKey = $event->eventName();

        $this->connection->publish($body, $routingKey);
    }
}
