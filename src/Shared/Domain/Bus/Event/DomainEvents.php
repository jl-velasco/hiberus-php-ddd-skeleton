<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Collection;

/** @extends Collection<int, DomainEvent> */
final class DomainEvents extends Collection
{
    protected function type(): string
    {
        return DomainEvent::class;
    }
}
