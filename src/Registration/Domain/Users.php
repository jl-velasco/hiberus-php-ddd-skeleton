<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain;

use Hiberus\Skeleton\Shared\Domain\Collection;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

/** @extends Collection<int, User> */
final class Users extends Collection
{
    protected function type(): string
    {
        return User::class;
    }

    /**
     * @param array<int, mixed> $users
     * @throws InvalidValueException
     */
    public static function fromArray(array $users): self
    {
        return new self(array_map(
            /** @throws InvalidValueException */
                static function ($user) {
                    return User::fromArray($user);
                }, $users)
        );
    }
}
