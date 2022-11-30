<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Application\Authenticate;

use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Domain\Bus\Command\CommandHandler;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidEmailAddressException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;

final class AuthenticateUserCommandHandler implements CommandHandler
{
    public function __construct(private readonly UserAuthenticator $authenticator)
    {
    }

    /** @throws InvalidValueException */
    public function __invoke(AuthenticateUserCommand $command): void
    {
        $this->authenticator->authenticate(
            new Email($command->email()),
            new Password($command->password())
        );
    }
}
