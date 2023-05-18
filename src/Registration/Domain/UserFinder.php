<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain;

use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filter;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterField;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterOperator;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filters;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterValue;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

final class UserFinder
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    /** @throws ResourceNotFoundException */
    public function __invoke(Uuid $id): User
    {
        return $this->repository->search($id);
    }
}
