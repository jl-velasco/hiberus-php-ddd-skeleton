<?php
declare(strict_types = 1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Aggregate\AggregateRoot;
use RuntimeException;

abstract class EventSourcedEntity implements EventSourced
{
    private AggregateRoot|null $aggregateRoot;

    public function handleRecursively(DomainEvent $event): void
    {
        $this->handle($event);

        foreach ($this->getChildEntities() as $entity) {
            $entity->registerAggregateRoot($this->aggregateRoot);
            $entity->handleRecursively($event);
        }
    }

    public function registerAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    protected function handle(DomainEvent $event): void
    {
        $method = $this->getApplyMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    private function getApplyMethod(DomainEvent $event): string
    {
        $classParts = explode('\\', get_class($event));

        return 'apply'.end($classParts);
    }

    protected function record(DomainEvent $event): void
    {
        $this->aggregateRoot->record($event);
    }

    /** @return array<int, mixed> */
    protected function getChildEntities(): array
    {
        return [];
    }
}