<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Unit\Shared\Infrastructure\Bus\Query;

use Hiberus\Skeleton\Shared\Domain\Bus\Query\Response;

final class FakeResponse implements Response
{
    public function __construct(private readonly int $number)
    {
    }

    public function number(): int
    {
        return $this->number;
    }
}
