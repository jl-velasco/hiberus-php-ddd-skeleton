<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Aggregate\AggregateRoot;
use Hiberus\Skeleton\Shared\Domain\Assert;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventBus;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourcing;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventStore;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

class EventSourcingRepository implements EventSourcing
{

    public function __construct(
        private readonly EventStore $eventStore,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function load(Uuid $id, string $aggregateClass): AggregateRoot
    {
        $this->assertExtendsEventSourcedAggregateRoot($aggregateClass);

        $domainEvents = $this->eventStore->load($id);

        /** @var AggregateRoot $aggregate */
        $aggregate = new $aggregateClass;
        $aggregate->initializeState($domainEvents);

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function save(AggregateRoot $aggregate): void
    {
        $domainEvents = $aggregate->pullDomainEvents();

        $this->eventStore->append($domainEvents);
        $this->eventBus->publish(...$domainEvents->getIterator());
    }

    private function assertExtendsEventSourcedAggregateRoot(string $class): void
    {
        Assert::subclassOf(
            $class,
            AggregateRoot::class,
            sprintf("Class '%s' is not an EventSourcedAggregateRoot.", $class)
        );
    }
}
