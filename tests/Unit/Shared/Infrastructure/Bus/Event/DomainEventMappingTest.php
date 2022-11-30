<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Unit\Shared\Infrastructure\Bus\Event;

use ArrayIterator;
use Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\DomainEventMapping;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class DomainEventMappingTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnNullWhenEmptyIterableList(): void
    {
        $iterable = new ArrayIterator([]);

        $mapping = new DomainEventMapping($iterable);

        $result = $mapping->for('whatever');

        self::assertNull($result);
    }

    /**
     * @test
     */
    public function shouldReturnNullWhenClassNameNotFoundInMapping(): void
    {
        $subscriberStub = new DomainEventSubscriberStub();

        $iterable = new ArrayIterator([$subscriberStub]);

        $mapping = new DomainEventMapping($iterable);

        $result = $mapping->for('whatever');

        self::assertNull($result);
    }

    /**
     * @test
     */
    public function shouldReturnClassNameWhenClassNameNotFoundInMapping(): void
    {
        $subscriberStub = new DomainEventSubscriberStub();

        $iterable = new ArrayIterator([$subscriberStub]);

        $mapping = new DomainEventMapping($iterable);

        $result = $mapping->for('domain.event.stub');
        $expected = StubDomainEvent::class;

        self::assertSame($expected, $result);
    }
}
