<?php

namespace Hiberus\Skeleton\Shared\Domain;

use Hiberus\Skeleton\Shared\Domain\Aggregate\AggregateRoot;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvents;

interface AggregateFactory
{
    public function create(string $aggregateClass, DomainEvents $domainEvents): AggregateRoot;
}