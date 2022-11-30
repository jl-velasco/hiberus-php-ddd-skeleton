<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Application\Upsert;

use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Registration\Domain\User;
use Hiberus\Skeleton\Registration\Domain\UserRepository;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventBus;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Name;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

final class UserCreator
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly EventBus $bus
    ) {
    }

    /** @throws InvalidValueException */
    public function create(
        Uuid $id,
        Name $name,
        Email $email,
        Password $password,
    ): void {
        $user = User::create($id, $name, $email, $password);

        $this->repository->save($user);

        $this->bus->publish(...$user->pullDomainEvents());
    }
}
