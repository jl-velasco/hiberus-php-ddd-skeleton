<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Application\Upsert;

use Hiberus\Skeleton\Shared\Domain\Bus\Command\Command;

final class UpsertUserCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $email,
        private readonly string $password
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
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
