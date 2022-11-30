<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Application\Authenticate;

use Hiberus\Skeleton\Shared\Domain\Bus\Command\Command;

final class AuthenticateUserCommand implements Command
{
    public function __construct(
        private readonly string $email,
        private readonly string $password
    )
    {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
