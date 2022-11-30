<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Application\Find;

use Hiberus\Skeleton\Registration\Domain\User;
use Hiberus\Skeleton\Registration\Domain\UserFinder as UserFinderDomain;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

final class UserFinder
{
    public function __construct(private readonly UserFinderDomain $userFinder)
    {
    }

    /** @throws ResourceNotFoundException */
    public function __invoke(Uuid $id): User
    {
        return $this->userFinder->__invoke($id);
    }
}
