<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;
}
