<?php

declare(strict_types = 1);

namespace Hiberus\Skeleton\Registration\Infrastructure;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Hiberus\Skeleton\Registration\Domain\User;
use Hiberus\Skeleton\Registration\Domain\UserRepository;
use Hiberus\Skeleton\Registration\Domain\Users;
use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filter;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterField;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterOperator;
use Hiberus\Skeleton\Shared\Domain\Criteria\Filters;
use Hiberus\Skeleton\Shared\Domain\Criteria\FilterValue;
use Hiberus\Skeleton\Shared\Domain\Exception\AlreadyStoredException;
use Hiberus\Skeleton\Shared\Domain\Exception\InternalErrorException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidEmailAddressException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Date;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Shared\Infrastructure\Symfony\DbalCriteriaConverter;
use Throwable;

class DbalUserRepository implements UserRepository
{
    private const TABLE_USER = 'user';

    public function __construct(
        private readonly Connection $connection,
        private readonly DbalCriteriaConverter $dbalCriteriaConverter
    ) {
    }

    /** @throws Exception|InvalidValueException */
    public function matching(Criteria $criteria): Users
    {
        $queryBuilder = $this->dbalCriteriaConverter->convert(
            self::TABLE_USER,
            $criteria,
            $this->connection->createQueryBuilder()
        );

        $result = $queryBuilder->executeQuery()->fetchAllAssociative();

        return new Users(array_map(static function ($user) {
            return User::fromPrimitives(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['password']
            );
        }, $result));
    }

    /**
     * @param User $user
     * @throws AlreadyStoredException|Exception|InternalErrorException|InvalidValueException
     */
    public function save(User $user): void
    {
        $fields = [
            'id' => $user->id()->value(),
        ];

        $filterList = array_map(static function ($field, $value) {
            return new Filter(
                new FilterField($field),
                FilterOperator::EQUAL,
                new FilterValue($value)
            );
        }, array_keys($fields), $fields);

        $criteria = new Criteria(
            new Filters(
                $filterList
            )
        );

        $users = $this->matching($criteria);
        if ($users->count() > 0) {
            $this->update($user);
        } else {
            $this->insert($user);
        }
    }

    /** @throws InternalErrorException|AlreadyStoredException */
    private function update(User $user): void
    {
        try {
            $this->connection->createQueryBuilder()
                ->update(self::TABLE_USER)
                ->set('email', ':email')
                ->set('name', ':name')
                ->set('password', ':password')
                ->set('updated_at', ':updated_at')
                ->where('id = :id')
                ->setParameters([
                    'id' => $user->id(),
                    'email' => $user->email(),
                    'name' => $user->name(),
                    'password' => $user->password(),
                    'updated_at' => new Date(),
                ])
                ->executeQuery();
        } catch (UniqueConstraintViolationException $e) {
            throw new AlreadyStoredException(sprintf('Email <%s> already exists', $user->email()->value()), $e);
        } catch (Throwable $e) {
            throw new InternalErrorException($e->getMessage(), $e);
        }
    }

    /** @throws InternalErrorException */
    public function delete(Uuid $id): void
    {
        try {
            $this->connection->delete(
                self::TABLE_USER,
                ['id' => $id]
            );
        } catch (\Throwable $exception) {
            throw new InternalErrorException($exception->getMessage(), $exception);
        }
    }

    /** @throws AlreadyStoredException|InternalErrorException */
    private function insert(User $user): void
    {
        try {
            $this->connection->insert(
                self::TABLE_USER,
                [
                    'id' => $user->id(),
                    'email' => $user->email(),
                    'name' => $user->name(),
                    'password' => $user->password(),
                ]
            );
        } catch (UniqueConstraintViolationException $e) {
            throw new AlreadyStoredException($e->getMessage(), $e);
        } catch (Throwable $e) {
            throw new InternalErrorException($e->getMessage(), $e);
        }
    }
}
