<?php

declare(strict_types = 1);

namespace Hiberus\Skeleton\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use PHPUnit\Framework\TestCase;

abstract class DbalTestCase extends TestCase
{
    protected Connection $connection;

    /** @throws Exception */
    protected function setUp(): void
    {
        $this->setConnection();

        $schema = new Schema();
        $this->createTables($schema);

        $platform = $this->connection->getDatabasePlatform();
        $queries = $schema->toSql($platform);

        foreach ($queries as $query) {
            $this->connection->fetchAllAssociative($query);
        }
    }

    protected function tearDown(): void
    {
        gc_collect_cycles();
        parent::tearDown();
    }

    /** @throws Exception */
    protected function setConnection(): void
    {
        $connectionParams = [
            'dbname' => 'TEST',
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $this->connection = DriverManager::getConnection($connectionParams);
    }

    abstract protected function createTables(Schema $schema): void;

    /**
     * @throws Exception
     *
     * @return array<mixed>
     */
    protected function fetchAll(string $tableName): array
    {
        return $this->connection->executeQuery("SELECT * from {$tableName}")->fetchAllAssociative();
    }
}
