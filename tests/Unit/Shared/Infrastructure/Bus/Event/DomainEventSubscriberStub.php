<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Unit\Shared\Infrastructure\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEventSubscriber;

class DomainEventSubscriberStub implements DomainEventSubscriber
{
    public function __invoke(DomainEvent $event): void
    {
    }

    public static function subscribedTo(): array
    {
        return [StubDomainEvent::class];
    }

    public static function queue(): string
    {
        return StubDomainEvent::class;
    }
}
