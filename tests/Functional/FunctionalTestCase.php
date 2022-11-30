<?php

declare(strict_types = 1);

namespace Hiberus\Skeleton\Tests\Functional;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class FunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected Connection $connection;

    /** @throws ContainerExceptionInterface|NotFoundExceptionInterface|Exception */
    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->connection = $this->getDiContainer()->get('doctrine.dbal.default_connection');

        $schema = new Schema();
        $this->createTables($schema);

        $platform = $this->connection->getDatabasePlatform();
        $queries = $schema->toSql($platform);

        foreach ($queries as $query) {
            $this->connection->fetchAllAssociative($query);
        }
        parent::setUp();
    }

    protected function tearDown(): void
    {
        gc_collect_cycles();
        parent::tearDown();
    }

    protected function getDiContainer(): ContainerInterface
    {
        return self::getContainer();
    }

    abstract protected function createTables(Schema $schema): void;

    /**
     * @param array<string, mixed> $jsonParams
     * @param array<string, mixed> $headerParams
     */
    protected function doJsonRequest(
        string $method,
        string $uri,
        array $jsonParams,
        ?string $token = null,
        array $headerParams = []
    ): Response {
        $defaultHeaders = ['HTTP_CONTENT_TYPE' => 'application/json'];

        if ($token) {
            $defaultHeaders = ['HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)];
        }

        $headers = array_merge($defaultHeaders, $headerParams);

        $jsonEncode = json_encode($jsonParams);
        $this->client->request(
            $method,
            $uri,
            [],
            [],
            $headers,
            false !== $jsonEncode ? $jsonEncode : null
        );

        return $this->client->getResponse();
    }

    /**
     * @param array<string, string> $params
     */
    protected function doRequest(string $method, string $uri, array $params = []): Response
    {
        $this->client->request($method, $uri, $params);

        return $this->client->getResponse();
    }

    /**
     * @throws Exception
     *
     * @return array<mixed>
     */
    protected function getAllFromRepository(string $tableName): array
    {
        return $this->connection->fetchAllAssociative("SELECT * FROM {$tableName}");
    }
}
