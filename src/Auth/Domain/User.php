<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Domain;

use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;

final class User
{
    public function __construct(
        private readonly Email $email,
        private readonly Password $password
    )
    {
    }

    public function passwordMatches(Password $password): bool
    {
        return $this->password->isEquals($password);
    }

    public function email(): Email
    {
        return $this->email;
    }

    /** @throws InvalidValueException */
    public static function fromPrimitives(
        string $email,
        string $password
    ): self {
        return new self(
            new Email($email),
            new Password($password)
        );
    }
}
