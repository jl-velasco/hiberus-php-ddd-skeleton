<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\Aggregate\AggregateRoot;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

/**
 * Repository for aggregate roots.
 */
interface EventSourcing
{
    public function save(AggregateRoot $aggregate): void;

    public function load(Uuid $id, string $aggregateClass): AggregateRoot;
}
