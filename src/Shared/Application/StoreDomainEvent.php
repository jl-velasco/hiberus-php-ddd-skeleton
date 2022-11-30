<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Application;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEventRepository;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEventSubscriber;

class StoreDomainEvent implements DomainEventSubscriber
{
    public function __construct(
        private readonly DomainEventRepository $repository
    ) {
    }

    public function __invoke(DomainEvent $event): void
    {
        $this->repository->save($event);
    }

    public static function subscribedTo(): array
    {
        return [DomainEvent::class];
    }
}
