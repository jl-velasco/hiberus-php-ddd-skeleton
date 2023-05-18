<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Ticket\Domain;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\StringValueObject;

final class Title extends StringValueObject
{
    public const MAX_LENGTH = 30;

    /** @throws InvalidValueException */
    public function __construct(private readonly string $title)
    {
        parent::__construct($this->title);
        $this->validate();
    }

    /** @throws InvalidValueException */
    private function validate(): void
    {
        if (empty($this->title)) {
            throw new InvalidValueException($this->title);
        }

        if (strlen($this->title) > self::MAX_LENGTH) {
            throw new InvalidValueException(
                sprintf('Title must be less than %s characters', self::MAX_LENGTH)
            );
        }
    }
}
