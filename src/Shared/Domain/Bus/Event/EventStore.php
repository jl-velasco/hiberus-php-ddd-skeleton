<?php
declare(strict_types = 1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

interface EventStore
{
    public function load(Uuid $id): DomainEvents;

    public function loadFromPlayhead(Uuid $id, int $playhead): DomainEvents;

    public function append(DomainEvents $domainEvents): void;
}