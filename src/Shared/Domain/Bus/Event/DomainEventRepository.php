<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

interface DomainEventRepository
{
    public function save(DomainEvent $event): void;
}
