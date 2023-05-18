<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\ValueObject;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

final class Description extends StringValueObject
{
    public const MAX_LENGTH = 255;

    /** @throws InvalidValueException */
    public function __construct(private readonly string $description)
    {
        parent::__construct($this->description);
        $this->validate();
    }

    /** @throws InvalidValueException */
    private function validate(): void
    {
        if (empty($this->description)) {
            throw new InvalidValueException($this->description);
        }

        if (strlen($this->description) > self::MAX_LENGTH) {
            throw new InvalidValueException(
                sprintf('Description must be less than %s characters', self::MAX_LENGTH)
            );
        }
    }
}
