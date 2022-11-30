<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

interface DomainEventSubscriber
{
    public function __invoke(DomainEvent $event): void;

    /** @return string[] */
    public static function subscribedTo(): array;
}
