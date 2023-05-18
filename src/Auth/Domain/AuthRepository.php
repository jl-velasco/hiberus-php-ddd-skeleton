<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Domain;

use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;

interface AuthRepository
{
    public function matching(Criteria $criteria): ?User;
}
