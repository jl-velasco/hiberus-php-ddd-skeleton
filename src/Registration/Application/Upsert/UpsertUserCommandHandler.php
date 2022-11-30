<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Application\Upsert;

use Hiberus\Skeleton\Registration\Application\Find\UserFinder;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Domain\Bus\Command\CommandHandler;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidEmailAddressException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Name;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

final class UpsertUserCommandHandler implements CommandHandler
{
    public function __construct(private readonly UserCreator $creator)
    {
    }

    /** @throws InvalidValueException */
    public function __invoke(UpsertUserCommand $command): void
    {
        $id = new Uuid($command->id());
        $name = new Name($command->name());
        $email = new Email($command->email());
        $password = new Password($command->password());

        $this->creator->create($id, $name, $email, $password);
    }
}
