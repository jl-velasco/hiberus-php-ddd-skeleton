<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\ValueObject;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid
{
    /** @throws InvalidValueException */
    public function __construct(protected string $value)
    {
        $this->validate($value);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function isValid(string $id): bool
    {
        return RamseyUuid::isValid($id);
    }

    /** @throws InvalidValueException */
    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    /** @throws InvalidValueException */
    private function validate(string $id): void
    {
        if (!self::isValid($id)) {
            throw new InvalidValueException(sprintf('<%s> does not allow the value <%s>.', Uuid::class, $id));
        }
    }
}
