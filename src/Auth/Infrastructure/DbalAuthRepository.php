<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Infrastructure;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Hiberus\Skeleton\Auth\Domain\AuthRepository;
use Hiberus\Skeleton\Auth\Domain\User;
use Hiberus\Skeleton\Shared\Domain\Criteria\Criteria;
use Hiberus\Skeleton\Shared\Domain\Exception\InternalErrorException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Infrastructure\Symfony\DbalCriteriaConverter;

final class DbalAuthRepository implements AuthRepository
{
    private const TABLE_USER = 'user';

    public function __construct(
        private readonly Connection $connection,
        private readonly DbalCriteriaConverter $dbalCriteriaConverter
    ) {
    }

    /** @throws InternalErrorException|InvalidValueException */
    public function search(Email $email): ?User
    {
        try {
            $user = $this->connection->createQueryBuilder()
                ->select('password')
                ->from(self::TABLE_USER)
                ->where('d.uuid = :email')
                ->setParameter('email', $email->value())
                ->fetchAssociative();
        } catch (Exception $e) {
            throw new InternalErrorException($e->getMessage(), $e);
        }

        return empty($user) ? null : new User($email, new Password($user['password']));
    }

    /**
     * @return User
     * @throws Exception|InvalidValueException
     */
    public function matching(Criteria $criteria): ?User
    {
        $queryBuilder = $this->dbalCriteriaConverter->convert(
            self::TABLE_USER,
            $criteria,
            $this->connection->createQueryBuilder()
        );

        $user = $queryBuilder->executeQuery()->fetchAssociative();

        return $user ? User::fromPrimitives(
            $user['email'],
            $user['password']
        ) : null;
    }
}
