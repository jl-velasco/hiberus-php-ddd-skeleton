<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Aggregate;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;

abstract class AggregateRoot
{
    /** @var array<int, DomainEvent> */
    private array $domainEvents = [];

    /** @return array<int,DomainEvent> */
    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
