<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Aggregate;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvents;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourced;

abstract class AggregateRoot
{
    /** @var array<int, DomainEvent> */
    private array $uncommittedEvents = [];
    private int $playhead = -1;

    public function record(DomainEvent $event): void
    {
        $this->handleRecursively($event);

        ++$this->playhead;
        $event->setPlayhead($this->playhead);
        $this->uncommittedEvents[] = $event;
    }

    public function pullDomainEvents(): DomainEvents
    {
        $uncommittedEvents =  new DomainEvents($this->uncommittedEvents);
        $this->uncommittedEvents = [];

        return $uncommittedEvents;
    }

    public function initializeState(DomainEvents $events): void
    {
        foreach ($events as $event) {
            ++$this->playhead;
            $this->handleRecursively($event);
        }
    }

    protected function handle(DomainEvent $event): void
    {
        $method = $this->getApplyMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    protected function handleRecursively(DomainEvent $event): void
    {
        $this->handle($event);

        foreach ($this->getChildEntities() as $entity) {
            $entity->registerAggregateRoot($this);
            $entity->handleRecursively($event);
        }
    }

    /** @return EventSourced[] */
    protected function getChildEntities(): array
    {
        return [];
    }

    private function getApplyMethod(DomainEvent $event): string
    {
        $classParts = explode('\\', get_class($event));

        return 'apply'.end($classParts);
    }
}