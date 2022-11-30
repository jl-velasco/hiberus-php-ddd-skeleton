<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Unit\Shared\Infrastructure\Bus\Event\RabbitMq;

use Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPUnit\Framework\TestCase;

class RabbitMqConnectionTest extends TestCase
{
    /** @test  */
    public function shouldPublishMessage(): void
    {
        $rabbitMqConnection = $this->getConnection();

        $rabbitMqConnection->publish('publish', 'routingKey');
    }

    private function getConnection(): RabbitMqConnection
    {
        $connection = $this->createMock(AMQPStreamConnection::class);
        $connection->expects(self::once())->method('isConnected')->willReturn(false);
        $connection->expects(self::once())->method('reconnect');
        $channel = $this->createMock(AMQPChannel::class);
        $connection->expects(self::once())->method('channel')->willReturn($channel);
        $channel->expects(self::atLeastOnce())->method('exchange_declare');
        $channel->expects(self::once())->method('basic_publish');

        return new RabbitMqConnection('exchangeName', $connection);
    }
}
