<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain;

use Hiberus\Skeleton\Shared\Domain\Collection;

/** @extends Collection<int, User> */
final class Users extends Collection
{
    protected function type(): string
    {
        return User::class;
    }
}
