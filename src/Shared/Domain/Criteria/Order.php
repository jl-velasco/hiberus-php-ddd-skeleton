<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Criteria;

final class Order
{
    public function __construct(
        private readonly OrderBy $orderBy,
        private readonly OrderType $orderType
    )
    {
    }

    public static function createDesc(OrderBy $orderBy): Order
    {
        return new self($orderBy, OrderType::DESC);
    }

    public function orderBy(): OrderBy
    {
        return $this->orderBy;
    }

    public function orderType(): OrderType
    {
        return $this->orderType;
    }

    public static function fromValues(?string $orderBy, ?string $order): Order
    {
        return null === $orderBy ? self::none() : new Order(new OrderBy($orderBy), OrderType::tryFrom($order));
    }

    public function isNone(): bool
    {
        return $this->orderType() === OrderType::NONE;
    }

    public static function none(): Order
    {
        return new Order(new OrderBy(''), OrderType::NONE);
    }
}
