<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Functional;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventBus;

class FakeEventBus implements EventBus
{
    /** @var array<int, mixed> */
    protected array $message;

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->message[] = $event;
        }
    }
}
