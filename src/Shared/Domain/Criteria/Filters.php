<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Criteria;

final class Filters
{
    /** @param Filter[] $items */
    public function __construct(protected array $items)
    {
    }

    public function add(Filter $filter): self
    {
        return new self(array_merge($this->items, [$filter]));
    }

    /** @return Filter[] */
    public function filters(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return \count($this->items);
    }
}
