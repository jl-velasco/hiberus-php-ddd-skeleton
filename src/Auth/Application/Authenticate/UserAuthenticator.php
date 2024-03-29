<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Application\Authenticate;

use Hiberus\Skeleton\Auth\Domain\User;
use Hiberus\Skeleton\Auth\Domain\InvalidCredentials;
use Hiberus\Skeleton\Auth\Domain\UserFinder;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;

final class UserAuthenticator
{
    public function __construct( private readonly UserFinder $userFinder)
    {
    }

    public function authenticate(Email $email, Password $password): void
    {
        $user = $this->userFinder->__invoke($email);

        $this->ensureCredentialsAreValid($user, $password);
    }

    private function ensureCredentialsAreValid(User $auth, Password $password): void
    {
        if (!$auth->passwordMatches($password)) {
            throw new InvalidCredentials($auth->email());
        }
    }
}
