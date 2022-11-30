<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event;

use Hiberus\Skeleton\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use Traversable;

final class DomainEventSubscriberLocator
{
    private array $mapping;

    public function __construct(Traversable $mapping)
    {
        $this->mapping = iterator_to_array($mapping);
    }

    /** @return array<mixed> */
    public function allSubscribedTo(string $eventClass): array
    {
        $formatted = CallableFirstParameterExtractor::forPipedCallables($this->mapping);

        return $formatted[$eventClass];
    }

    /** @return array<mixed> */
    public function all(): array
    {
        return $this->mapping;
    }
}
