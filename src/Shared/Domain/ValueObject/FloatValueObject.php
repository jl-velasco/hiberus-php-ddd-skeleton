<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\ValueObject;

abstract class FloatValueObject
{
    public function __construct(protected float $value)
    {
    }

    public function value(): float
    {
        return $this->value;
    }
}
