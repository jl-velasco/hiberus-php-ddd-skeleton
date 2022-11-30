<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Bus\Query;

interface QueryBus
{
    public function ask(Query $query): ?Response;
}
