<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Application\Upsert;

use Hiberus\Skeleton\Registration\Application\Find\UserFinder;
use Hiberus\Skeleton\Registration\Application\Update\UserEmailUpdater;
use Hiberus\Skeleton\Registration\Domain\UserRepository;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventBus;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Domain\Bus\Command\CommandHandler;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Name;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

final class UpsertUserCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UserCreator $creator,
        private readonly UserFinder $finder,
        private readonly UserRepository $repository,
        private readonly EventBus $bus,
    )
    {
    }

    /** @throws InvalidValueException */
    public function __invoke(UpsertUserCommand $command): void
    {
        $id = new Uuid($command->id());
        $name = new Name($command->name());
        $email = new Email($command->email());
        $password = new Password($command->password());

        try {
            $user = $this->finder->__invoke($id);
            $user->updateName($name);
            $user->updateEmail($email);
            $user->updatePassword($password);

            $events = $user->pullDomainEvents();

            if($events->count() > 0) {
                $this->repository->save($user);

                $this->bus->publish(...$events);
            }
        } catch (ResourceNotFoundException) {
            $this->creator->create($id, $name, $email, $password);
        }
    }
}
