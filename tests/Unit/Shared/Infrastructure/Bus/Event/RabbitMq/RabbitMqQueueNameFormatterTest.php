<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Unit\Shared\Infrastructure\Bus\Event\RabbitMq;

use Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use PHPUnit\Framework\TestCase;

class RabbitMqQueueNameFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnValidNameFormatted(): void
    {
        $subscriberStub = 'Domain\Bus\Event\DomainEventSubscriberStub';

        $result = RabbitMqQueueNameFormatter::format($subscriberStub);
        $expected = 'domaineventsubscriberstub';

        static::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldReturnValidQueueNameFormatted(): void
    {
        $queueName = 'version.domain_event.subscriber_stub';

        $result = RabbitMqQueueNameFormatter::formatQueueName($queueName);
        $expected = 'domaineventsubscriberstub';

        static::assertSame($expected, $result);
    }
}
