<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEventRepository;
use Hiberus\Skeleton\Shared\Domain\Exception\AlreadyStoredException;
use Hiberus\Skeleton\Shared\Domain\Exception\InternalErrorException;
use Hiberus\Skeleton\Shared\Domain\Utils;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Date;
use Throwable;

class DbalDomainEventRepository implements DomainEventRepository
{
    private const DOMAIN_EVENT = 'domain_event';

    public function __construct(private readonly Connection $connection)
    {
    }

    /** @throws AlreadyStoredException|InternalErrorException */
    public function save(DomainEvent $event): void
    {
        try {
            $this->connection->insert(
                self::DOMAIN_EVENT,
                [
                    'id' => $event->eventId(),
                    'aggregate_id' => $event->aggregateId(),
                    'name' => Utils::extractClassName($event),
                    'body' => Utils::jsonEncode($event->toPrimitives()),
                    'occurred_on' => (new Date($event->occurredOn()))->stringDateTime(),
                ]
            );
        } catch (UniqueConstraintViolationException $e) {
            throw new AlreadyStoredException($e->getMessage(), $e);
        } catch (Throwable $e) {
            throw new InternalErrorException($e->getMessage(), $e);
        }
    }
}
