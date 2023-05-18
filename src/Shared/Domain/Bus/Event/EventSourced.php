<?php

namespace Hiberus\Skeleton\Shared\Domain\Bus\Event;

interface EventSourced
{
    public function handleRecursively(DomainEvent $event): void;
}