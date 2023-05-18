<?php
declare(strict_types = 1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvents;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventStore;
use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filter;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterField;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterOperator;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filters;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterValue;
use Hiberus\Skeleton\Shared\Domain\Criteria\Order;
use Hiberus\Skeleton\Shared\Domain\Criteria\OrderBy;
use Hiberus\Skeleton\Shared\Domain\Criteria\OrderType;
use Hiberus\Skeleton\Shared\Domain\Exception\EventStoreException;
use Hiberus\Skeleton\Shared\Domain\Exception\InternalErrorException;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Shared\Infrastructure\Symfony\DbalCriteriaConverter;
use Throwable;

class DbalEventStore implements EventStore
{
    private const TABLE = 'events';

    public function __construct(
        private readonly Connection $connection,
        private readonly DbalCriteriaConverter $dbalCriteriaConverter
    ) {
    }

    /**
     * @throws Exception
     * @throws ResourceNotFoundException
     * @throws \JsonException
     */
    public function load(Uuid $id): DomainEvents
    {
        $queryBuilder = $this->dbalCriteriaConverter->convert(
            self::TABLE,
            new Criteria(
                new Filters(
                    [
                        new Filter(
                            new FilterField('agreggate_id'),
                            FilterOperator::EQUAL,
                            new FilterValue($id->value())
                        )
                    ]
                ),
                new Order(new OrderBy('playhead'), OrderType::ASC),
            ),
            $this->connection->createQueryBuilder()
        );

        $data = $queryBuilder->executeQuery()->fetchAllAssociative();
        if (empty($data)) {
            throw new ResourceNotFoundException('No events found for aggregate ', $id->value());
        }

        $events = new DomainEvents([]);
        foreach ($data as $event) {
            $events->add($this->deserializeEvent($event));
        }

        return $events;
    }

    /**
     * @throws ResourceNotFoundException
     * @throws \JsonException
     * @throws Exception
     */
    public function loadFromPlayhead(Uuid $id, int $playhead): DomainEvents
    {
        $queryBuilder = $this->dbalCriteriaConverter->convert(
            self::TABLE,
            new Criteria(
                new Filters(
                    [
                        new Filter(
                            new FilterField('agreggate_id'),
                            FilterOperator::EQUAL,
                            new FilterValue($id->value())
                        ),
                        new Filter(
                            new FilterField('playhead'),
                            FilterOperator::GTE,
                            new FilterValue((string) $playhead)
                        ),
                    ]
                ),
                new Order(new OrderBy('playhead'), OrderType::ASC),
            ),
            $this->connection->createQueryBuilder()
        );

        $data = $queryBuilder->executeQuery()->fetchAllAssociative();
        if (empty($data)) {
            throw new ResourceNotFoundException('No events found for aggregate ', $id->value());
        }

        $events = new DomainEvents([]);
        foreach ($data as $event) {
            $events->add($this->deserializeEvent($event));
        }

        return $events;
    }


    /**
     * @throws Exception
     * @throws InternalErrorException
     */
    public function append(DomainEvents $domainEvents): void
    {
        /** @var DomainEvent $domainEvent */
        foreach ($domainEvents as $domainEvent) {
            try {
                $this->connection->insert(
                    self::TABLE,
                    [
                        'event_id'     => $domainEvent->eventId(),
                        'agreggate_id' => $domainEvent->aggregateId(),
                        'playhead'     => $domainEvent->playhead(),
                        'payload'      => json_encode($domainEvent->toPrimitives(), JSON_THROW_ON_ERROR),
                        'recorded_on'  => $domainEvent->occurredOn(),
                        'type'         => $domainEvent::class,
                    ]
                );
            } catch (UniqueConstraintViolationException $e) {
                throw new EventStoreException('Duplicate playhead exception', 0, $e);
            } catch (Throwable $e) {
                throw new InternalErrorException($e->getMessage(), $e);
            }
        }
    }

    /**
     * @param array<string, mixed> $event
     * @throws \JsonException
     */
    private function deserializeEvent(array $event): DomainEvent
    {
        return call_user_func(
            [$event['type'], 'fromPrimitives'],
            $event['agreggate_id'],
            json_decode($event['payload'], true, 512, JSON_THROW_ON_ERROR),
            $event['event_id'],
            $event['recorded_on'],
        );
    }
}
