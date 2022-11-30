<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Criteria;

class Criteria
{
    public function __construct(
        private readonly Filters $filters,
        private readonly ?Order $order = null,
        private readonly ?int $offset = null,
        private readonly ?int $limit = null
    )
    {
    }

    public function hasFilters(): bool
    {
        return $this->filters->count() > 0;
    }

    public function hasOrder(): bool
    {
        if ($this->order !== null) {
            return !$this->order->isNone();
        }

        return false;
    }

    public function hasOffset(): bool
    {
        return $this->offset !== null;
    }

    public function hasLimit(): bool
    {
        return $this->limit !== null;
    }

    public function filters(): Filters
    {
        return $this->filters;
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }
}
