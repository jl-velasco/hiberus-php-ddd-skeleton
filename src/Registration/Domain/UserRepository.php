<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain;

use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

interface UserRepository
{
    public function save(User $user): void;

    public function matching(Criteria $criteria): Users;

    public function delete(Uuid $id): void;
}
