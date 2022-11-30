<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Date;

class InitialDataFixtures extends Fixture implements FixtureGroupInterface
{
    private const USER_TABLE = 'user';

    protected Generator $faker;

    public function __construct(
        private readonly Connection $dbalConnection
    ) {
        $this->faker = Factory::create('es_ES');
    }

    public static function getGroups(): array
    {
        return [
            'local',
        ];
    }

    /** @throws \JsonException|Exception */
    public function load(ObjectManager $manager): void
    {
        echo "1: TRUNC Database\n";
        $this->truncDatabase();
        echo "2: LOAD Users\n";
        $this->loadUsers();
    }

    /** @throws \Doctrine\DBAL\Exception */
    private function truncDatabase(): void
    {
        $tablesToDelete = [
            self::USER_TABLE,
        ];
        foreach ($tablesToDelete as $toDelete) {
            $deleteDeviceTable = sprintf('DELETE FROM %s', $toDelete);
            $this->dbalConnection->executeStatement($deleteDeviceTable);
        }
    }

    /** @throws \JsonException|Exception */
    private function loadUsers(): void
    {
        $this->insertUser([
            'id' => '91650d1e-b884-4113-b473-31a1c4e30566',
            'email' => 'user@hiberus.com',
            'name' => 'user',
            'password' => (new Password('password'))->value(),
        ]);

        for($i = 0; $i < 10; $i++) {
            $this->insertUser([]);
        }
    }

    /**
     * @param array<string, string> $user
     *
     * @throws Exception|InvalidValueException
     */
    private function insertUser(array $user): void
    {
        $this->dbalConnection->insert(
            self::USER_TABLE,
            [
                'id' => $user['uuid'] ?? $this->faker->uuid(),
                'email' => $user['email'] ?? $this->faker->email(),
                'name' => $user['name'] ?? $this->faker->name(),
                'password' => $user['password'] ?? (new Password($this->faker->word()))->value(),
                'created_at' => $user['createdAt'] ?? (new Date())->stringDateTime(),
                'updated_at' => $user['updatedAt'] ?? (new Date())->stringDateTime(),
            ]
        );
    }

}
