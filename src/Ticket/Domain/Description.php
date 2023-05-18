<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\StringValueObject;

final class Description extends StringValueObject
{
    /** @throws InvalidValueException */
    public function __construct(private readonly string $name)
    {
        parent::__construct($this->name);
        $this->validate();
    }

    /** @throws InvalidValueException */
    private function validate(): void
    {
        if (empty($this->name)) {
            throw new InvalidValueException($this->name);
        }
    }
}
