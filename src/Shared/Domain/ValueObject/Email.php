<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\ValueObject;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

final class Email extends StringValueObject
{
    /** @throws InvalidValueException */
    public function __construct(protected string $value)
    {
        parent::__construct($this->value);
        $this->validate();
    }

    public function isEquals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    /** @throws InvalidValueException */
    private function validate(): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidValueException($this->value);
        }
    }
}
