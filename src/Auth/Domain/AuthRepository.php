<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Domain;

use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;

interface AuthRepository
{
    public function matching(Criteria $criteria): ?User;
}
