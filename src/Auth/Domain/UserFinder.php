<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Domain;

use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filter;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterField;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterOperator;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filters;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterValue;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;

final class UserFinder
{
    public function __construct(private readonly AuthRepository $repository)
    {
    }

    public function __invoke(Email $email): User
    {
        $user = $this->repository->matching($this->criteria($email));

        if ($user === null) {
            throw new InvalidUserEmail($email);
        }

        return $user;
    }

    private function criteria(Email $email): Criteria
    {
        $fields = [
            'email' => $email->value(),
        ];

        $filterList = array_map(static function ($field, $value) {
            return new Filter(
                new FilterField($field),
                FilterOperator::EQUAL,
                new FilterValue($value)
            );
        }, array_keys($fields), $fields);

        return new Criteria(
            new Filters(
                $filterList
            )
        );
    }
}
